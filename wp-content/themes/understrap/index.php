<?php

if (is_front_page()) {
    is_user_logged_in() ? get_header('logged') : get_header('blank');
} else {
    !is_user_logged_in() ? get_header('blank') : get_header('logged');

    if (isset($_POST['submit'])) {

        // here

     
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-6">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?php if (isset($_POST['name'])) { echo esc_attr($_POST['name']); } ?>" />
                    <span class="text-danger">
                        <?php
                        if (!empty($name_error)) {
                            echo $name_error;
                        }
                        ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" 
                           value="<?php if (isset($_POST['email'])) { echo esc_attr($_POST['email']); } ?>" />
                    <span class="text-danger">
                        <?php
                        if (!empty($email_error)) {
                            echo $email_error;
                        }
                        ?>
                    </span>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
        <div class="col-6">
            <div class="login-form">
                <?php 
                $args = array(
                    'echo'              => true,
                    'redirect'          => (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
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
                    'required_username' => false,
                    'required_password' => false,
                );
                wp_login_form($args);
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
