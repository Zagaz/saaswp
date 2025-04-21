<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Initialize variables
$name_error = '';
$email_error = '';
$success_message = '';
$email = ''; // Initialize $email to avoid undefined variable warning

if (isset($_POST['submit'])) {
    // Sanitize user inputs
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');

    // Validate name
    if (empty($name)) {
        $name_error = 'Please enter your name.';
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $name_error = 'Name can only contain letters and spaces.';
    }

    // Validate email
    if (empty($email)) {
        $email_error = 'Please enter your email address.';
    } elseif (!is_email($email)) {
        $email_error = 'Please enter a valid email address.';
    } elseif (email_exists($email)) {
        $email_error = 'This email address is already registered.';
    }

    // If no errors, create the user
    if (empty($name_error) && empty($email_error)) {
        $random_password = wp_generate_password(6, true, true);

        $user_id = wp_create_user($email, $random_password, $email);

        if (!is_wp_error($user_id)) {
            // Update user details
            wp_update_user([
                'ID'           => $user_id,
                'display_name' => $name,
                'first_name'   => $name,
            ]);

            // Assign subscriber role
            $user = new WP_User($user_id);
            $user->set_role('author');

            // Prepare email
            $subject = 'Registration Confirmation';
            $blogname = get_bloginfo('name');
            $message = "Hello $name,\n\nYour account has been created successfully.\n\n";
            $message .= "You can log in using the following credentials:\n\n";
            $message .= "Username: $email\nPassword: $random_password\n\n";
            $message .= "Please keep this information safe.\n\n";
            $message .= "To log in, please visit: " . wp_login_url() . "\n\n";
            $message .= "If you did not create this account, please ignore this email.\n\n";
            $message .= "For security reasons, we recommend that you change your password after logging in.\n\n";
            $message .= "Thank you for registering with us!\n\nBest regards,\n$blogname Team";

            // Send email
            $headers = ['Content-Type: text/plain; charset=UTF-8'];
            
            // send the email 
          





            if (wp_mail($email, $subject, $message, $headers)) {
                echo "email";
                wp_redirect('/?p=login');
                
                exit;
            } else {
                error_log('Email failed to send to: ' . $email);
                $email_error = 'Error sending email. Please try again.';
            }
        } else {
            $email_error = 'Error creating user: ' . $user_id->get_error_message();
        }
    }
}

// Test email functionality


// Load the appropriate header based on login status
is_user_logged_in() ? get_header('logged') : get_header('blank');

if (is_user_logged_in() && is_front_page()) {
    $page = $_GET['p'] ?? '';

    switch ($page) {
        case 'login':
            get_template_part('template-parts/tpl-login');
            break;
        case 'dashboard':
            get_template_part('template-parts/tpl-dashboard');
            break;
        case 'add-income':
            get_template_part('template-parts/tpl-add-income');
            break;
        case 'add-outcome':
            get_template_part('template-parts/tpl-add-outcome');
            break;
        case 'report-income':
            get_template_part('template-parts/tpl-report-income');
            break;
        case 'report-outcome':
            get_template_part('template-parts/tpl-report-outcome');
            break;
        case 'report-full':
            get_template_part('template-parts/tpl-report-full');
            break;
        case 'edit-income':
            get_template_part('template-parts/tpl-edit-income');
            break;
        case 'edit-outcome':
            get_template_part('template-parts/tpl-edit-outcome');
            break;
        default:
            get_template_part('template-parts/tpl-dashboard');
            break;
    }
}

// The login and registration form only appears if the user is not logged in
if (!is_user_logged_in() && is_front_page()): ?>


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
                <form action="/?p=login" method="POST" id="registration-form">
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

<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Registration Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Your password was sent to: <span id="user-email"></span>.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registration-form');
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission for demonstration purposes

        const emailInput = document.getElementById('email');
        const userEmail = emailInput.value;

        // Set the email in the modal
        document.getElementById('user-email').textContent = userEmail;

        // Show the modal
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    });

    // Clear all inputs when the modal is dismissed
    const successModalElement = document.getElementById('successModal');
    successModalElement.addEventListener('hidden.bs.modal', function () {
        // Clear all forms on the page
        document.querySelectorAll('form').forEach(function (form) {
            form.reset();
        });
    });

    // Inputs
    document.querySelectorAll('#loginform input[type="text"], #loginform input[type="password"]').forEach(function (input) {
        input.classList.add('form-control', 'mb-3');
    });

    // Checkbox
    const rememberMeWrapper = document.querySelector('.login-remember');
    if (rememberMeWrapper) {
        rememberMeWrapper.classList.add('form-check', 'mb-3');

        const checkbox = rememberMeWrapper.querySelector('input[type="checkbox"]');
        const label = rememberMeWrapper.querySelector('label');

        if (checkbox) checkbox.classList.add('form-check-input');
        if (label) label.classList.add('form-check-label');
    }

    // Botão
    const submitBtn = document.querySelector('#wp-submit');
    if (submitBtn) {
        submitBtn.classList.remove('button', 'button-primary');
        submitBtn.classList.add('btn', 'btn-primary');
    }

    // Adiciona classe form-group nos parágrafos (exceto o botão)
    document.querySelectorAll('#loginform p').forEach(function (p) {
        if (!p.classList.contains('login-submit')) {
            p.classList.add('mb-3');
        }
    });

    // Reduce all .form-control 80% width and justify left
    document.querySelectorAll('.form-control').forEach(function (input) {
        input.style.width = '80%';
        input.style.marginLeft = '0';
    });
});
</>


<?php endif;



get_footer(); ?>
