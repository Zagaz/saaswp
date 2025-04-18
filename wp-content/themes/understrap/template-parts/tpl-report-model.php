<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize variables
$message = '';
$page = isset($_GET['p']) ? str_replace('report-', '', $_GET['p']) : 'default';

// Determine post types
$post_types = ($page === 'full') ? ['income', 'outcome'] : [$page];

// Get the current user
$current_user = wp_get_current_user();

// Query arguments
$args = [
    'post_type'      => $post_types,
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'private',
    'author'         => $current_user->ID,
];

// Display success message if applicable
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $message = "<div class='container alert alert-success'>Entry added successfully!</div>";
}

if (!empty($message)) {
    echo $message;
}

// Fetch posts
$posts = get_posts($args);

if (empty($posts)) {
    echo "<div class='container alert alert-info'>No bills found.</div>";
} else {
    $total = 0.00;
    $page_title = ($page === 'full') ? 'Income and Outcome' : ucfirst($page);
    ?>

    <div class="container table-wrapper table-<?php echo esc_attr($page); ?>">
        <div class="d-flex justify-content-start mb-3">
            <a href="?p=add-income&a=add" class="btn btn-success me-2">Add Income</a>
            <a href="?p=add-outcome&a=add" class="btn btn-info">Add Outcome</a>
        </div>
        <h2 class="text-center">Your <?php echo esc_html($page_title); ?> Bills</h2>
        <p class="text-center">Here you can view and manage your <?php echo esc_html($page_title); ?> bills.</p>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): 
                    $id_data = $post->ID;
                    $category = get_post_type($id_data);
                    $name = get_field('bill_name', $id_data);
                    $price = get_field('bill_price', $id_data);
                    $date = get_field('bill_date', $id_data);
                    $total += ($category === 'income') ? $price : -$price;
                ?>
                    <tr>
                        <td><?php echo esc_html($id_data); ?></td>
                        <td><?php echo esc_html(ucfirst($category)); ?></td>
                        <td><?php echo esc_html($name); ?></td>
                        <td><?php echo esc_html($price); ?></td>
                        <td><?php echo esc_html($date); ?></td>
                        <td>
                            <a href="?p=edit-<?php echo esc_attr($category); ?>&dc=<?php echo esc_attr($id_data); ?>&a=edit" class="btn btn-primary">View</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-<?php echo esc_attr($id_data); ?>">Delete</button>
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal-<?php echo esc_attr($id_data); ?>" tabindex="-1" aria-labelledby="deleteModalLabel-<?php echo esc_attr($id_data); ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this <?php echo esc_html($category); ?> bill?</p>
                                    <h5><?php echo esc_html("#$id_data $name"); ?></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <a href="<?php echo esc_url(get_delete_post_link($id_data)); ?>" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-center">
                        <h3>Total: <span class="text-<?php echo esc_attr($total > 0 ? 'income' : 'outcome'); ?>"><?php echo esc_html($total); ?></span></h3>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
}
?>