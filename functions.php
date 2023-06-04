<?php
/*
 * This is the child theme for Twenty Twenty-One theme, generated with Generate Child Theme plugin by catchthemes.
 *
 * (Please see https://developer.wordpress.org/themes/advanced-topics/child-themes/#how-to-create-a-child-theme)
 */
add_action( 'wp_enqueue_scripts', 'twenty_twenty_one_child_enqueue_styles' );
function twenty_twenty_one_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}

function create_building_custom_post_type() {
    $labels = array(
        'name'               => 'Buildings',
        'singular_name'      => 'Building',
        'menu_name'          => 'Buildings',
        'name_admin_bar'     => 'Building',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Building',
        'new_item'           => 'New Building',
        'edit_item'          => 'Edit Building',
        'view_item'          => 'View Building',
        'all_items'          => 'All Buildings',
        'search_items'       => 'Search Buildings',
        'parent_item_colon'  => 'Parent Buildings:',
        'not_found'          => 'No buildings found.',
        'not_found_in_trash' => 'No buildings found in Trash.'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'building' ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
     //   'taxonomies'          => array( 'category', 'post_tag' ),
    );

    register_post_type( 'building', $args );
}
add_action( 'init', 'create_building_custom_post_type' );


function custom_taxonomy_building_category() {
    $labels = array(
        'name'                       => _x('Building Categories', 'taxonomy general name'),
        'singular_name'              => _x('Building Category', 'taxonomy singular name'),
        'search_items'               => __('Search Building Categories'),
        'popular_items'              => __('Popular Building Categories'),
        'all_items'                  => __('All Building Categories'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __('Edit Building Category'),
        'update_item'                => __('Update Building Category'),
        'add_new_item'               => __('Add New Building Category'),
        'new_item_name'              => __('New Building Category Name'),
        'separate_items_with_commas' => __('Separate building categories with commas'),
        'add_or_remove_items'        => __('Add or remove building categories'),
        'choose_from_most_used'      => __('Choose from the most used building categories'),
        'not_found'                  => __('No building categories found.'),
        'menu_name'                  => __('Building Categories'),
    );

    $args = array(
        'hierarchical'      => true, // Set to true if it's hierarchical like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'building-category'), // Customize the slug as desired
    );

    register_taxonomy('building-category', 'building', $args);
}
add_action('init', 'custom_taxonomy_building_category', 0);

function custom_taxonomy_building_tag() {
    $labels = array(
        'name'                       => _x('Building Tags', 'taxonomy general name'),
        'singular_name'              => _x('Building Tag', 'taxonomy singular name'),
        'search_items'               => __('Search Building Tags'),
        'popular_items'              => __('Popular Building Tags'),
        'all_items'                  => __('All Building Tags'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __('Edit Building Tag'),
        'update_item'                => __('Update Building Tag'),
        'add_new_item'               => __('Add New Building Tag'),
        'new_item_name'              => __('New Building Tag Name'),
        'separate_items_with_commas' => __('Separate building tags with commas'),
        'add_or_remove_items'        => __('Add or remove building tags'),
        'choose_from_most_used'      => __('Choose from the most used building tags'),
        'not_found'                  => __('No building tags found.'),
        'menu_name'                  => __('Building Tags'),
    );

    $args = array(
        'hierarchical'      => false, // Set to false if it's non-hierarchical like tags
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'building-tag'), // Customize the slug as desired
    );

    register_taxonomy('building-tag', 'building', $args);
}
add_action('init', 'custom_taxonomy_building_tag', 0);




function enqueue_ajax_scripts() {
    wp_enqueue_script('ajax-filter', get_stylesheet_directory_uri() . '/js/ajax-filter.js', array('jquery'), '1.0', true);
    wp_localize_script('ajax-filter', 'ajax_filter_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax-filter-nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_scripts');


function ajax_filter_posts() {
    $selected_tags = $_POST['selected_tags'];
    $selected_categories = $_POST['selected_categories'];

    if(empty($selected_categories)){
        $category_slugs = get_terms(array(
            'taxonomy' => 'building-category',
            'hide_empty' => false,
        ));
        $newarray = array();
        foreach ($category_slugs as $category) {
            $newarray[] = $category->slug;
        }
        $selected_categories = $newarray;
    }
    if(empty($selected_tags)){
        $tags = get_terms(array(
            'taxonomy' => 'building-tag',
            'hide_empty' => false,
        ));
        $tag_slugs= array();
        foreach ($tags as $tag) {
            $tag_slugs[] = $tag->slug;
        }
        $selected_tags = $tag_slugs;
    }
    $args = array(
        'post_type' => 'building',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'building-category',
                'field'    => 'slug',
                'terms'    => $selected_categories,
            ),
            array(
                'taxonomy' => 'building-tag',
                'field'    => 'slug',
                'terms'    => $selected_tags,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'page');
        }
    }

    wp_reset_postdata();
    $output = ob_get_clean();
    wp_send_json_success($output);
}
add_action('wp_ajax_ajax_filter_posts', 'ajax_filter_posts');
add_action('wp_ajax_nopriv_ajax_filter_posts', 'ajax_filter_posts');
