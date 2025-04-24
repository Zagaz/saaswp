<?php
// You shall not pass!
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * This is the front end of the ADD/EDIT pages.
 */
?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <h1 class="mb-4 text-center"><?php echo ucwords("$action $post_type"); ?></h1>

            <?php echo $message ?? ''; // Display the message dynamically if exists ?>

            <form action="" method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="bill_name" class="form-label">Name <small class="text-muted">(E.g. Electricity, Water, etc.)</small></label>
                    <input type="text" name="bill_name" id="bill_name" value="<?php echo esc_attr($title); ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="bill_price" class="form-label">Price <small class="text-muted">(E.g. 42.00)</small></label>
                    <input type="number" name="bill_price" id="bill_price" value="<?php echo esc_attr($price); ?>" class="form-control" step="0.01" required>
                </div>

                <div class="mb-4">
                    <label for="bill_date" class="form-label">Date</label>
                    <input type="date" name="bill_date" id="bill_date" value="<?php echo esc_attr($date); ?>" class="form-control" required>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
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
