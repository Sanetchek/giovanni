<?php

/**
 * Break content on N words
 *
 * @param [type] $text
 * @param integer $counttext
 * @param string $sep
 * @return void
 */
function str_word($text, $counttext = 30, $sep = ' ')
{
	$text = wp_strip_all_tags($text);
	$words = explode($sep, $text);

	if (count($words) > $counttext)
		$text = join($sep, array_slice($words, 0, $counttext));

	return $text;
}

/**
 * Counting the number of page visits
 */
add_action('wp_head', function ($args = []) {
	global $user_ID, $post, $wpdb;
	$cont_id = '';

	$rg = (object) wp_parse_args($args, [
		// The post meta field key, where the number of views will be recorded.
		'meta_key' => 'views',
		// Whose visits count? 0 - All. 1 - Guests only. 2 - Only registered users.
		'who_count' => 0,
		// Exclude bots, robots? 0 - no, let them also count. 1 - yes, exclude from the count.
		'exclude_bots' => true,
	]);

	$do_count = false;
	switch ($rg->who_count) {
		case 0:
			$do_count = true;
			break;
		case 1:
			if (!$user_ID) {
				$do_count = true;
			}
			break;
		case 2:
			if ($user_ID) {
				$do_count = true;
			}
			break;
	}

	if ($do_count && $rg->exclude_bots) {
		$notbot = 'Mozilla|Opera'; // Chrome|Safari|Firefox|Netscape - all equal Mozilla
		$bot = 'Bot/|robot|Slurp/|yahoo';
		if (
			!preg_match("/$notbot/i", $_SERVER['HTTP_USER_AGENT']) ||
			preg_match("~$bot~i", $_SERVER['HTTP_USER_AGENT'])
		) {
			$do_count = false;
		}
	}

	if ($do_count) {
		// for single page
		if (is_singular()) {
			$up = $wpdb->query(
				$wpdb->prepare(
					"UPDATE $wpdb->postmeta SET meta_value = (meta_value+1) WHERE post_id = %d AND meta_key = %s",
					$post->ID,
					$rg->meta_key
				)
			);

			if (!$up) {
				add_post_meta($post->ID, $rg->meta_key, 1, true);
			}

			wp_cache_delete($post->ID, 'post_meta');
		}

		// for user/author page
		if (is_author()) {
			$user = get_user_by('slug', get_query_var('author_name'));
			$up = $wpdb->query(
				$wpdb->prepare(
					"UPDATE $wpdb->usermeta SET meta_value = (meta_value+1) WHERE user_id = %d AND meta_key = %s",
					$user->ID,
					$rg->meta_key
				)
			);

			if (!$up) {
				add_user_meta($user->ID, $rg->meta_key, 1, true);
			}

			wp_cache_delete($user->ID, 'user_meta');
		}
	}
});

/**
 * Count Page View
 *
 * @param string $cont_id
 * @param boolean $user
 * @return void
 */
function view($cont_id = '', $user = false)
{
	global $post;

	if (!$cont_id) {
		$cont_id = $post->ID;
	}

	$view = get_post_meta($cont_id, 'views', true);

	if ($user) {
		$view = get_user_meta($cont_id, 'views', true);
	}

	if ($view) {
		if ($view > 999999) {
			$view /= 1000000;
			$view = round($view, 1);
			return $view . 'KK';
		} elseif ($view > 999) {
			$view /= 1000;
			$view = round($view, 1);
			return $view . 'K';
		} else {
			return $view;
		}
	} else {
		return '0';
	}
}

function query_user_liked_posts() {
	$current_user = wp_get_current_user();
	$user_ip = $_SERVER['REMOTE_ADDR'];

	$args = array(
		'post_type' => 'product', // Adjust post type as needed
		'posts_per_page' => -1, // Retrieve all posts
		'has_password' => false,
	);

	if (is_user_logged_in()) {
		$args['meta_query'] = array(
			array(
				'key' => '_user_liked',
				'value' => $current_user->user_nicename,
				'compare' => 'LIKE',
			),
		);
	} else {
		$args['meta_query'] = array(
			array(
				'key' => '_user_ip',
				'value' => $user_ip,
				'compare' => 'LIKE',
			),
		);
	}

	$posts = get_posts($args);
	$count = count($posts);

	return array('query' => $posts, 'count' => $count);
}

/**
 * Login redirect if not administrator
 */
add_action('admin_init', function () {
	if (is_admin() && !(current_user_can('administrator')) && (!defined('DOING_AJAX') || !DOING_AJAX)) {
		wp_redirect(home_url(404), 302);
		exit ();
	}
});

/**
 * Contact form 7 remove span
 */
add_filter('wpcf7_form_elements', function ($content) {
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

	$content = str_replace('<br />', '', $content);

	return $content;
});

