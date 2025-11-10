<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article main-wrap' ); ?>>

  <?php
		$bg = get_field('background_image');
		$bg_mob = get_field('background_image_mob');

	 	generate_picture_source($bg, $bg_mob);
	?>

  <div class="article-wrap article-top">
    <div class="container">
      <?php
				if (function_exists('giovanni_breadcrumbs')) {
					giovanni_breadcrumbs();
				}
			?>

      <h1 class="page-title"><?= get_the_title() ?></h1>
      <div class="article-content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>

  <?php $content = get_field('content'); ?>
  <?php show_page_content($content); ?>

</article><!-- #post-<?php the_ID(); ?> -->