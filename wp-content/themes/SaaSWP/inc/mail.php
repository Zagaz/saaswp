<?php
if (!defined('ABSPATH')) { exit; }

/**
 * SaaSWP Mailer
 * - Works on Wasmer: prefers HTTP API providers (Resend, Mailgun, SendGrid) to avoid blocked SMTP.
 * - Can fallback to SMTP if configured.
 * - Config via environment or theme options (Appearance > SaaSWP Mail).
 */

// Option keys
const SAASWP_MAIL_OPTIONS_KEY = 'saaswp_mail_options';

function saaswp_mail_get_options(): array {
    $defaults = [
        'provider' => getenv('SAASWP_MAIL_PROVIDER') ?: 'php', // resend|mailgun|sendgrid|smtp|php
        'from_email' => getenv('SAASWP_MAIL_FROM') ?: get_bloginfo('admin_email'),
        'from_name' => getenv('SAASWP_MAIL_FROM_NAME') ?: get_bloginfo('name'),

        // Resend
        'resend_api_key' => getenv('RESEND_API_KEY') ?: getenv('SAASWP_RESEND_API_KEY') ?: '',

        // Mailgun
        'mailgun_api_key' => getenv('MAILGUN_API_KEY') ?: getenv('SAASWP_MAILGUN_API_KEY') ?: '',
        'mailgun_domain' => getenv('MAILGUN_DOMAIN') ?: getenv('SAASWP_MAILGUN_DOMAIN') ?: '',
        'mailgun_region' => getenv('MAILGUN_REGION') ?: 'us', // us|eu

        // SendGrid
        'sendgrid_api_key' => getenv('SENDGRID_API_KEY') ?: getenv('SAASWP_SENDGRID_API_KEY') ?: '',

        // SMTP
        'smtp_host' => getenv('SMTP_HOST') ?: getenv('SAASWP_SMTP_HOST') ?: '',
        'smtp_port' => getenv('SMTP_PORT') ?: getenv('SAASWP_SMTP_PORT') ?: '',
        'smtp_secure' => getenv('SMTP_SECURE') ?: getenv('SAASWP_SMTP_SECURE') ?: 'tls', // tls|ssl|none
        'smtp_user' => getenv('SMTP_USER') ?: getenv('SAASWP_SMTP_USER') ?: '',
        'smtp_pass' => getenv('SMTP_PASS') ?: getenv('SAASWP_SMTP_PASS') ?: '',
        'smtp_auth' => getenv('SMTP_AUTH') ?: 'true',
    ];

    $saved = get_option(SAASWP_MAIL_OPTIONS_KEY, []);
    if (!is_array($saved)) { $saved = []; }
    return array_merge($defaults, $saved);
}

// Hook to set From headers consistently
add_filter('wp_mail_from', function ($from) {
    $opts = saaswp_mail_get_options();
    return !empty($opts['from_email']) ? $opts['from_email'] : $from;
});
add_filter('wp_mail_from_name', function ($name) {
    $opts = saaswp_mail_get_options();
    return !empty($opts['from_name']) ? $opts['from_name'] : $name;
});

// Intercept wp_mail and route to the configured provider
add_filter('pre_wp_mail', function ($null, $atts) {
    $opts = saaswp_mail_get_options();
    $provider = strtolower(trim($opts['provider']));

    // Normalize mail attributes
    $to = $atts['to'] ?? '';
    $subject = $atts['subject'] ?? '';
    $message = $atts['message'] ?? '';
    $headers = $atts['headers'] ?? [];
    $attachments = $atts['attachments'] ?? [];

    // Build payload
    $payload = saaswp_mail_build_payload($to, $subject, $message, $headers, $attachments, $opts);

    switch ($provider) {
        case 'resend':
            if (!empty($opts['resend_api_key'])) { return saaswp_mail_send_resend($payload, $opts); }
            return null;
        case 'mailgun':
            if (!empty($opts['mailgun_api_key']) && !empty($opts['mailgun_domain'])) { return saaswp_mail_send_mailgun($payload, $opts); }
            return null;
        case 'sendgrid':
            if (!empty($opts['sendgrid_api_key'])) { return saaswp_mail_send_sendgrid($payload, $opts); }
            return null;
        case 'smtp':
            // Let PHPMailer do SMTP via wp_mail defaults by configuring on phpmailer_init
            return null; // do not short-circuit
        case 'php':
        default:
            return null;
    }
}, 10, 2);

