<?php 
// this is a wp header  - Make a basic header.

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class('body-full'); ?> >
	<?php do_action( 'wp_body_open' ); ?>
    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark mb-4">
    
    

