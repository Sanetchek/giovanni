<?php

/**
 * Simple Likes
 */
add_action('wp_ajax_process_simple_like', 'processSimpleLike');
add_action('wp_ajax_nopriv_process_simple_like', 'processSimpleLike');

function processSimpleLike()
{
  // Security
  $nonce = isset($_REQUEST['nonce']) ? sanitize_text_field($_REQUEST['nonce']) : 0;
  if (!wp_verify_nonce($nonce, 'simple-likes-nonce')) {
    exit(__('Not permitted', 'simple-likes'));
  }
  // Custom field slug
  $field_count = '_likes_count';
  $field_user_liked = '_user_liked';
  $field_user_ip = '_user_ip';

  // Base variables
  $post_id = $_POST["post"];
  $liked = '<svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.53553 0.5C2.30677 0.5 0.5 2.30677 0.5 4.53553C0.5 5.60582 0.92517 6.63228 1.68198 7.38909L7.64645 13.3536C7.84171 13.5488 8.15829 13.5488 8.35355 13.3536L14.318 7.38909C15.0748 6.63228 15.5 5.60582 15.5 4.53553C15.5 2.30677 13.6932 0.5 11.4645 0.5C10.3942 0.5 9.36772 0.925171 8.61091 1.68198L8 2.29289L7.38909 1.68198C6.63228 0.925171 5.60582 0.5 4.53553 0.5Z" fill="#000000"/></svg>';
  $unliked = '<svg width="16" height="14" role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve"><path id="heart" d="M64 127.5C17.1 79.9 3.9 62.3 1 44.4c-3.5-22 12.2-43.9 36.7-43.9 10.5 0 20 4.2 26.4 11.2 6.3-7 15.9-11.2 26.4-11.2 24.3 0 40.2 21.8 36.7 43.9C124.2 62 111.9 78.9 64 127.5zM37.6 13.4c-9.9 0-18.2 5.2-22.3 13.8C5 49.5 28.4 72 64 109.2c35.7-37.3 59-59.8 48.6-82 -4.1-8.7-12.4-13.8-22.3-13.8 -15.9 0-22.7 13-26.4 19.2C60.6 26.8 54.4 13.4 37.6 13.4z"/>&#9829;</svg>';
  $heart = $unliked;

  /**
   * Utility to retrieve IP address
   * @since    0.5
   */
  function sl_get_ip()
  {
    if (isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = ( isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }
    $ip = filter_var($ip, FILTER_VALIDATE_IP);

    return ( $ip === false ) ? '0.0.0.0' : $ip;
  } // sl_get_ip()

  if ($post_id != '') {
    $count = get_field($field_count, $post_id); // like count
    $count = intval($count);
    $count = ( isset($count) && is_numeric($count) ) ? $count : 0;

    if (is_user_logged_in()) { // user is logged in
      $user_id = get_current_user_id();
      $user_info = get_userdata($user_id);
      $user_liked = get_field($field_user_liked, $post_id);

      if (empty($user_liked)) {
        $list = $user_info->user_nicename . ', ';
        $like_count = ++$count;
        $heart = $liked;
      } else {
        $list   = explode(', ', $user_liked);
        $uid_key = array_search($user_info->user_nicename, $list); // search for username

        if ($uid_key !== false) {
          unset($list[$uid_key]);
          $like_count = --$count;
          $heart = $unliked;
        } else {
          $list[] = $user_info->user_nicename;
          $like_count = ++$count;
          $heart = $liked;
        }
        $list = implode(', ', $list);
      }

      // Update Post
      update_field($field_user_liked, $list, $post_id);
    } else { // user is anonymous
      $user_ip = sl_get_ip();
      $user_liked = get_field($field_user_ip, $post_id);

      if (empty($user_liked)) {
        $list = $user_ip . ', ';
        $like_count = ++$count;
        $heart = $liked;
      } else {
        $list   = explode(', ', $user_liked);
        $uid_key = array_search($user_ip, $list);

        if ($uid_key !== false) {
          unset($list[$uid_key]);
          $like_count = --$count;
          $heart = $unliked;
        } else {
          $list[] = $user_ip;
          $like_count = ++$count;
          $heart = $liked;
        }
        $list = implode(', ', $list);
      }

      // Update Post
      update_field($field_user_ip, $list, $post_id);
    }

    update_field($field_count, $like_count, $post_id);
    $likes = query_user_liked_posts();

    wp_send_json(['count' => $like_count, 'icon' => $heart, 'total_likes' => $likes['count']]);
  }

  wp_die();
}

/**
 * Show Likes Count
 *
 * @param string $field_count
 * @param string $field_user_liked
 * @param string $field_user_ip
 * @return string
 */
function simpleLikes($post_id, $field_count = '_likes_count', $field_user_liked = '_user_liked', $field_user_ip = '_user_ip')
{
	$number = get_field($field_count);
	$post_id = !$post_id ? get_the_ID() : $post_id;
	$nonce = wp_create_nonce('simple-likes-nonce'); // Security
	$liked = '<svg width="16" height="14" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg"><path d="M4.53553 0.5C2.30677 0.5 0.5 2.30677 0.5 4.53553C0.5 5.60582 0.92517 6.63228 1.68198 7.38909L7.64645 13.3536C7.84171 13.5488 8.15829 13.5488 8.35355 13.3536L14.318 7.38909C15.0748 6.63228 15.5 5.60582 15.5 4.53553C15.5 2.30677 13.6932 0.5 11.4645 0.5C10.3942 0.5 9.36772 0.925171 8.61091 1.68198L8 2.29289L7.38909 1.68198C6.63228 0.925171 5.60582 0.5 4.53553 0.5Z" fill="#000000"/></svg>';

  $unliked = '<svg width="16" height="14" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><path d="M64 127.5C17.1 79.9 3.9 62.3 1 44.4c-3.5-22 12.2-43.9 36.7-43.9 10.5 0 20 4.2 26.4 11.2 6.3-7 15.9-11.2 26.4-11.2 24.3 0 40.2 21.8 36.7 43.9C124.2 62 111.9 78.9 64 127.5zM37.6 13.4c-9.9 0-18.2 5.2-22.3 13.8C5 49.5 28.4 72 64 109.2c35.7-37.3 59-59.8 48.6-82 -4.1-8.7-12.4-13.8-22.3-13.8 -15.9 0-22.7 13-26.4 19.2C60.6 26.8 54.4 13.4 37.6 13.4z" fill="#FF0000"/></svg>';

	$heart = $unliked;

	$precision = 2;
	if ($number >= 1000 && $number < 1000000) {
		$formatted = number_format($number/1000, $precision).'K';
	} else if ($number >= 1000000 && $number < 1000000000) {
		$formatted = number_format($number/1000000, $precision).'M';
	} else if ($number >= 1000000000) {
		$formatted = number_format($number/1000000000, $precision).'B';
	} else {
		$formatted = $number; // Number is less than 1000
	}
	if (is_string($formatted)) {
    $formatted = str_replace('.00', '', $formatted);
  }

	if (is_user_logged_in()) { // user is logged in
		$user_id = get_current_user_id();
		$user_info = get_userdata($user_id);
		$user_liked = get_field($field_user_liked, $post_id);

		if (empty($user_liked)) {
			$heart = $unliked;
		} else {
			$list   = explode(', ', $user_liked, -1);
      $list = array_combine(range(1, count($list)), $list);
			$uid_key = array_search($user_info->user_nicename, $list); // search for username

			if ($uid_key) {
				$heart = $liked;
			} else {
				$heart = $unliked;
			}
		}
	} else { // user is anonymous
		if (isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = ( isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		}
		$ip = filter_var($ip, FILTER_VALIDATE_IP);

		if ($ip === false) {
			$ip = '0.0.0.0';
		}

		$user_ip = $ip;
		$user_liked = get_field($field_user_ip, $post_id);

		if (empty($user_liked)) {
			$heart = $unliked;
		} else {
			$list   = explode(', ', $user_liked);
			$uid_key = array_search($user_ip, $list);

			if (!$uid_key) {
				$heart = $liked;
			} else {
				$heart = $unliked;
			}
		}
	}


	$output = '<span class="post-review__like" data-post-id="' . $post_id . '" data-nonce="' . $nonce . '">
      <span class="post-review__like-icon">' . $heart . '</span>
    </span>';

	return $output;
}