<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h2 class="entry-title"><?php the_title(); ?></h2>
        <div class="entry-meta">
            <?php
            $tags = get_the_terms(get_the_ID(), 'building-tag');
            if ($tags && !is_wp_error($tags)) {
                $tag_list = array();
                foreach ($tags as $tag) {
                    $tag_list[] = $tag->name;
                }
                echo '<div><strong>Cтатус руйнувань: </strong> <span class="tags">' . implode(', ', $tag_list) . '</span></div>';
            }

            $categories = get_the_terms(get_the_ID(), 'building-category');
            if ($categories && !is_wp_error($categories)) {
                $category_list = array();
                foreach ($categories as $category) {
                    $category_list[] = $category->name;
                }
                echo '<div><strong>Категорія: </strong><span class="categories">' . implode(', ', $category_list) . '</span></div>';
            }
            ?>
        </div>
    </header>
    <div class="entry-content">
        <?php the_content(); ?>
    </div>
</article>
