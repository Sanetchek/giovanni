<?php

/**
 * Logs data to a file in the WordPress uploads directory.
 *
 * This function saves a provided data object to a log file within the 'giovanni-logs'
 * directory under the WordPress uploads directory. A subdirectory can be specified
 * to further categorize logs. Each log entry is timestamped and appended to a
 * daily log file. The log directory and file are created with 777 permissions if
 * they do not already exist.
 *
 * @param mixed  $data   The data to be logged, which will be encoded as JSON.
 * @param string $subdir Optional. A subdirectory to append to the log directory.
 */

function log_data($data, $subdir = '') {
  // Get WordPress uploads directory
  $upload_dir = wp_upload_dir();
  $log_dir = trailingslashit($upload_dir['basedir']) . 'giovanni-logs/';

  // If a subdirectory is provided, append it to the log directory
  if (!empty($subdir)) {
    $log_dir .= trailingslashit($subdir);
  }

  // Ensure the directory exists
  if (!file_exists($log_dir)) {
    wp_mkdir_p($log_dir);
    chmod($log_dir, 0777); // Set permissions to 777 for the directory
  }

  // Log file name with current date
  $log_file = $log_dir . 'log-' . date('Y-m-d') . '.log';

  // Create log entry
  $log_entry = '[' . date('Y-m-d H:i:s') . '] ' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;

  // Write log to file
  file_put_contents($log_file, $log_entry, FILE_APPEND);

  // Ensure the log file has 777 permissions
  chmod($log_file, 0777);
}

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
 * Generate picture
 *
 * @param [type] $image_id
 * @param [type] $is_last
 * @param [type] $class
 * @return void
 */
function generate_picture_element($image_id, $is_last, $class) {
	// Get the image URLs for different sizes
	$thumb = '812-812';
	$large_image = wp_get_attachment_image_src($image_id, $thumb);

  $thumb = $is_last ? '1440-500' : $thumb;
	$medium_image = wp_get_attachment_image_src($image_id, $thumb);

	// Check if images are available
	if ($large_image && $medium_image) {
		$output = '<picture aria-hidden="true">';
		$output .= '<source media="(min-width:1280px)" srcset="' . esc_url($large_image[0]) . '">';
		$output .= '<img class="lazyloaded" data-src="' . esc_url($medium_image[0]) . '" alt="' . esc_attr(get_the_title($image_id)) . '" src="' . esc_url($medium_image[0]) . '" class="'.$class.'" loading="lazy">';
		$output .= '</picture>';

		return $output;
	}

	return '';
}

/**
 * Generates a responsive <picture> element with multiple <source> elements for different screen sizes.
 *
 * @param int    $image_id ID of the image attachment.
 * @param string $thumb    Size of the thumbnail to retrieve for the default image.
 * @param string $class    CSS class to apply to the <img> element.
 * @param array  $min      An associative array of min-width media queries and corresponding image sizes.
 *                         Example: ['768' => 'medium', '1024' => 'large']
 * @param array  $max      An associative array of max-width media queries and corresponding image sizes.
 *                         Example: ['767' => 'thumbnail', '1023' => 'medium']
 *
 * @return string The generated HTML markup for the <picture> element or an empty string if the image is not found.
 */
function generate_picture($image_id, $thumb = 'full', $class = '', $min = [], $max = []) {
	$image = wp_get_attachment_image_src($image_id, $thumb);

	// Check if the image source is available
	if ($image) {
		$output = '<picture aria-hidden="true">';

		// Generate <source> elements for min-width queries
		if ($min) {
			foreach ($min as $width => $size) {
				$source_image = wp_get_attachment_image_src($image_id, $size);
				if ($source_image) {
					$output .= '<source media="(min-width:' . esc_attr($width) . 'px)" srcset="' . esc_url($source_image[0]) . '">';
				}
			}
		}

		// Generate <source> elements for max-width queries
		if ($max) {
			foreach ($max as $width => $size) {
				$source_image = wp_get_attachment_image_src($image_id, $size);
				if ($source_image) {
					$output .= '<source media="(max-width:' . esc_attr($width) . 'px)" srcset="' . esc_url($source_image[0]) . '">';
				}
			}
		}

		// Add the <img> element
		$output .= '<img class="lazyloaded ' . esc_attr($class) . '" data-src="' . esc_url($image[0]) . '" alt="' . esc_attr(get_the_title($image_id)) . '" src="' . esc_url($image[0]) . '" loading="lazy">';
		$output .= '</picture>';

		return $output;
	}

	return '';
}

