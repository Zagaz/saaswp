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
			wp_login_form();
			?>

		</div>
	</div>

</div>


<?php 
get_footer();
