<?php
if (!defined('ABSPATH')) {
    exit;
}

// Register custom post types: income and outcome
add_action('init', function () {
    $common = [
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'supports' => ['title'],
        'has_archive' => false,
        'rewrite' => false,
        'map_meta_cap' => true,
        'capability_type' => 'post',
        'publicly_queryable' => false,
    ];

    register_post_type('income', array_merge([
        'labels' => [
            'name' => __('Income', 'saaswp'),
            'singular_name' => __('Income', 'saaswp'),
        ],
        'menu_icon' => 'dashicons-chart-line',
    ], $common));

    register_post_type('outcome', array_merge([
        'labels' => [
            'name' => __('Outcome', 'saaswp'),
            'singular_name' => __('Outcome', 'saaswp'),
        ],
        'menu_icon' => 'dashicons-money-alt',
    ], $common));
});

// ACF local JSON registration for fields used in theme
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // Shared fields for income/outcome
    acf_add_local_field_group([
        'key' => 'group_saaswp_bill_fields',
        'title' => 'Bill Fields',
        'fields' => [
            [
                'key' => 'field_bill_name',
                'label' => 'Bill Name',
                'name' => 'bill_name',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_bill_price',
                'label' => 'Bill Price',
                'name' => 'bill_price',
                'type' => 'number',
                'required' => 1,
                'min' => 0,
                'step' => '0.01',
            ],
            [
                'key' => 'field_bill_date',
                'label' => 'Bill Date',
                'name' => 'bill_date',
                'type' => 'date_time_picker',
                'display_format' => 'd/m/Y h:i a',
                'return_format' => 'd/m/Y h:i a',
                'required' => 1,
            ],
            [
                'key' => 'field_user_id',
                'label' => 'User ID',
                'name' => 'user_id',
                'type' => 'number',
                'required' => 0,
                'readonly' => 1,
                'disabled' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'income',
                ],
            ],
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'outcome',
                ],
            ],
        ],
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'show_in_rest' => 0,
    ]);
});
