<?php
if (!defined('ABSPATH')) { exit; }

// Admin settings page under Appearance
add_action('admin_menu', function () {
    add_theme_page(
        __('SaaSWP Mail', 'saaswp'),
        __('SaaSWP Mail', 'saaswp'),
        'manage_options',
        'saaswp-mail',
        'saaswp_mail_render_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('saaswp_mail', SAASWP_MAIL_OPTIONS_KEY, [
        'type' => 'array',
        'sanitize_callback' => 'saaswp_mail_sanitize_options',
        'default' => [],
    ]);

    add_settings_section('saaswp_mail_main', __('Mail Settings', 'saaswp'), function () {
        echo '<p>' . esc_html__('Configure the mail provider. Env vars override these values in production.', 'saaswp') . '</p>';
    }, 'saaswp_mail');

    $fields = [
        'provider' => ['label' => __('Provider', 'saaswp'), 'type' => 'select', 'choices' => [
            'resend' => 'Resend (API)', 'mailgun' => 'Mailgun (API)', 'sendgrid' => 'SendGrid (API)', 'smtp' => 'SMTP', 'php' => 'PHP mail()'
        ]],
        'from_email' => ['label' => __('From Email', 'saaswp')],
        'from_name' => ['label' => __('From Name', 'saaswp')],
        'resend_api_key' => ['label' => __('Resend API Key', 'saaswp')],
        'mailgun_api_key' => ['label' => __('Mailgun API Key', 'saaswp')],
        'mailgun_domain' => ['label' => __('Mailgun Domain', 'saaswp')],
        'mailgun_region' => ['label' => __('Mailgun Region', 'saaswp')],
        'sendgrid_api_key' => ['label' => __('SendGrid API Key', 'saaswp')],
        'smtp_host' => ['label' => __('SMTP Host', 'saaswp')],
        'smtp_port' => ['label' => __('SMTP Port', 'saaswp')],
        'smtp_secure' => ['label' => __('SMTP Secure', 'saaswp')],
        'smtp_user' => ['label' => __('SMTP Username', 'saaswp')],
        'smtp_pass' => ['label' => __('SMTP Password', 'saaswp')],
        'smtp_auth' => ['label' => __('SMTP Auth', 'saaswp')],
    ];

    foreach ($fields as $name => $def) {
        add_settings_field(
            $name,
            esc_html($def['label']),
            function () use ($name, $def) { saaswp_mail_field($name, $def); },
            'saaswp_mail',
            'saaswp_mail_main'
        );
    }
});

function saaswp_mail_sanitize_options($input) {
    $out = [];
    foreach ((array)$input as $k => $v) {
        $out[$k] = is_string($v) ? trim($v) : $v;
    }
    return $out;
}

function saaswp_mail_field($name, $def) {
    $opts = saaswp_mail_get_options();
    $val = isset($opts[$name]) ? $opts[$name] : '';
    $name_attr = SAASWP_MAIL_OPTIONS_KEY . '[' . esc_attr($name) . ']';

    if (($def['type'] ?? '') === 'select') {
        echo '<select name="' . esc_attr($name_attr) . '">';
        foreach (($def['choices'] ?? []) as $k => $label) {
            printf('<option value="%s" %s>%s</option>', esc_attr($k), selected($val, $k, false), esc_html($label));
        }
        echo '</select>';
        return;
    }

    $type = in_array($name, ['smtp_pass']) ? 'password' : 'text';
    printf('<input type="%s" class="regular-text" name="%s" value="%s" />', esc_attr($type), esc_attr($name_attr), esc_attr((string)$val));
}

function saaswp_mail_render_settings_page() {
    if (!empty($_POST['saaswp_mail_test']) && check_admin_referer('saaswp_mail_test_action', 'saaswp_mail_test_nonce')) {
        $to = sanitize_email($_POST['saaswp_test_to'] ?? get_bloginfo('admin_email'));
        $subject = 'SaaSWP Test Email';
        $body = '<p>This is a test email from SaaSWP theme.</p>';
        $sent = wp_mail($to, $subject, $body, ['Content-Type: text/html; charset=UTF-8']);
        if (is_wp_error($sent)) {
            $msg = (is_object($sent) && method_exists($sent, 'get_error_message')) ? $sent->get_error_message() : __('Unknown mail error', 'saaswp');
            echo '<div class="notice notice-error"><p>' . esc_html($msg) . '</p></div>';
        } elseif ($sent === true) {
            echo '<div class="notice notice-success"><p>' . esc_html__('Test email dispatched. Check your inbox.', 'saaswp') . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . esc_html__('Mail not sent. Check provider configuration and logs.', 'saaswp') . '</p></div>';
        }
    }

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('SaaSWP Mail', 'saaswp') . '</h1>';
    // Status summary
    $opts = saaswp_mail_get_options();
    echo '<p><strong>' . esc_html__('Active provider:', 'saaswp') . '</strong> ' . esc_html($opts['provider']) . '</p>';
    if (in_array($opts['provider'], ['resend','mailgun','sendgrid'], true)) {
        echo '<p>' . esc_html__('Using HTTP API provider which works well on Wasmer environments.', 'saaswp') . '</p>';
    } else {
        echo '<p>' . esc_html__('Using PHP/SMTP. Some serverless hosts may block SMTP ports; prefer API providers if possible.', 'saaswp') . '</p>';
    }
    echo '<form method="post" action="options.php">';
    settings_fields('saaswp_mail');
    do_settings_sections('saaswp_mail');
    submit_button();
    echo '</form>';

    // Test form
    echo '<hr />';
    echo '<h2>' . esc_html__('Send Test Email', 'saaswp') . '</h2>';
    echo '<form method="post">';
    wp_nonce_field('saaswp_mail_test_action', 'saaswp_mail_test_nonce');
    echo '<p><label>' . esc_html__('Recipient', 'saaswp') . ' <input type="email" name="saaswp_test_to" value="' . esc_attr(get_bloginfo('admin_email')) . '" class="regular-text" /></label></p>';
    echo '<p><button class="button button-secondary" name="saaswp_mail_test" value="1">' . esc_html__('Send Test', 'saaswp') . '</button></p>';
    echo '</form>';
    echo '</div>';
}
