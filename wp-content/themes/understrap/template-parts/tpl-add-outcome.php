<div class="container add-outcome-wrapper">
   <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6"> <!-- Responsive column -->
         <h1> 
            Add Outcome Bill
         </h1>
         <form action="" method="post">

            <div class="form-group mb-3">
               <label for="bill_name">Name</label>
               <small> (E.g. Electricity, Water, etc.)</small>
               <input type="text" name="bill_name" id="bill_name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
               <label for="bill_price">Price</label>
               <small> (E.g. 123.45)</small>
               <input type="number" name="bill_price" id="bill_price"  class="form-control" required>
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
               'hide_empty' => false,
            ));

            if (!empty($categories)) :?>
               <div class="form-group mb-3">
                  <?php
                  $categories_number = count($categories);
                  if ($categories_number > 1) : ?>
                     <label for="bill_category">Categories</label>
                  <?php else : ?>
                     <label for="bill_category">Category</label>
                  <?php endif; ?>
                  

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
               <input type="submit" name="add-outcome-bill" id="add-outcome-bill" value="Add Bill" class="btn btn-success">
               <button type="reset" class="btn btn-secondary" onclick="this.form.reset()" >Clear All</button>
            </div>

         </form>
      </div>
   </div>
</div>