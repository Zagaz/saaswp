<?php
// This will check if it is income or outcome;
$add_type  = $_GET['p'];
$post_type = str_replace('add-', '', $add_type);

$action = $_GET['a'] ?? '';
$entry_id = $_GET['dc'] ?? '';

// Inicializa a variável $message com a mensagem padrão
$message = "<div class='alert alert-info'>Add a new  $post_type.</div>";

// Ensure no output is sent before headers
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

      // Atualiza a variável $message com a mensagem de sucesso
      $message = "<div class='alert alert-success'><strong>Success:</strong> New $post_type added successfully.</div>";
   }
}

// Edit MODE
if ($action == 'edit') {
   $id = $entry_id;
   $post = get_post($id); // Use get_post() to retrieve the raw title
   $title = $post->post_title; // Get the raw title without "Private:"
   $price = get_field('bill_price', $id);
   $raw_date = get_field('bill_date', $id); // Retrieve the raw date

   // Debugging the raw date
   error_log('Raw date from database: ' . print_r($raw_date, true));

   // Convert DD/MM/YYYY to YYYY-MM-DD
   if (!empty($raw_date)) {
       $date_parts = date_create_from_format('d/m/Y h:i a', $raw_date);
       if ($date_parts) {
           $date = $date_parts->format('Y-m-d'); // Format the date to YYYY-MM-DD
       } else {
           $date = ''; // Leave blank if the date is invalid
       }
   } else {
       $date = ''; // Leave blank if the raw date is empty
   }

   $message = "<div class='alert alert-info'><strong> Editing:</strong> $title</div>";

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

      if (strstr($post_type, 'edit-')) {
         $post_type = str_replace('edit-', '', $post_type);
      }

      $message .= "<a href='" . esc_url(home_url('/?p=report-' . $post_type)) . "' class='btn btn-primary'>Return to Report</a>";
   }
} else {
   // Add MODE
   $id = '';
   $title = '';
   $price = '';
   $date = '';
}

?>
<div class="container add-outcome-wrapper">
   <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6"> <!-- Responsive column -->
         <h1><?php echo  ucwords("$action $post_type"); ?></h1>
         <?php
         echo $message ?? ''; // Exibe a mensagem dinamicamente
         ?>

         <form action="" method="post">

            <div class="form-group mb-3">
               <label for="bill_name">Name</label>
               <small> (E.g. Electricity, Water, etc.)</small>
               <input type="text" name="bill_name" id="bill_name" value="<?php echo esc_attr($title); ?>" class="form-control" required>
            </div>

            <div class="form-group mb-3">
               <label for="bill_price">Price</label>
               <small> (E.g. 42.00)</small>
               <input type="number" name="bill_price" id="bill_price" value="<?php echo esc_attr($price); ?>" class="form-control" step="0.01" required>
            </div>

            <div class="form-group mb-3">
               <label for="bill_date">Date</label>
               <input type="date" name="bill_date" id="bill_date" value="<?php echo esc_attr($date); ?>" class="form-control" required>
            </div>

            <div class="form-group d-flex gap-2">
               <button type="submit"
                  name="add-<?php echo esc_attr($add_type); ?>"
                  id="add-<?php echo esc_attr($add_type); ?>-bill"
                  class="btn btn-primary">






                  <?php echo ($action == 'edit') ? '<i class="fa fa-refresh"></i> Update' : '<i class="fa fa-plus"></i> Add'; ?>
               </button>
            </div>

         </form>
      </div>
   </div>
</div>