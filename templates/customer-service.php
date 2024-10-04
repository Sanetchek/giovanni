<?php
/**
 * Template Name: Customer Service Page
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

get_header();

$dashboard_page_id = get_parent_page_id();
$children_page = get_current_page_children_ids($dashboard_page_id);
$is_dashboard = get_the_ID() === $dashboard_page_id;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article main-wrap' ); ?>>

  <div class="customer-header">
    <p class="customer-subtitle">
      <?php
      $subtitle = __('שירות לקוחות', 'giovanni');
      echo !$is_dashboard ? '**' . $subtitle . '**' : $subtitle;
      ?>
    </p>
    <h1 class="customer-title">
      <?php
        $dashbord_name = __('דשבורד', 'giovanni');
        echo $is_dashboard ? $dashbord_name : get_the_title();
      ?>
    </h1>
  </div>

  <div class="customer-main">
    <div class="customer-side menu">
      <ul class="customer-menu-links">
        <li class="customer-menu-item <?= $is_dashboard ? 'current' : ''; ?>">
          <a href="<?= get_permalink($dashboard_page_id) ?>" class="customer-menu-link">
            <?= $dashbord_name ?>
          </a>
        </li>
        <?php get_template_part('template-parts/customer-service/menu', '', ['children_page' => $children_page]); ?>
      </ul>
    </div>

    <div class="customer-side content">
      <?php if ($is_dashboard) {
        get_template_part('template-parts/customer-service/dashboard', '', ['children_page' => $children_page]);
      } ?>

      <?php
        $content = get_field('content');
        show_customer_service_content($content);
      ?>
    </div>
  </div>

</article><!-- #post-<?php the_ID(); ?> -->

<?php get_footer(); ?>