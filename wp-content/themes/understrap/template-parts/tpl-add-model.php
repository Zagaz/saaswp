<?php
// This will check if it is income or outcome;
$add_type  = $_GET['p'];
$add_type = str_replace('add-', '', $add_type);

if (isset($_POST["add-{$add_type}-bill"])) {
   // Sanitize user inputs
   $name = sanitize_text_field($_POST['bill_name'] ?? '');
   $price = sanitize_text_field($_POST['bill_price'] ?? '');
   $date = sanitize_text_field($_POST['bill_date'] ?? '');
   $category = sanitize_text_field($_POST['bill_category'] ?? '');

   $wpdb = $GLOBALS['wpdb'];
   $user_id = get_current_user_id();
   $args = array(
      'post_title' => $name,
      'post_content' => $price,
      'post_type' => $add_type, // Income or Outcome
      'post_status' => 'private',// Can't be seen by other users
      'post_author' => $user_id,
   );
   $id_post = wp_insert_post($args);
   // ACF fields UPDATE
   update_field('user_id', $user_id, $id_post);
   update_field('bill_name', $name, $id_post);
   update_field('bill_price', $price, $id_post);
   update_field('bill_date', $date, $id_post);
   update_field('bill_category', $category, $id_post);

   // Check if the post was created successfully
   if (is_wp_error($id_post)) {
      // Handle error
      $message =  '<div class="alert alert-danger">Error creating post: ' . $id_post->get_error_message() . '</div>';
   } else {
      // Success message
      $message =  '<div class="alert alert-success">Bill created successfully!</div>';
   }
}
?>
<div class="container add-outcome-wrapper">
   <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6"> <!-- Responsive column -->
         <h1>Add <?php echo ucfirst($add_type); ?> Bill</h1>
         <?php
         echo $message ?? ''; // Display message if exists
         ?>

         <form action="" method="post">

            <div class="form-group mb-3">
               <label for="bill_name">Name</label>
               <small> (E.g. Electricity, Water, etc.)</small>
               <input type="text" name="bill_name" id="bill_name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
               <label for="bill_price">Price</label>
               <small> (E.g. 123.45)</small>
                <input type="number" name="bill_price" id="bill_price" class="form-control" step="0.01" required>
               
            </div>

            <div class="form-group mb-3">
               <label for="bill_date">Date</label>
               <input type="date" name="bill_date" id="bill_date" class="form-control" required>
            </div>
            <?php
            // Wordpress get all categories and make a select
            $categories = get_categories(array(
               'taxonomy' => 'billing-category',
               'orderby' => 'name',
               'order' => 'ASC',
               'hide_empty' => false,
            ));

            if (!empty($categories)) : ?>
               <div class="form-group mb-3">
                  <?php
                  $categories_number = count($categories);
                  if ($categories_number > 1) : ?>
                     <label for="bill_category">Categories</label>
                  <?php else : ?>
                     <label for="bill_category">Category</label>
                  <?php endif; ?>
                  <small> (Select the category for this bill)</small>
                  <select name="bill_category" id="bill_category" class="form-control">
                     <option value="">Select Category</option>
                     <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category->term_id); ?>">
                           <?php echo esc_html($category->name); ?>
                        </option>
                     <?php endforeach; ?>
                  </select>
               </div>
            <?php else : ?>
               <div class="alert alert-warning">
                  No categories found. Please add a category first.
               </div>
            <?php endif; ?>

            <div class="form-group d-flex gap-2">
               <input type="submit" name="add-<?php echo esc_attr($add_type); ?>-bill" id="add-<?php echo esc_attr($add_type); ?>-bill" value="Add" class="btn btn-success">
               <button type="reset" class="btn btn-secondary" onclick="this.form.reset()">Clear All</button>
            </div>

         </form>
      </div>
   </div>
</div>