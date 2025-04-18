<?php
// This will check if it is income or outcome;
$add_type  = $_GET['p'];
$action = $_GET['a'] ?? '';
$entry_id = $_GET['dc'] ?? '';


echo '<pre>';
echo "Add type: ";
var_dump($add_type);
echo "<br>";
echo "Action: ";
var_dump($action);
echo "<br>";
echo "Entry ID: ";
var_dump($entry_id);
echo "-----------------";
echo '</pre>';



if (isset($_POST["add-{$add_type}"])) {
  echo "Add {$add_type} button clicked";
  // Get the data from inputs and sanitize it and register it.
  

   

}

if (isset($_GET['a']) === 'edit') {
  
   echo "Edit mode";

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
               <input type="text" name="bill_name" id="bill_name" value="<?php echo esc_attr($title); ?>" class="form-control" required>
            </div>

            <div class="form-group mb-3">
               <label for="bill_price">Price</label>
               <small> (E.g. 42.00)</small>
                <input type="number" name="bill_price" id="bill_price" value="<?php echo esc_attr(get_field('bill_price', $id)); ?>" class="form-control" step="0.01" required>
               
            </div>

            <div class="form-group mb-3">
               <label for="bill_date">Date</label>
               <input type="date" name="bill_date" id="bill_date" value="<?php echo esc_attr(get_field('bill_date', $id)); ?>" class="form-control" required>
            </div>
            <script>
               //Set default date to today
              var today = new Date();
              document.getElementById("bill_date").defaultValue = today.toISOString().split('T')[0];
            </script>
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
               <?php // ACTION BUTTON ?>
               <input type="submit" 
               name="add-<?php echo esc_attr($add_type);?>" 
               id="add-<?php echo esc_attr($add_type); ?>-bill" 
               value="TODO">
               <button type="reset" class="btn btn-secondary" onclick="this.form.reset()">Clear All</button>
            </div>

         </form>
      </div>
   </div>
</div>