<?php
// You shall not pass!
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the current page from the query string
if (isset($_GET['p'])) {
    $page = $_GET['p'];
}
$page = str_replace('report-', '', $page);

// Handle "full" case to include both income and outcome
if ($page == "full") {
    $post_types = array('income', 'outcome');
} else {
    $post_types = array($page);
}

// Get the current logged-in user
$current_user = wp_get_current_user();

// Query arguments to fetch posts authored by the current user
$args = array(
    'post_type'      => $post_types,
    'posts_per_page' => -1, // Fetch all posts
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'private',
    'author'         => $current_user->ID,
);

// Fetch posts
$posts = get_posts($args);
?>

<div class="container table-wrapper table-<?php echo esc_attr($page); ?> ">
    <?php
    if ($page == "full") {
        $page = "Income and utcome";
    }

    ?>
    <h2 class="text-center">Your <?php echo esc_html(ucfirst($page)); ?> Bills</h2>
    <p class="text-center">Here you can view and manage your <?php echo esc_html(ucfirst($page)); ?> bills.</p>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Bill ID</th>
                <th scope="col">Category</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0.00;
            foreach ($posts as $post) {
                $id_data = $post->ID;
                $category = get_post_type($id_data); // Get the post type (income or outcome)
                $name = get_field('bill_name', $id_data);
                $price = get_field('bill_price', $id_data);
                $date = get_field('bill_date', $id_data);
                $post_id = $post->ID;
                if ($category == "income") {
                    $total += $price;
                } elseif ($category == "outcome") {
                    $total -= $price;
                }

            ?>
                <tr>
                    <td><?php echo esc_html($post->ID); ?></td>
                    <td><?php echo esc_html(ucfirst($category)); ?></td>
                    <td><?php echo esc_html($name); ?></td>
                    <td><?php echo esc_html($price); ?></td>
                    <td><?php echo esc_html($date); ?></td>
                    <td>
                        <a href="?p=edit-<?php echo esc_attr($category);?>&dc=<?php echo esc_attr($post_id);?>" class="btn btn-primary">View</a>
                        <!-- Delete Button with Modal Trigger -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-<?php echo esc_attr($post_id); ?>">
                            Delete
                        </button>
                    </td>
                </tr>


                <!-- Bootstrap Modal -->
                <div class="modal fade" id="deleteModal-<?php echo esc_attr($post_id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel-<?php echo esc_attr($post_id); ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel-<?php echo esc_attr($post_id); ?>">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this <?php echo esc_html($category); ?> bill?</p>
                                <h5><?php echo esc_html("#$post_id $name"); ?></h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <a href="<?php echo esc_url(get_delete_post_link($post_id)); ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <tfoot>
            <tr>
                <th scope="col">Bill ID</th>
                <th scope="col">Category</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
            </tr>
            <tr>
               
                <td colspan="12" class="text-center">
                    <?php
                    $color = ($total > 0)? 'income' : 'outcome' ; 
                    ?>
                    <h3 >Total: <span class="text-<?php echo esc_attr($color); ?>"> <?php echo esc_html($total); ?> </span> </h3>
                </td>
            </tr>
        </tfoot>
        </tbody>
    </table>
</div>