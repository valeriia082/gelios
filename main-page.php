<?php
/*
Template Name: Main page
*/

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-filter">
            <form id="ajax-filter-form">
                <div class="filter-group filter-group__tags">
                    <h4>Статус:</h4>
                    <?php
                    $tags = get_terms(array(
                        'taxonomy' => 'building-tag', // Replace with your custom taxonomy slug
                        'hide_empty' => false, // Set to true to hide empty taxonomies
                    ));
                    foreach ($tags as $tag) {
                        echo '<span><input type="checkbox" id="'. $tag->slug . '" name="tags" value="' . $tag->slug . '"><label for="'.  $tag->slug .'">' . $tag->name . '</label></span>';
                    }
                    ?>
                </div>
                <div class="filter-group filter-group__cat">
                    <h4>Тип об'єкту:</h4>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'building-category', // Replace with your custom taxonomy slug
                        'hide_empty' => false, // Set to true to hide empty taxonomies
                    ));
                    foreach ($categories as $category) {
                        echo '<span><input type="checkbox" id="'. $category->slug . '" name="categories" value="' . $category->slug . '"><label  for="'.  $category->slug .'">' . $category->name . '</label></span>';
                    }
                    ?>
                </div>
            </form>
            <div id="ajax-filter-results">
            <?php
            $args = array(
                'post_type' => 'building',
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();

                    get_template_part('template-parts/content', 'page');
                }
            }

            wp_reset_postdata();
            ?>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();