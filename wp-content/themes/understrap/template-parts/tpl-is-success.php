<?php

// You shall not pass!
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


if (is_user_logged_in()) {
    return;
}

$is_success = isset($_GET['a']) ;


if ( $is_success == 0 ){
    wp_redirect('/?p=login');
    exit;
}

if ( $is_success ){
    $alert = 'alert-success';
    $message =  'Successs! Please check your email to get your password.' ;
} else {
    $alert = 'alert-danger';
    $message = 'Error - Please try again.';

}


?>
<div class="container">
    <div class="row">
        <div class="alert <?php
                            echo esc_attr($alert);
                            ?>" role="alert">
            Successs! Please check your email to get your password.
        </div>
    </div>

    
<div class="container">
    <div class="row">
  

        <div class="col-6 form-login">
            <?php // RIGHT - Login ?>
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