<?php
/**
 * Template Name: Configurable Page
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
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article main-wrap' ); ?>>

  <?php
		$bg = get_field('background_image');
		$bg_mob = get_field('background_image_mob');

	 	generate_picture_source($bg, $bg_mob);
	?>

  <div class="article-wrap">
    <div class="container">
      <div class="article-content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>

  <?php $content = get_field('content'); ?>
  <?php show_page_content($content); ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php get_footer(); ?>