<?php
// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Ensure only logged-in users can access this functionality
if (!is_user_logged_in()) {
    return;
} else {

    /**
     * Logic for handling ADD and EDIT operations on custom post types.
     */

    // Sanitize and parse URL parameters
    $add_type  = isset($_GET['p']) ? sanitize_key($_GET['p']) : '';
    $post_type = str_replace('add-', '', $add_type);
    $action    = isset($_GET['a']) ? sanitize_key($_GET['a']) : '';
    $entry_id  = isset($_GET['dc']) ? absint($_GET['dc']) : 0;

    // Default UI message
    $message = "<div class='alert alert-info'>Add a new " . esc_html($post_type) . ".</div>";

    // Handle ADD form submission
    if (isset($_POST["add-{$add_type}"])) {

       

        // Sanitize form input
        $name  = sanitize_text_field($_POST['bill_name']);
        $price = floatval($_POST['bill_price']);
        $date  = sanitize_text_field($_POST['bill_date']);
        $user_id = get_current_user_id();

        global $wpdb;

        // Insert post
        $post_id = wp_insert_post(array(
            'post_author' => $user_id,
            'post_status' => 'private', // Keep the post hidden
            'post_type'   => $post_type,
            'post_title'  => $name,
            'post_content'=> '',
        ));

        // Handle successful insert
        if ($post_id) {
            update_field('bill_name', $name, $post_id);
            update_field('bill_price', $price, $post_id);
            update_field('bill_date', $date, $post_id);
            update_field('user_id', $user_id, $post_id);

            $message = "<div class='alert alert-success'><strong>Success:</strong> New " . esc_html($post_type) . " added successfully.</div>";
        }
    }

    // Handle EDIT logic
    if ($action === 'edit' && $entry_id > 0) {
        $id = $entry_id;
        $post = get_post($id);

        // Ensure post exists and belongs to the user
        /** 
        if (!$post || $post->post_type !== $post_type || (int) $post->post_author !== get_current_user_id()) {
            wp_die(__('You are not allowed to edit this entry.', 'text-domain'));
        }
            */

        // Load existing data
        $title    = $post->post_title;
        $price    = get_field('bill_price', $id);
        $raw_date = get_field('bill_date', $id);

        // Convert date format for display
        if (!empty($raw_date)) {
            $date_parts = date_create_from_format('d/m/Y h:i a', $raw_date);
            $date = $date_parts ? $date_parts->format('Y-m-d') : '';
        } else {
            $date = '';
        }

        $message = "<div class='alert alert-info'><strong>Editing:</strong> " . esc_html($title) . "</div>";

        // Handle form submission for editing
        if (isset($_POST["add-{$add_type}"])) {

            

            
           

            // Sanitize input
            $name  = sanitize_text_field($_POST['bill_name']);
            $price = floatval($_POST['bill_price']);
            $date  = sanitize_text_field($_POST['bill_date']);

            // Update post
            $post_data = array(
                'ID'           => $id,
                'post_title'   => $name,
                'post_content' => '',
                'post_status'  => 'private',
            );
            wp_update_post($post_data);

            // Update ACF fields
            update_field('bill_name', $name, $id);
            update_field('bill_price', $price, $id);
            update_field('bill_date', $date, $id);

            $title = $name;

            // Success message
            $message = "<div class='alert alert-success'><strong>Updated</strong> successfully.</div>";

            // Normalize post_type if it was prefixed
            if (strstr($post_type, 'edit-')) {
                $post_type = str_replace('edit-', '', $post_type);
            }

            // Return link
            $message .= "<a href='" . esc_url(home_url('/?p=report-' . $post_type)) . "' class='btn btn-primary'>Return to Report</a>";
        }

    } else {
        // Defaults when in ADD mode
        $id = '';
        $title = '';
        $price = '';
        $date = '';
    }
}
