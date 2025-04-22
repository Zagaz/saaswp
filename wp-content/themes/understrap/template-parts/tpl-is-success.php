<?php

// You shall not pass!
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


if (is_user_logged_in()) {
    return;
}

$is_success = isset($_GET['a']) ;


if ( $is_success == 0 ){
    wp_redirect('/?p=login');
    exit;
}

if ( $is_success ){
    $alert = 'alert-success';
    $message =  'Successs! Please check your email to get your password.' ;
} else {
    $alert = 'alert-danger';
    $message = 'Error - Please try again.';

}


?>
<div class="container">
    <div class="row">
        <div class="alert <?php
                            echo esc_attr($alert);
                            ?>" role="alert">
            Successs! Please check your email to get your password.
        </div>
    </div>

</div>