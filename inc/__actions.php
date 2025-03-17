<?php

/**
 * Allow SVG
 */
add_action('upload_mimes', function ($mimes) {
	$mimes['svg-xml']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	$mimes['svg']  = 'image/svg+xml';
	return $mimes;
});

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

    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? ''; // Ensure it's always defined

    if (
			!empty($user_agent) &&
			(!preg_match("/$notbot/i", $user_agent) || preg_match("~$bot~i", $user_agent))
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
