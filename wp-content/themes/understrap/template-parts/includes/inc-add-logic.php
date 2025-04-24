<?php
//You shall not pass!
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    return;
}else{

/**
 * This is the logic of the ADD/EDIT pages.
 */


// This will check if it is income or outcome;
//E.g.: homeurl.com/?p=add-income&a=add
$add_type  = $_GET['p']; // 
$post_type = str_replace('add-', '', $add_type);
$action = $_GET['a'] ?? ''; // Add or Edit
$entry_id = $_GET['dc'] ?? ''; // The ID of the entry to be updated.

// Starts the $message with a standard message
$message = "<div class='alert alert-info'>Add a new  $post_type.</div>";

//  ADD MODE
if (isset($_POST["add-{$add_type}"])) {
   $name = sanitize_text_field($_POST['bill_name']);
   $price = sanitize_text_field($_POST['bill_price']);
   $date = sanitize_text_field($_POST['bill_date']);

   global $wpdb;
   $user_id = get_current_user_id();

   $post_id = wp_insert_post(array(
      'post_author' => $user_id,
      'post_status' => 'private', // Keep the post private
      'post_type'   => $post_type,
      'post_title'  => $name,
      'post_content' => '',
   ));

   if ($post_id) {
      // Add custom fields
      update_field('bill_name', $name, $post_id);
      update_field('bill_price', $price, $post_id);
      update_field('bill_date', $date, $post_id);
      update_field('user_id', $user_id, $post_id);

      // Updates the $message variable with a success message
      $message = "<div class='alert alert-success'><strong>Success:</strong> New $post_type added successfully.</div>";
   }
}

// EDIT MODE
if ($action == 'edit') {
   $id = $entry_id;
   $post = get_post($id); // Use get_post() to retrieve the raw title
   $title = $post->post_title; // Get the raw title without "Private:"
   $price = get_field('bill_price', $id);
   $raw_date = get_field('bill_date', $id); // Retrieve the raw date

   // Converts d/m/Y to m/d/Y
   if (!empty($raw_date)) {
       $date_parts = date_create_from_format('d/m/Y h:i a', $raw_date);
       if ($date_parts) {
           $date = $date_parts->format('Y-m-d'); 
       } else {
           $date = ''; // Leave blank if the date is invalid
       }
   } else {
       $date = ''; // Leave blank if the raw date is empty
   }
   // This will check if it is income or outcome and updates the title.
   $message = "<div class='alert alert-info'><strong> Editing:</strong> $title</div>";

   //On click the button 
   if (isset($_POST["add-{$add_type}"])) {
      $name = sanitize_text_field($_POST['bill_name']);
      $price = sanitize_text_field($_POST['bill_price']);
      $date = sanitize_text_field($_POST['bill_date']);

      // Update the post
      $post_data = array(
         'ID'           => $id,
         'post_title'   => $name,
         'post_content' => '',
         'post_status'  => 'private', // Keep the post private
      );

      wp_update_post($post_data);

      // Update custom fields
      update_field('bill_name', $name, $id);
      update_field('bill_price', $price, $id);
      update_field('bill_date', $date, $id);

      // Refresh the $title variable with the new value
      $title = $name;

      // Update the success message
      $message = "<div class='alert alert-success'><strong>Updated </strong>successfully.</div>";

      // Checks if it is income or outcome and updates the title.
      if (strstr($post_type, 'edit-')) {
         $post_type = str_replace('edit-', '', $post_type);
      }
      // Generates a dynamic button that return to report Income or Outcome
      $message .= "<a href='" . esc_url(home_url('/?p=report-' . $post_type)) . "' class='btn btn-primary'>Return to Report</a>";
   }
} else {
   // Add MODE
   $id = '';
   $title = '';
   $price = '';
   $date = '';
}

}