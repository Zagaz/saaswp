<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Initialize variables
$name_error = '';
$email_error = '';
$success_message = '';

?>

<div class="container">
    <div class="row">
        <div class="col-6 form-registration">
            <?php // LEFT - Registration ?>
            <h2>Registration</h2>
            <p>Fill in the form below to register.</p>
            <?php if (!empty($success_message)) : ?>
                <div class="alert alert-success">
                    <?php echo esc_html($success_message); ?>
                </div>
            <?php endif; ?>

            <div class="registration-form <?php echo !empty($success_message) ? 'd-none' : ''; ?>">
                <form action="/?p=is_success" method="post" id="registration-form">
                    <div class="">
                        <label for="name">Name</label>
                        <input type="text" class="form-control mb-3" id="name" name="name" 
                               value="<?php echo esc_attr($_POST['name'] ?? ''); ?>" />
                        <span class="text-danger mb-3"><?php echo esc_html($name_error); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control mb-3" id="email" name="email" 
                               value="<?php echo esc_attr($_POST['email'] ?? ''); ?>" />
                        <span class="text-danger mb-3"><?php echo esc_html($email_error); ?></span>
                    </div>
                    <div>
                        <small>You password will be sent to your email.</small>

                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Register</button>
                </form>
            </div>
        </div>

        <div class="col-6 form-login">
            <?php // RIGHT - Login ?>
            <h2>Login</h2>
            <p>Already have an account? Log in below.</p>
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