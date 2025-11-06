<?php
/**
 * The template for displaying search results pages
 *
 * @package giovanni
 */

get_header();

$per_page = 24; 
$paged    = max( 1, get_query_var('paged') );

$args = array(
    'post_type'           => 'product',
    's'                   => get_search_query(false),
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
    'paged'               => $paged,
    'posts_per_page'      => $per_page,
    'no_found_rows'       => false,
    'wc_query'            => 'product_query',
    'orderby'             => 'relevance',
    'order'               => 'DESC',
);


$query      = new WP_Query($args);
$post_count = $query->found_posts;
?>

<main id="primary" class="site-main search-page">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title main-title search-title">
                <?= __('תוצאות חיפוש', 'giovanni'); ?>
            </h1>
            <p class="search-result-count">
                <?= $post_count . __( ' תוצאות עבור ', 'giovanni' ) . '<span class="search-keywords">' . get_search_query() . '</span>'; ?>
            </p>
        </header>
    </div>

    <div id="products-filter" class="products-filter">
        <div class="filter-mob">
            <button type="button" class="btn btn-no-border open-filter">
                <span class="filter-label"><?= __('מסנן', 'giovanni') ?></span>
                <svg class='icon-filter' width='24' height='24'>
                    <use href='<?= assets('img/sprite.svg#icon-filter') ?>'></use>
                </svg>
            </button>
        </div>

        <div class="products-container">
            <?php display_product_filters(get_search_query()); ?>
        </div>
    </div>

    <ul id="product-list" class="search-modal-content">
        <?php
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }
        } else {
            echo '<li class="results-not-found">' . __('לא נמצאו תוצאות', 'giovanni') . '</li>';
        }
        ?>
    </ul>

    <?php
    $big   = 999999999;
    $paged = max(1, get_query_var('paged'));

    $links = paginate_links(array(
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'total'     => $query->max_num_pages,
        'current'   => $paged,
        'prev_text' => '‹',
        'next_text' => '›',
        'type'      => 'list',
    ));

    if ($links) {
        echo '<nav class="pagination-wrap" aria-label="Search results pages">' . $links . '</nav>';
    }

    wp_reset_postdata();
    ?>

</main>

<?php get_footer(); ?>
