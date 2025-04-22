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

// enqueue styles
function enqueue_styles() {
    // Enqueue your custom stylesheet
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/style.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'enqueue_styles');

// disable admin bar for authors
add_action('after_setup_theme', function () {
    if (!current_user_can('administrator')) {
        add_filter('show_admin_bar', '__return_false');
    }
});

function add_fontawesome_cdn() {
    wp_enqueue_style(
        'fontawesome-cdn',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', // você pode atualizar a versão se necessário
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'add_fontawesome_cdn');


add_action('template_redirect', function () {
    if (!is_user_logged_in()) {
        $current_url = $_SERVER['REQUEST_URI'] ?? '';

        // Evita loop de redirecionamento quando já está na página /?p=login
        if (strpos($current_url, '/?p=login') === false && strpos($current_url, 'wp-login.php') === false) {
            wp_redirect(home_url('/?p=login'));
            exit;
        }
    }
});
