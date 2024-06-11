<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>

	<div class="container">
		<div class="article-header-show">

			<div class="article-all">
				<?php $page_parent_id = 1814; ?>
				<a href="<?php the_permalink( $page_parent_id ); ?>">
					<?php
						$icon = get_field( 'icon', $page_parent_id );
						if ($icon) {
							echo wp_get_attachment_image($icon, '');
						}
					?>
					<?= get_the_title( $page_parent_id ) ?>
				</a>
			</div>

			<div class="article-print">
				<span><img src="<?= get_template_directory_uri() . '/assets/img/icons/s-shows.svg' ?>" alt=""> <?= view( get_the_ID() ) ?></span>
				<a href="" id="print_btn"><img src="<?= get_template_directory_uri() . '/assets/img/icons/s-print.svg' ?>" alt=""> <?= __('printing ', 'giovanni') ?></a>
			</div>

		</div>
	</div>

	<div id="print">
		<div class="article-header">
			<div class="container">
				<div class="article-header2">
					<div class="article-row-title">

						<div class="article-title">
							<h1><?= get_the_title() ?></h1>
						</div>

						<div class="article-date">
							<p><?= get_the_date('d/m/Y'); ?></p>
						</div>

					</div>
					<div class="article-row-image">

						<div class="article-image">

							<?php $subtitle = get_field('subtitle'); ?>
							<?php if ($subtitle) : ?>
							<h2 class="article-subtitle"><?= $subtitle ?></h2>
							<?php endif; ?>

							<div class="articles__thumb">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail( 'large', ['class' => 'br-25 articles__thumb_image'] ); ?>
								<?php else : ?>
									<?php get_template_part( 'template-parts/thumb', 'placer', ['size' => '-1045-475'] ) ?>
								<?php endif; ?>
							</div>
						</div>

						<?= socialShare( get_the_permalink(), get_the_title() ) ?>

					</div>
				</div>
			</div>
		</div>
		<div class="article-content">
			<div class="container">

				<?php get_template_part( 'template-parts/posts/content' ); ?>

			</div>
		</div>
	</div>

	<div class="more-articles">
		<div class="container">
			<h2>
				<?= __('More articles', 'giovanni') ?>
			</h2>
			<div class="row row__30">
				<?php
					$category = get_the_terms( get_the_ID(), 'category' );
					$array_id = [];
					foreach ( $category as $cat){
						$array_id[] = $cat->term_id;
					}
					$args = [
						'post_type' => 'post',
						'tax_query' => array(
							array(
								 'taxonomy' => 'category',
								 'field' => 'term_id',
								 'terms' => $array_id,
						 	),
						),
						'post__not_in' => array(get_the_ID()),
						'posts_per_page' => 4,
						'orderby' => 'rand',
					];

					$related_query = new WP_Query($args);
				?>

				<?php if ($related_query->have_posts()) : ?>
					<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>

						<?php get_template_part('template-parts/posts/more', 'post'); ?>

					<?php endwhile; ?>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->