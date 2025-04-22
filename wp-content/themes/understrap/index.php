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
                
                wp_redirect('/?p=is_success&a=1');
                
                exit;
            } else {
                error_log('Email failed to send to: ' . $email);
                wp_redirect('/?p=is_success&a=0');
            }
        } else {
            $email_error = 'Error creating user: ' . $user_id->get_error_message();
        }
    }
}

// Test email functionality


// Load the appropriate header based on login status
is_user_logged_in() ? get_header('logged') : get_header('blank');

if (!is_user_logged_in() && is_front_page()) {
    $page = $_GET['p'] ?? '';

    switch ($page) {
        case 'is_success':
            get_template_part('template-parts/tpl-is-success');
            break;
   
    }
}

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

if (!is_user_logged_in() && is_front_page()){
    get_template_part('template-parts/tpl-login' );

}

// The login and registration form only appears if the user is not logged in
if (!is_user_logged_in() && is_front_page()): ?>



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



<?php endif;



get_footer(); ?>