/**
 * Generates a responsive background image using the <picture> element.
 *
 * By default, the image is shown in its full size on desktop and in a smaller
 * size on mobile devices. If the $bg_mob parameter is provided, it will be used

 * instead of the $bg parameter for mobile devices.
 *
 * @param int $bg The ID of the background image.
 * @param int $bg_mob The ID of the background image for mobile devices.
 */
function generate_picture_source($bg, $bg_mob = null, $attrs = array()) {
  $resolve_id = function($val) {
    if (!$val) return 0;
    if (is_numeric($val)) return (int)$val;                     
    if (is_array($val)) {
      if (!empty($val['ID'])) return (int)$val['ID'];           
      if (!empty($val['id'])) return (int)$val['id'];
      if (!empty($val['url'])) return attachment_url_to_postid($val['url']);
    }
    if (is_string($val)) return attachment_url_to_postid($val); 
    return 0;
  };

  $bg_id     = $resolve_id($bg);
  $bg_mob_id = $resolve_id($bg_mob ?: $bg);

  $desktop = $bg_id ? wp_get_attachment_image_src($bg_id, '1920-865') : false;
  if (!$desktop && is_string($bg) && filter_var($bg, FILTER_VALIDATE_URL)) {
    $desktop = [$bg, 1920, 865];
  }
  if (!$desktop) {
    $desktop = $bg_id ? wp_get_attachment_image_src($bg_id, 'full') : ['', 1920, 865];
  }

  $mobile = $bg_mob_id ? wp_get_attachment_image_src($bg_mob_id, '768-865') : false;
  if (!$mobile && is_string($bg_mob) && filter_var($bg_mob, FILTER_VALIDATE_URL)) {
    $mobile = [$bg_mob, 768, 865];
  }
  if (!$mobile) {
    $mobile = $bg_mob_id ? wp_get_attachment_image_src($bg_mob_id, 'full') : $desktop;
  }

  $alt = isset($attrs['alt']) ? $attrs['alt'] : 'hero';

  $class = 'is-hero';
  if (!empty($attrs['class'])) $class = trim($attrs['class'].' is-hero');

  ?>
  <picture>
    <source media="(max-width:1024px)"
            srcset="<?php echo esc_url($mobile[0]); ?>"
            sizes="(max-width: 1024px) 100vw">
    <img
      class="<?php echo esc_attr($class); ?>"
      src="<?php echo esc_url($desktop[0]); ?>"
      width="<?php echo (int)($desktop[1] ?: 1920); ?>"
      height="<?php echo (int)($desktop[2] ?: 865); ?>"
      alt="<?php echo esc_attr($alt); ?>"
      loading="eager"
      fetchpriority="high">
  </picture>
  <?php
}


/**
 * Generates a responsive background image using the <picture> element.
 *
 * By default, the image is shown in its full size on desktop and in a smaller
 * size on mobile devices. If the $bg_mob parameter is provided, it will be used

 * instead of the $bg parameter for mobile devices.
 *
 * @param int $bg The ID of the background image.
 * @param int $bg_mob The ID of the background image for mobile devices.
 */