// Configure PHPMailer if SMTP selected
add_action('phpmailer_init', function ($phpmailer) {
    $opts = saaswp_mail_get_options();
    if (strtolower($opts['provider']) !== 'smtp') {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = $opts['smtp_host'];
    $phpmailer->Port = (int)($opts['smtp_port'] ?: 587);
    $phpmailer->SMTPAuth = filter_var($opts['smtp_auth'], FILTER_VALIDATE_BOOLEAN);
    $secure = strtolower((string)$opts['smtp_secure']);
    if ($secure === 'ssl') { $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; }
    elseif ($secure === 'tls') { $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; }
    else { $phpmailer->SMTPSecure = false; }

    if (!empty($opts['smtp_user'])) { $phpmailer->Username = $opts['smtp_user']; }
    if (!empty($opts['smtp_pass'])) { $phpmailer->Password = $opts['smtp_pass']; }
});

function saaswp_mail_build_payload($to, $subject, $message, $headers, $attachments, $opts): array {
    $to_list = is_array($to) ? $to : preg_split('/,\s*/', (string)$to, -1, PREG_SPLIT_NO_EMPTY);

    // Parse headers
    $hdrs = is_array($headers) ? $headers : preg_split("/(\r?\n)/", (string)$headers, -1, PREG_SPLIT_NO_EMPTY);
    $content_type = 'text/plain';
    $reply_to = [];
    $cc = [];
    $bcc = [];

    foreach ($hdrs as $h) {
        if (stripos($h, 'Content-Type:') === 0) {
            if (stripos($h, 'text/html') !== false) { $content_type = 'text/html'; }
        } elseif (stripos($h, 'Reply-To:') === 0) {
            $reply_to[] = trim(substr($h, 9));
        } elseif (stripos($h, 'Cc:') === 0) {
            $cc[] = trim(substr($h, 3));
        } elseif (stripos($h, 'Bcc:') === 0) {
            $bcc[] = trim(substr($h, 4));
        }
    }

    // Normalize attachments: absolute paths -> named attachments
    $files = [];
    foreach ((array)$attachments as $file) {
        if (is_string($file) && file_exists($file)) {
            $files[] = [
                'filename' => basename($file),
                'content' => base64_encode(file_get_contents($file)),
                'type' => wp_check_filetype($file)['type'] ?: 'application/octet-stream',
            ];
        }
    }

    return [
        'from' => [
            'email' => $opts['from_email'],
            'name' => $opts['from_name'],
        ],
        'to' => $to_list,
        'subject' => $subject,
        'html' => ($content_type === 'text/html') ? $message : wpautop(esc_html($message)),
        'text' => ($content_type === 'text/html') ? wp_strip_all_tags($message) : $message,
        'reply_to' => $reply_to,
        'cc' => $cc,
        'bcc' => $bcc,
        'attachments' => $files,
    ];
}

function saaswp_mail_send_resend($payload, $opts) {
    $api_key = trim((string)$opts['resend_api_key']);
    if (empty($api_key)) { return new WP_Error('mail_no_key', 'Missing Resend API key'); }

    $body = [
        'from' => sprintf('%s <%s>', $payload['from']['name'], $payload['from']['email']),
        'to' => $payload['to'],
        'subject' => $payload['subject'],
        'html' => $payload['html'],
        'text' => $payload['text'],
    ];
    if (!empty($payload['reply_to'])) {
        $body['reply_to'] = is_array($payload['reply_to']) ? reset($payload['reply_to']) : $payload['reply_to'];
    }
    if (!empty($payload['cc'])) { $body['cc'] = $payload['cc']; }
    if (!empty($payload['bcc'])) { $body['bcc'] = $payload['bcc']; }
    if (!empty($payload['attachments'])) {
        $body['attachments'] = array_map(function ($a) {
            return [
                'filename' => $a['filename'],
                'content' => $a['content'],
            ];
        }, $payload['attachments']);
    }

    $res = wp_remote_post('https://api.resend.com/emails', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => wp_json_encode($body),
        'timeout' => 20,
    ]);

    if (is_wp_error($res)) { return $res; }
    $code = wp_remote_retrieve_response_code($res);
    if ($code >= 200 && $code < 300) { return true; }
    return new WP_Error('mail_http_error', 'Resend API error', [
        'status' => $code,
        'body' => wp_remote_retrieve_body($res),
    ]);
}

