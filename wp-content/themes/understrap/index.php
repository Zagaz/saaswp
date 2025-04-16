<?php

defined('ABSPATH') || exit;

if (is_front_page()) {
	is_user_logged_in() ? get_header('logged') : get_header('blank');
}
?>
<div class="container">
	<div class="row">
		<div class="col-4">1</div>
		<div class="col-4">2</div>
		<div class="col-4">3</div>
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


<?php 
get_footer();
