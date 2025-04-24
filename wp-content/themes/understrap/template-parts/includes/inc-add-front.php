<?php
// You shall not pass!
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * This is the front end of th ADD/EDIT pages.
 */
?>
<div class="container add-outcome-wrapper">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6"> <!-- Responsive column -->
            <h1><?php echo  ucwords("$action $post_type"); ?></h1>
            <?php
            echo $message ?? ''; // Display the message dynamically if exists
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