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

// Get the current logged-in user
$current_user = wp_get_current_user();

// Query arguments to fetch 'outcome' posts authored by the current user
$args = array(
    'post_type'      => 'outcome',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'private',
    'author'         => $current_user->ID,
);

// Fetch posts
$posts = get_posts($args);
?>

<div class="container table-wrapper table-<?php echo esc_attr($page); ?> ">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Bill ID</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post) {
                $id_data = $post->ID;
                $id_client = get_field('user_id', $id_data);
                $name = get_field('bill_name', $id_data);
                $price = get_field('bill_price', $id_data);
                $date = get_field('bill_date', $id_data);
                $post_id = $post->ID;
            ?>
                <tr>
                    <td><?php echo esc_html($post->ID); ?></td>
                    <td><?php echo esc_html($name); ?></td>
                    <td><?php echo esc_html($price); ?></td>
                    <td><?php echo esc_html($date); ?></td>
                    <td>
                        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="btn btn-primary">View</a>
                        <!-- Delete Button with Modal Trigger -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-<?php echo esc_attr($post_id); ?>">
                            Delete
                        </button>
                    </td>
                </tr>

                <!-- Bootstrap Modal -->
                <div class="modal fade" id="deleteModal-<?php echo esc_attr($post_id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel-<?php echo esc_attr($post_id); ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel-<?php echo esc_attr($post_id); ?>">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this <?php echo "$page bill?" ?></p>
                                <h5> <?php echo esc_html("#$post_id $name"); ?></h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <a href="<?php echo esc_url(get_delete_post_link($post_id)); ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>
</div>