<?php
/**
 * Plugin Name: SAASWP SMTP Enforcer (MU)
 * Description: Forces WordPress to use SMTP for all emails via environment variables. Ideal for serverless/edge deployments.
 * Author: SAASWP
 * Version: 1.1.0
 * License: MIT
 */

if (!defined('ABSPATH')) {
    exit;
}

// Allow disabling via env var if needed.
if (filter_var(getenv('SMTP_DISABLE') ?: 'false', FILTER_VALIDATE_BOOLEAN)) {
    return;
}

/**
 * Configure PHPMailer to use SMTP based on environment variables.
 *
 * Supported env vars:
 * - SMTP_HOST (required)
 * - SMTP_PORT (default: 587; or 465 if SMTP_SECURE=ssl)
 * - SMTP_SECURE (tls|ssl|starttls|none; default: tls with auto-TLS)
 * - SMTP_USERNAME / SMTP_USER (optional)
 * - SMTP_PASSWORD / SMTP_PASS (optional)
 * - SMTP_FROM / MAIL_FROM (optional)
 * - SMTP_FROM_NAME / MAIL_FROM_NAME (optional)
 * - SMTP_DEBUG (0-4; optional)
 * - SMTP_TIMEOUT (seconds; optional)
 * - SMTP_ALLOW_SELF_SIGNED (true/false; optional)
 */
add_action('phpmailer_init', function ($phpmailer) {
    $host = getenv('SMTP_HOST');
    if (!$host) {
        return; // No host provided, keep WordPress defaults.
    }

    // Basics
    $phpmailer->isSMTP();
    $phpmailer->Host = $host;

    $secure = strtolower((string) (getenv('SMTP_SECURE') ?: 'tls'));
    $portEnv = getenv('SMTP_PORT');
    if ($portEnv !== false && $portEnv !== '') {
        $port = (int) $portEnv;
    } else {
        $port = ($secure === 'ssl') ? 465 : 587;
    }
    $phpmailer->Port = $port;
    $phpmailer->CharSet = get_bloginfo('charset') ?: 'UTF-8';

    // Security
    switch ($secure) {
        case 'ssl':
            $phpmailer->SMTPSecure = 'ssl';
            $phpmailer->SMTPAutoTLS = false;
            break;
        case 'tls':
        case 'starttls':
            $phpmailer->SMTPSecure = 'tls';
            $phpmailer->SMTPAutoTLS = true;
            break;
        case 'none':
        default:
            $phpmailer->SMTPSecure = '';
            $phpmailer->SMTPAutoTLS = true; // Opportunistic
            break;
    }

    // Self-signed cert support if explicitly allowed
    $allowSelfSigned = filter_var(getenv('SMTP_ALLOW_SELF_SIGNED') ?: 'false', FILTER_VALIDATE_BOOLEAN);
    if ($allowSelfSigned) {
        $phpmailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
    }

    // Timeout
    $timeoutEnv = getenv('SMTP_TIMEOUT');
    if ($timeoutEnv !== false && $timeoutEnv !== '') {
        $phpmailer->Timeout = (int) $timeoutEnv;
    }

    // Auth
    $user = getenv('SMTP_USERNAME') ?: getenv('SMTP_USER');
    $pass = getenv('SMTP_PASSWORD') ?: getenv('SMTP_PASS');
    if ($user) {
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $user;
        $phpmailer->Password = (string) $pass;
    } else {
        $phpmailer->SMTPAuth = false; // e.g., IP-allowed relays
    }

    // From
    $from = getenv('SMTP_FROM') ?: getenv('MAIL_FROM');
    $fromName = getenv('SMTP_FROM_NAME') ?: getenv('MAIL_FROM_NAME');
    if ($from) {
        try {
            // Third param false -> do not override existing Reply-To when present
            $phpmailer->setFrom($from, $fromName ?: get_bloginfo('name'), false);
        } catch (\Exception $e) {
            // Invalid from address; fall back silently and let filters below handle
        }
    }

    // Optional debugging to error_log
    $debugLevel = (int) (getenv('SMTP_DEBUG') ?: 0);
    if ($debugLevel > 0) {
        $phpmailer->SMTPDebug = $debugLevel; // 1=client, 2=client/server, 3+=verboser
        $phpmailer->Debugoutput = static function ($str, $level) {
            error_log(sprintf('SMTP[%d]: %s', (int) $level, (string) $str));
        };
    }
});

// Ensure From and From Name are consistent with env when not explicitly set per-email.
add_filter('wp_mail_from', function ($email) {
    $env = getenv('SMTP_FROM') ?: getenv('MAIL_FROM');
    if ($env && filter_var($env, FILTER_VALIDATE_EMAIL)) {
        return $env;
    }
    return $email;
});

add_filter('wp_mail_from_name', function ($name) {
    $env = getenv('SMTP_FROM_NAME') ?: getenv('MAIL_FROM_NAME');
    if ($env) {
        return $env;
    }
    return $name;
});

// Log failures to aid debugging in production.
add_action('wp_mail_failed', function ($wp_error) {
    if (is_wp_error($wp_error)) {
        error_log('wp_mail_failed: ' . $wp_error->get_error_message());
    }
}, 10, 1);

// Optional: simple WP-CLI test command: wp saaswp smtp-test --to=<email>
if (defined('WP_CLI') && constant('WP_CLI') && class_exists('WP_CLI')) {
    call_user_func(['WP_CLI', 'add_command'], 'saaswp smtp-test', function ($args, $assoc_args) {
        $to = $assoc_args['to'] ?? getenv('SMTP_TEST_TO');
        if (!$to) {
            call_user_func(['WP_CLI', 'error'], 'Provide --to=<email> or set SMTP_TEST_TO.');
        }
        $subject = 'SAASWP SMTP test ' . wp_generate_password(6, false);
        $body = 'This is a test email sent at ' . gmdate('c') . ' (UTC).';
        $sent = wp_mail($to, $subject, $body);
        if ($sent) {
            call_user_func(['WP_CLI', 'success'], 'Test email queued successfully to ' . $to);
        } else {
            call_user_func(['WP_CLI', 'error'], 'Failed to send test email. Check logs for wp_mail_failed.');
        }
    });
}
