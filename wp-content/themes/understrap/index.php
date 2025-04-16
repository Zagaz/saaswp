<?php

defined('ABSPATH') || exit;

if (is_front_page()) {
    is_user_logged_in() ? get_header('logged') : get_header('blank');
}
?>
<div class="container">
    <div class="row">
        <div class="col-6">
            <form action="?>" method="post" class="search-form" id="registration-form">

                <label for="form-name" class="screen-reader-text"><?php esc_html_e('Search for:', 'understrap'); ?>
                Name?
                </label>
                <input type="text" name="name" id="form-name" class="form-control" placeholder="<?php esc_html_e('John Doe', 'understrap'); ?>" value="<?php echo get_search_query(); ?>" />
                <span class="error-message" id="name-error" style="color: red; display: none;"><?php esc_html_e('Name is required.', 'understrap'); ?></span>

                <label for="form-email" class="screen-reader-text"><?php esc_html_e('Search for:', 'understrap'); ?>
                Email?
                </label>
                <input type="email" name="email" id="form-email" class="form-control" placeholder="<?php esc_html_e('john.doe@email.com', 'understrap'); ?>" value="<?php echo get_search_query(); ?>" />
                <span class="error-message" id="email-error" style="color: red; display: none;"><?php esc_html_e('Please enter a valid email address.', 'understrap'); ?></span>

                <label for="form-password" class="screen-reader-text"><?php esc_html_e('Password:', 'understrap'); ?>
                Password?
                </label>
                <input type="password" name="password" id="form-password" class="form-control" placeholder="<?php esc_html_e('Enter your password', 'understrap'); ?>" />
                <span class="error-message" id="password-error" style="color: red; display: none;"><?php esc_html_e('Password must be at least 6 characters long.', 'understrap'); ?></span>

                <input type="submit" name="submit" id="form-submit" class="btn btn-primary" value="<?php esc_html_e('Register', 'understrap'); ?>" disabled />
            </form>
        </div>
        
        <div class="col-6">
            <div class="login-wp">
                <?php 
                $args = array(
                    'echo' => true, 
                    'redirect' => home_url(), // Redirect to the homepage after login
                    'form_id' => 'loginform',
                    'label_username' => __('Username or Email Address'),
                    'label_password' => __('Password'),
                    'label_remember' => __('Remember Me'),
                    'label_log_in' => __('Log In'),
                    'id_username' => 'user_login',
                    'id_password' => 'user_pass',
                    'id_remember' => 'rememberme',
                    'id_submit' => 'wp-submit',
                    'remember' => true,
                    'login_button' => true,
                    'value_username' => '',
                    'value_remember' => false,
                );

                wp_login_form($args);
                ?>
            </div>
        </div>
    </div>
</div>

<style>
.error-message {
    display: none;
    color: red;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registration-form');
    const nameInput = document.getElementById('form-name');
    const emailInput = document.getElementById('form-email');
    const passwordInput = document.getElementById('form-password');
    const submitButton = document.getElementById('form-submit');

    const nameError = document.getElementById('name-error');
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');

    // Function to validate the form
    function validateForm() {
        let isValid = true;

        // Validate name
        if (nameInput.value.trim() === '') {
            nameError.style.display = 'block'; // Show the error message
            isValid = false;
        } else {
            nameError.style.display = 'none'; // Hide the error message
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            emailError.style.display = 'block';
            isValid = false;
        } else {
            emailError.style.display = 'none';
        }

        // Validate password
        if (passwordInput.value.trim().length < 6) {
            passwordError.style.display = 'block';
            isValid = false;
        } else {
            passwordError.style.display = 'none';
        }

        // Enable or disable the submit button
        submitButton.disabled = !isValid;
    }

    // Add event listeners to validate on input
    nameInput.addEventListener('input', validateForm);
    emailInput.addEventListener('input', validateForm);
    passwordInput.addEventListener('input', validateForm);

    // Initial validation check
    validateForm();
});
</script>

<?php 
get_footer();
