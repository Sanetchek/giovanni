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

$children_page = get_current_page_children();
$is_children = !empty($childrens_page);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article main-wrap' ); ?>>

  <div class="cusotem-header">
    <p class="customer-subtitle">
      <?php
      $subtitle = __('Customer service', 'giovanni');
      echo $is_children ? '**' . $subtitle . '**' : $subtitle;
      ?>
    </p>
    <h1 class="customer-title">
      <?php
        echo $is_children ? __('Dashboard', 'giovanni') : get_the_title();
      ?>
    </h1>
  </div>


  <?php $content = get_field('content'); ?>
  <?php show_customer_service_content($content); ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php get_footer(); ?>