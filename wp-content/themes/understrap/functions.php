<?php 


add_action('after_setup_theme', function () {
    // Remove the deprecated function
    remove_action('wp_footer', 'the_block_template_skip_link');

    // Add the new function
    add_action('wp_footer', 'wp_enqueue_block_template_skip_link');
});

function enqueue_bootstrap_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css', array(), '5.3.0-alpha3');

    // Enqueue Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0-alpha3', true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_scripts');


function enqueue_jquery_cdn() {
    // Deregister WordPress's default jQuery
    wp_deregister_script('jquery');

    // Enqueue jQuery from a CDN
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_jquery_cdn');