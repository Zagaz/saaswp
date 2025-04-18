<h1>Edit INCOME</h1>

<?php 

$id = $_GET['dc'] ?? '';
$post = get_post($id);

// Make $post global
global $edit_post;
$edit_post = $post;

// Include the template part
get_template_part('template-parts/tpl-add-model');