function saaswp_mail_send_mailgun($payload, $opts) {
    $key = trim((string)$opts['mailgun_api_key']);
    $domain = trim((string)$opts['mailgun_domain']);
    if (empty($key) || empty($domain)) { return new WP_Error('mail_no_key', 'Missing Mailgun credentials'); }

    $region = strtolower((string)$opts['mailgun_region']) === 'eu' ? 'api.eu.mailgun.net' : 'api.mailgun.net';
    $url = sprintf('https://%s/v3/%s/messages', $region, $domain);

    // Mailgun expects multipart form
    $body = [
        'from' => sprintf('%s <%s>', $payload['from']['name'], $payload['from']['email']),
        'to' => implode(',', (array)$payload['to']),
        'subject' => $payload['subject'],
        'html' => $payload['html'],
        'text' => $payload['text'],
    ];
    if (!empty($payload['cc'])) { $body['cc'] = implode(',', (array)$payload['cc']); }
    if (!empty($payload['bcc'])) { $body['bcc'] = implode(',', (array)$payload['bcc']); }

    $args = [
        'headers' => [ 'Authorization' => 'Basic ' . base64_encode('api:' . $key) ],
        'body' => $body,
        'timeout' => 20,
    ];

    $res = wp_remote_post($url, $args);
    if (is_wp_error($res)) { return $res; }
    $code = wp_remote_retrieve_response_code($res);
    if ($code >= 200 && $code < 300) { return true; }
    return new WP_Error('mail_http_error', 'Mailgun API error', [
        'status' => $code,
        'body' => wp_remote_retrieve_body($res),
    ]);
}

function saaswp_mail_send_sendgrid($payload, $opts) {
    $key = trim((string)$opts['sendgrid_api_key']);
    if (empty($key)) { return new WP_Error('mail_no_key', 'Missing SendGrid API key'); }

    $personalization = [
        'to' => array_map(fn($e) => ['email' => $e], (array)$payload['to']),
        'subject' => $payload['subject'],
    ];
    if (!empty($payload['cc'])) {
        $personalization['cc'] = array_map(fn($e) => ['email' => $e], (array)$payload['cc']);
    }
    if (!empty($payload['bcc'])) {
        $personalization['bcc'] = array_map(fn($e) => ['email' => $e], (array)$payload['bcc']);
    }

    $body = [
        'personalizations' => [ $personalization ],
        'from' => [ 'email' => $payload['from']['email'], 'name' => $payload['from']['name'] ],
        'content' => [[ 'type' => 'text/html', 'value' => $payload['html'] ]],
    ];
    if (!empty($payload['reply_to'])) {
        $body['reply_to'] = [ 'email' => is_array($payload['reply_to']) ? reset($payload['reply_to']) : $payload['reply_to'] ];
    }
    if (!empty($payload['attachments'])) {
        $body['attachments'] = array_map(function ($a) {
            return [
                'content' => $a['content'],
                'type' => $a['type'] ?? 'application/octet-stream',
                'filename' => $a['filename'],
            ];
        }, $payload['attachments']);
    }

    $res = wp_remote_post('https://api.sendgrid.com/v3/mail/send', [
        'headers' => [
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
        ],
        'body' => wp_json_encode($body),
        'timeout' => 20,
    ]);

    if (is_wp_error($res)) { return $res; }
    $code = wp_remote_retrieve_response_code($res);
    if ($code >= 200 && $code < 300) { return true; }
    return new WP_Error('mail_http_error', 'SendGrid API error', [
        'status' => $code,
        'body' => wp_remote_retrieve_body($res),
    ]);
}
