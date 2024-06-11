<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-2 post__col_4 shadow br-40' ); ?>>
	<div class="articles__thumb">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail( 'post-col-' . $num, ['class' => 'br-25 articles__thumb_image'] ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/thumb', 'placer', ['size' => '-' . $size] ) ?>
		<?php endif; ?>
	</div>

	<div class="articles__info">
		<h2 class="articles__title"><a href="<?php the_permalink() ?>"><?= get_the_title() ?></a></h2>

		<div class="articles__text">
			<?php
				$content = get_field('content');

				if ( $content ) {
					foreach ($content as $item) {
						if ( $item['text'] ) {
							echo str_word( $item['text'], $words );
							break;
						}
					}
				}
			?>
		</div>

		<div class="articles__date" dir="ltr"><?= wp_date('d/m/Y|H:i') ?></div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