/**
 * Get Current Url
 */
function currUrl()
{
	global $wp;
	return home_url($wp->request);
}

/**
 * get assets
 */
function assets($source = '')
{
	return get_template_directory_uri() . '/assets/' . $source;
}

/**
 * get image
 */
function get_image($img = '', $thumb = 'large', $attr = [])
{
	return wp_get_attachment_image($img, $thumb, '', $attr);
}

/**
 * show image
 */
function show_image($img = '', $thumb = 'large', $attr = [])
{
	if ($img) {
		echo wp_get_attachment_image($img, $thumb, '', $attr);
	}
}

/**
 * show video
 */
function show_video($url = '')
{
	if ($url) {
		echo '
		<div class="video-container play">
			<video class="video-element" autoplay muted loop playsinline>
				<source src="' . $url . '" type="video/mp4">
				Your browser does not support the video tag.
			</video>
			<button class="video-controls">
				<span class="icon-stop"></span>
				<span class="icon-play"></span>
			</button>
		</div>
		';
	}
}

/**
 * Social Share
 *
 * @param [string] $url
 * @param [string] $title
 * @return string
 */
function socialShare($url, $title)
{
	return '<div class="social_share">
		<div class="article-social">
			<a class="social_share_link social_share_whatsapp"
				href="https://api.whatsapp.com/send?text=' . $title . '&url=' . $url . '"
				title="Whatsapp" rel="nofollow noopener" target="_blank">
				<span class="social_share_svg">
					<svg class="icon-whatsapp" width="24" height="24">
						<use href="'. assets('img/sprite.svg#icon-whatsapp') .'"></use>
					</svg>
				</span>
			</a>
			<a class="social_share_link social_share_facebook"
				href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '"
				title="Facebook" rel="nofollow noopener" target="_blank">
				<span class="social_share_svg">
					<svg class="icon-facebook" width="24" height="24">
						<use href="'. assets('img/sprite.svg#icon-facebook') .'"></use>
					</svg>
				</span>
			</a>
			<a class="social_share_link social_share_gmail"
				href="mailto:' .
		$url . '?subject=' . $title . '"
				title="Mail" rel="nofollow noopener" target="_blank">
				<span class="social_share_svg">
					<svg class="icon-envelope" width="24" height="24">
						<use href="'. assets('img/sprite.svg#icon-envelope') .'"></use>
					</svg>
				</span>
			</a>
		</div>
	</div>';
}

/**
 * Show YouTube Video
 */
function showYoutubeVideo($link)
{
	$video = $link;
	$video = substr($video, strpos($video, "=") + 1);
	if ($link): ?>
		<iframe width="635" height="405" src="https://www.youtube.com/embed/<?= $video ?>" title="YouTube video player"
			frameborder="0" allow="autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
			allowfullscreen></iframe>
	<?php else: ?>
		<img src="https://img.youtube.com/vi/<?php $video ?>/default.jpg" class="br-40" alt="youtube">
	<?php endif;
}

/**
 * Show Logo
 *
 * @param [type] $image
 * @param [type] $text
 * @return void
 */
function show_logo($image, $text) { ?>
	<a href="/" class="site-branding" rel="home" aria-current="page" tabindex="0">
	<?php
		$logo = get_field($image, 'option');

		if ($logo['image']) :

			show_image( $logo['image'], [300, 50], ['class' => 'site-logo'] );

		else :
			$logo = get_field($text, 'option');

			if ($logo['name']) :
				echo $logo['name'];
			endif;

			if ($logo['label']) : ?>
				<span class="branding-label"><?= $logo['label'] ?></span>
			<?php endif;
		endif; ?>
	</a>
	<?php
}

/**
 * Show Burger icon
 *
 * @param boolean $is_active
 * @return void
 */
function show_burger($is_active = false) {
	$is_active = $is_active ? 'open' : '';
	?>
	<div id="burger-menu" class="burger-menu <?= $is_active ?>">
		<div class="burger-bar"></div>
		<div class="burger-bar"></div>
		<div class="burger-bar"></div>
	</div>
	<?php
}

/**
 * Function to show breadcrumbs
 *
 * @return void
 */