function generate_archive_picture_source($bg, $bg_mob = null) {
	// Default mobile image to desktop image if not provided
	$bg_mob = $bg_mob ? $bg_mob : $bg;

	// Get the URL of the desktop image
	$desktop_image_url = wp_get_attachment_image_url($bg, '1920-400');

	// Get the URL of the mobile image
	$mob_image_url = wp_get_attachment_image_url($bg_mob, '768-400'); ?>

	<picture>
		<source media="(max-width:1024px)" srcset="<?= esc_url($mob_image_url) ?>" sizes="(max-width: 1024px) 100vw">
		<img width="1920" height="400" src="<?= esc_url($desktop_image_url) ?>" alt="hero" loading="lazy">
	</picture>
	<?php
}

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
<img src="https://img.youtube.com/vi/<?php $video ?>/default.jpg" class="br-40" alt="youtube" loading="lazy">
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
  <span class="site-logo-desktop">
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
			endif;
		?>
  </span>
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
<div class="burger-menu <?= $is_active ?>">
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
				if ($anc) {
					foreach ($anc as $ancestor) {
						echo '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
						echo '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
					}
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

/**
 * Get content template for Page and Post types
 *
 * @param array $content
 * @return void
 */
function show_page_content($content) {
	if (empty($content)) {
		return;
	}

	foreach ($content as $block) :
		switch ($block['acf_fc_layout']) {
			case 'paragraph':
				get_template_part('template-parts/page/paragraph', '', ['block' => $block]);
				break;
			case 'full_width_image':
				$bg = $block['image'];
				$bg_mob = $block['image_mob'];
				generate_picture_source($bg, $bg_mob);
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
				$title = esc_html($block['title']); // Escape title
				$taxonomies = $block['list'];
				get_template_part('template-parts/sections/taxonomies', '', ['title' => $title, 'taxonomies' => $taxonomies]);
				break;
			case 'products':
				$productArray = $block['list'];
				get_template_part('template-parts/page/products', '', ['productArray' => $productArray]);
				break;
			case 'products_slider':
				$productArray = $block['list'];
				get_template_part('template-parts/product', 'slider', ['productArray' => $productArray]);
				break;
			case 'page_list':
				get_template_part('template-parts/page/page', 'list', ['block' => $block]);
				break;
		}
	endforeach;
}


/**
 * Get content template for Custom Service page Template
 *
 * @param [type] $content
 * @return void
 */
function show_customer_service_content($content) {
	if (empty($content)) {
		return;
	}

	foreach ($content as $block) :
		switch ($block['acf_fc_layout']) {
			case 'tabs':
				$tabs = $block['tab_list'];
				get_template_part('template-parts/customer-service/tabs', '', ['tabs' => $tabs]);
				break;
			case 'text_content':
				echo '<div class="customer-content">';
				echo $block['content'];
				echo '</div>';
				break;
			case 'image':
				show_image($block['image'], 'full', ['class' => 'customer-image']);
				break;
			case 'shortcode':
				echo '<div class="customer-shortcode">';
				echo do_shortcode( sanitize_text_field($block['shortcode']) );
				echo '</div>';
				break;
			case 'table':
				$table = $block['table'];
				get_template_part('template-parts/customer-service/table', '', ['table' => $table]);
				break;
		}
	endforeach;
}


/**
 * Get the parent page ID of the current page or a specified page.
 *
 * If no specific page ID is provided, it retrieves the ID of the current post in the loop.
 *
 * @param int|null $current_page The ID of the current page. If null, the function retrieves the current post's ID.
 * @return int The parent page ID or the current page ID if no parent exists.
 */
function get_parent_page_id($current_page = null) {
	// If no current page is provided, get the ID of the current post
	$current_page = $current_page ?? get_the_ID();

	// Get the parent page ID
	$parent_id = wp_get_post_parent_id($current_page);

	// Return the parent ID if it exists, otherwise return the current page ID
	return $parent_id > 0 ? $parent_id : $current_page;
}

/**
 * Get Array of Current Page Children
 *
 * @param int|null $current_page_id The ID of the current page. Defaults to the ID of the current post if null.
 * @return array Array of child pages.
 */
function get_current_page_children_ids($current_page_id = null) {
	// Default to the current page ID if not provided
	if (empty($current_page_id)) {
		$current_page_id = get_the_ID();
	}

	// Query to get child pages of the current page
	$args = array(
		'post_type'      => 'page',
		'post_parent'    => $current_page_id,
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'order'          => 'ASC',
		'orderby'        => 'date',
	);

	// Return the child pages as an array of posts
	return get_posts($args);
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
				<path fill="#000" fill-opacity=".2"
					d="M822 40 40 76.41c23 66.749 43.193 223.328 43.193 345.383V582h702.26V460.284c0-88.871.77-298.706 36.547-420.284Z" />
			</g>
			<defs>
				<filter id="a" width="862" height="622" x="0" y="0" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
					<feFlood flood-opacity="0" result="BackgroundImageFix" />
					<feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
					<feGaussianBlur result="effect1_foregroundBlur_18_653" stdDeviation="20" />
				</filter>
			</defs>
		</svg>
		<?php else: ?>
		<svg xmlns="http://www.w3.org/2000/svg" width="782" height="542" fill="none" viewBox="0 0 861 696">
			<g filter="url(#a)" transform="translate(-98, -11) scale(1.12)">
				<path fill="#000" fill-opacity=".2"
					d="m40 40 781 41.382c-22.97 75.862-43.138 253.819-43.138 392.537V656H76.5V517.666C76.5 416.661 75.731 178.177 40 40Z" />
			</g>
			<defs>
				<filter id="a" width="861" height="696" x="0" y="0" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
					<feFlood flood-opacity="0" result="BackgroundImageFix" />
					<feBlend in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
					<feGaussianBlur result="effect1_foregroundBlur_18_669" stdDeviation="20" />
				</filter>
			</defs>
		</svg>
	<?php endif;
}

/**
 * Fetches the content of a product modal and renders it
 *
 * @param int $page_id The ID of the page to fetch content from
 * @return void
 */
function get_product_modal($page_id) {
  // Get the page ID from the request
  if (!$page_id) {
    return;
  }

  // Fetch the post content and title
  $post = get_post($page_id);
  if (!$post) {
    return;
  }

  $content = get_field('content', $page_id);

  echo '<h2>' . $post->post_title . '</h2>';
  show_customer_service_content($content);
}

/**
 * Outputs the WhatsApp link and icon
 *
 * Outputs the WhatsApp link with the icon. The link is taken from the
 * "whatsapp" field in the ACF settings.
 *
 * @return void
 */
function whatsapp() {
	$whatsapp = get_field('whatsapp', 'option');
	if (empty($whatsapp)) return;

  echo '<a href="'. $whatsapp .'" class="whatsApp" target="_blank">
	 	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 175.216 175.552" style="width:50px;height:50px" ><defs><linearGradient id="b" x1="85.915" x2="86.535" y1="32.567" y2="137.092" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#57d163"/><stop offset="1" stop-color="#23b33a"/></linearGradient><filter id="a" width="1.115" height="1.114" x="-.057" y="-.057" color-interpolation-filters="sRGB"><feGaussianBlur stdDeviation="3.531"/></filter></defs><path fill="#b3b3b3" d="m54.532 138.45 2.235 1.324c9.387 5.571 20.15 8.518 31.126 8.523h.023c33.707 0 61.139-27.426 61.153-61.135.006-16.335-6.349-31.696-17.895-43.251A60.75 60.75 0 0 0 87.94 25.983c-33.733 0-61.166 27.423-61.178 61.13a60.98 60.98 0 0 0 9.349 32.535l1.455 2.312-6.179 22.558zm-40.811 23.544L24.16 123.88c-6.438-11.154-9.825-23.808-9.821-36.772.017-40.556 33.021-73.55 73.578-73.55 19.681.01 38.154 7.669 52.047 21.572s21.537 32.383 21.53 52.037c-.018 40.553-33.027 73.553-73.578 73.553h-.032c-12.313-.005-24.412-3.094-35.159-8.954zm0 0" filter="url(#a)"/><path fill="#fff" d="m12.966 161.238 10.439-38.114a73.42 73.42 0 0 1-9.821-36.772c.017-40.556 33.021-73.55 73.578-73.55 19.681.01 38.154 7.669 52.047 21.572s21.537 32.383 21.53 52.037c-.018 40.553-33.027 73.553-73.578 73.553h-.032c-12.313-.005-24.412-3.094-35.159-8.954z"/><path fill="url(#linearGradient1780)" d="M87.184 25.227c-33.733 0-61.166 27.423-61.178 61.13a60.98 60.98 0 0 0 9.349 32.535l1.455 2.312-6.179 22.559 23.146-6.069 2.235 1.324c9.387 5.571 20.15 8.518 31.126 8.524h.023c33.707 0 61.14-27.426 61.153-61.135a60.75 60.75 0 0 0-17.895-43.251 60.75 60.75 0 0 0-43.235-17.929z"/><path fill="url(#b)" d="M87.184 25.227c-33.733 0-61.166 27.423-61.178 61.13a60.98 60.98 0 0 0 9.349 32.535l1.455 2.313-6.179 22.558 23.146-6.069 2.235 1.324c9.387 5.571 20.15 8.517 31.126 8.523h.023c33.707 0 61.14-27.426 61.153-61.135a60.75 60.75 0 0 0-17.895-43.251 60.75 60.75 0 0 0-43.235-17.928z"/><path fill="#fff" fill-rule="evenodd" d="M68.772 55.603c-1.378-3.061-2.828-3.123-4.137-3.176l-3.524-.043c-1.226 0-3.218.46-4.902 2.3s-6.435 6.287-6.435 15.332 6.588 17.785 7.506 19.013 12.718 20.381 31.405 27.75c15.529 6.124 18.689 4.906 22.061 4.6s10.877-4.447 12.408-8.74 1.532-7.971 1.073-8.74-1.685-1.226-3.525-2.146-10.877-5.367-12.562-5.981-2.91-.919-4.137.921-4.746 5.979-5.819 7.206-2.144 1.381-3.984.462-7.76-2.861-14.784-9.124c-5.465-4.873-9.154-10.891-10.228-12.73s-.114-2.835.808-3.751c.825-.824 1.838-2.147 2.759-3.22s1.224-1.84 1.836-3.065.307-2.301-.153-3.22-4.032-10.011-5.666-13.647"/></svg>
	 </a>';
}
add_action( 'wp_footer', 'whatsapp' );


add_filter('script_loader_tag', function ($tag, $handle, $src) {
  if (is_admin() || wp_doing_ajax()) return $tag;
  $exclude = [
    'jquery','jquery-core','jquery-migrate',
    'woocommerce','wc-add-to-cart','wc-cart-fragments',
    'elementor-frontend','elementor-waypoints'
  ];
  if (in_array($handle, $exclude, true)) return $tag;
  if (strpos($tag, ' type="module"') !== false) return $tag;
  if (strpos($tag, ' defer') === false && strpos($tag, ' async') === false) {
    $tag = str_replace('<script ', '<script defer ', $tag);
  }
  return $tag;
}, 10, 3);

add_action('wp_enqueue_scripts', function () {
  if (wp_script_is('jquery-core', 'registered')) {
    wp_deregister_script('jquery-core');
    wp_register_script('jquery-core', includes_url('/js/jquery/jquery.min.js'), [], null, true);
  }
  if (wp_script_is('jquery-migrate', 'registered')) {
    wp_deregister_script('jquery-migrate');
    wp_register_script('jquery-migrate', includes_url('/js/jquery/jquery-migrate.min.js'), ['jquery-core'], null, true);
  }
  if (wp_script_is('jquery', 'registered')) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', false, ['jquery-core','jquery-migrate'], null, true);
  }
  wp_enqueue_script('jquery');
}, 100);


