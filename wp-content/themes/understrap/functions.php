<?php 

// enqueue bootstrap CDN

function enqueue_bootstrap_cdn() {
    wp_enqueue_style( 'bootstrap-cdn', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );
    wp_enqueue_script( 'bootstrap-cdn-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_bootstrap_cdn' );

add_action('after_setup_theme', function () {
    // Remove the deprecated function
    remove_action('wp_footer', 'the_block_template_skip_link');

    // Add the new function
    add_action('wp_footer', 'wp_enqueue_block_template_skip_link');
});