<?php

// You shall not pass!
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


if (is_user_logged_in()) {
    return;
}

$is_success = isset($_GET['a']);



if ($is_success == 0) {
    wp_redirect('/?p=login');
    exit;
}

if ($is_success) {
    $icon = 'fa fa-check';
    $alert = 'alert-success';
    $message =  'Successs! Please check your email to get your password.';
} else {
    $icon = 'fa-solid fa-circle-exclamation';
    $alert = 'alert-danger';
    $message = 'Ooops! Something went wrong - Please try again.';
}
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="alert  <?php
                                echo esc_attr($alert);
                                ?>" role="alert">
                <i class="fa <?php echo esc_attr($icon); ?>"></i> <?php echo esc_html($message); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-6 form-login">
                <?php // RIGHT - Login 
                ?>
                <h2>Login</h2>
                <div class="login-form">
                    <?php
                    if (function_exists('wp_login_form')) {
                        $args = array(
                            'echo'              => true,
                            'redirect'          => home_url(),
                            'form_id'           => 'loginform',
                            'label_username'    => __('Username or Email Address'),
                            'label_password'    => __('Password'),
                            'label_remember'    => __('Remember Me'),
                            'label_log_in'      => __('Log In'),
                            'id_username'       => 'user_login',
                            'id_password'       => 'user_pass',
                            'id_remember'       => 'rememberme',
                            'id_submit'         => 'wp-submit',
                            'remember'          => true,
                            'value_username'    => '',
                            'value_remember'    => false,
                            'form_class'        => 'form-group',
                            'before'            => '<div class="mb-3">',
                            'after'             => '</div>',
                        );
                        wp_login_form($args);
                    } else {
                        echo '<p>Login functionality is currently unavailable.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('.login-form form');
    if (!form) return;

    // Adiciona classe do Bootstrap ao formulário
    form.classList.add('p-4', 'border', 'rounded', 'bg-light');

    // Estiliza os grupos de input
    form.querySelectorAll('p').forEach(p => {
        p.classList.add('mb-3');
    });

    // Inputs
    form.querySelectorAll('input[type="text"], input[type="password"]').forEach(input => {
        input.classList.add('form-control');
    });

    // Checkbox
    const checkbox = form.querySelector('input[type="checkbox"]');
    if (checkbox) {
        checkbox.classList.add('form-check-input');
        const label = checkbox.closest('label');
        if (label) {
            label.classList.add('form-check-label');
            const wrapper = checkbox.closest('p');
            if (wrapper) {
                wrapper.classList.add('form-check');
            }
        }
    }

    // Botão de login
    const submit = form.querySelector('input[type="submit"]');
    if (submit) {
        submit.classList.add('btn', 'btn-primary', 'w-100');
    }
});
</script>