function giovanni_breadcrumbs() {
	// Settings
	$separator = '/';
	$home_title = __('עמוד הבית ', 'giovanni');

	// Get the query & post information
	global $post;

	// Do not display on the homepage
	if (!is_front_page()) {
		echo '<ul class="breadcrumbs">';

		// Home page
		echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
		echo '<li class="separator separator-home"> ' . $separator . ' </li>';

		if (is_single()) {
			// Single post
			$category = get_the_category();
			if (!empty($category)) {
				echo '<li class="item-cat"><a class="bread-cat bread-cat-' . $category[0]->term_id . '" href="' . get_category_link($category[0]->term_id) . '" title="' . $category[0]->name . '">' . $category[0]->name . '</a></li>';
				echo '<li class="separator"> ' . $separator . ' </li>';
			}
			echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></li>';
		} elseif (is_page()) {
			// Standard page
			if ($post->post_parent) {
				// If child page, get parents
				$anc = get_post_ancestors($post->ID);
				$anc = array_reverse($anc);
				foreach ($anc as $ancestor) {
					echo '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
					echo '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
				}
				// Current page
				echo '<li class="item-current item-' . $post->ID . '"><span title="' . get_the_title() . '"> ' . get_the_title() . '</span></li>';
			} else {
				// Just display current page if not parents
				echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '"> ' . get_the_title() . '</span></li>';
			}
		} elseif (is_category()) {
			// Category page
			echo '<li class="item-current item-cat"><span class="bread-cat bread-cat-' . get_query_var('cat') . '" title="' . single_cat_title('', false) . '">' . single_cat_title('', false) . '</span></li>';
		} elseif (is_home()) {
			// Blog page
			echo '<li class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></li>';
		}

		echo '</ul>';
	}
}

function show_page_content($content) {
	foreach ($content as $block) :
		switch($block['acf_fc_layout']) {
			case 'paragraph':
				get_template_part('template-parts/page/paragraph', '', ['block' => $block]);
				break;
			case 'full_width_image':
				$bg = $block['image'];
				$bg_mob = $block['image_mob'];

				get_template_part('template-parts/page', 'hero', ['hero_image' => $bg, 'hero_image_mob' => $bg_mob]);
				break;
			case 'image_with_caption':
				get_template_part('template-parts/page/image', 'caption', ['block' => $block]);
				break;
			case 'image':
				get_template_part('template-parts/page/image', '', ['block' => $block]);
				break;
			case 'full_width_video':
				get_template_part('template-parts/page/video', 'full-width', ['block' => $block]);
				break;
			case 'video':
				get_template_part('template-parts/page/video', '', ['block' => $block]);
				break;
			case 'download':
				get_template_part('template-parts/page/download', '', ['block' => $block]);
				break;
			case 'list':
				get_template_part('template-parts/page/list', '', ['block' => $block]);
				break;
			case 'post':
				get_template_part('template-parts/page/post', '', ['block' => $block]);
				break;
			case 'post_full_width':
				get_template_part('template-parts/page/post', 'full-width', ['block' => $block]);
				break;
			case 'exhibition':
				get_template_part('template-parts/page/exhibition', '', ['block' => $block]);
				break;
			case 'slider':
				get_template_part('template-parts/page/slider', '', ['block' => $block]);
				break;
			case 'taxonomies':
				$title = $block['title'];
				$taxonomies = $block['list'];
    		get_template_part('template-parts/sections/taxonomies', '', ['title' => $title, 'taxonomies' => $taxonomies]);
				break;
			case 'products_slider':
				$productArray = $block['list'];
				get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]);
				break;
		}
	endforeach;
}

/**
 * Get Shadow for Main Post
 *
 * @param [type] $shadow
 * @return void
 */
function get_shadow($shadow) {
  if ($shadow) : ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="782" height="542" fill="none" viewBox="0 0 862 622">
      <g filter="url(#a)" transform="translate(-70, -11) scale(1.12)">
        <path fill="#000" fill-opacity=".2" d="M822 40 40 76.41c23 66.749 43.193 223.328 43.193 345.383V582h702.26V460.284c0-88.871.77-298.706 36.547-420.284Z"/>
      </g>
      <defs>
        <filter id="a" width="862" height="622" x="0" y="0" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
          <feFlood flood-opacity="0" result="BackgroundImageFix"/>
          <feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
          <feGaussianBlur result="effect1_foregroundBlur_18_653" stdDeviation="20"/>
        </filter>
      </defs>
    </svg>
  <?php else: ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="782" height="542" fill="none" viewBox="0 0 861 696">
      <g filter="url(#a)" transform="translate(-98, -11) scale(1.12)">
        <path fill="#000" fill-opacity=".2" d="m40 40 781 41.382c-22.97 75.862-43.138 253.819-43.138 392.537V656H76.5V517.666C76.5 416.661 75.731 178.177 40 40Z"/>
      </g>
      <defs>
        <filter id="a" width="861" height="696" x="0" y="0" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
          <feFlood flood-opacity="0" result="BackgroundImageFix"/>
          <feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
          <feGaussianBlur result="effect1_foregroundBlur_18_669" stdDeviation="20"/>
        </filter>
      </defs>
    </svg>
  <?php endif;
}