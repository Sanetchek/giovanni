<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (is_account_page() && is_user_logged_in()) : ?>
		<header class="entry-header">
			<?php
				$current_user = wp_get_current_user();
				echo '<div class="my-account-header">';
				echo '<p>' . esc_html( $current_user->display_name ) . '</p>'; // Display user name
				the_title( '<h1 class="entry-title">', '</h1>' );
				echo '</div>';
			?>
		</header><!-- .entry-header -->
	<?php endif ?>

	<div class="container">
		<?php the_post_thumbnail(); ?>

		<div class="entry-content">
			<?php
			if ( is_account_page() && is_user_logged_in() ) { echo '<div class="profile-container">';}

			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'giovanni' ),
					'after'  => '</div>',
				)
			);

			if ( is_account_page() && is_user_logged_in() ) { echo '</div>';}
			?>
		</div><!-- .entry-content -->

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'giovanni' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