add_filter('script_loader_tag', function ($tag, $handle, $src) {
  if (is_admin() || wp_doing_ajax()) return $tag;
  $delay = false;
  if (strpos($src, 'pluro.ai') !== false) $delay = true;
  if (strpos($src, 'unitoolbar') !== false) $delay = true;
  if (strpos($src, 'google.com') !== false && strpos($src, 'api') !== false) $delay = true;
  if ($delay) {
    $tag = preg_replace('#\s+src=([\'"]).*?\1#', '', $tag);
    $tag = str_replace('<script ', '<script data-delayed-src="'.esc_attr($src).'" ', $tag);
  }
  return $tag;
}, 10, 3);

add_action('wp_footer', function () {
?>
<script>
(function(){
  var started=false;
  function loadDelayed(){
    if(started) return; started=true;
    var nodes=document.querySelectorAll('script[data-delayed-src]');
    nodes.forEach(function(n){
      var s=document.createElement('script');
      s.src=n.getAttribute('data-delayed-src');
      s.async=true; s.defer=true;
      document.head.appendChild(s);
      n.parentNode.removeChild(n);
    });
  }
  ['mousemove','keydown','touchstart','scroll'].forEach(function(e){
    window.addEventListener(e, loadDelayed, {once:true, passive:true});
  });
  setTimeout(loadDelayed, 4000);
})();
</script>
<?php
}, 1);
