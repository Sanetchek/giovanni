<?php

function handle_user_registration() {
  check_ajax_referer('register_nonce', 'nonce');

  parse_str($_POST['formData'], $formData);

  // Validate required fields
  if (empty($formData['privacy_policy'])) {
    wp_send_json_error(['message' => __('עליך להסכים למדיניות הפרטיות.', 'giovanni')]);
  }

  if ($formData['email'] !== $formData['confirm_email']) {
    wp_send_json_error(['message' => __('המיילים אינם תואמים.', 'giovanni')]);
  }

  if ($formData['password'] !== $formData['confirm_password']) {
    wp_send_json_error(['message' => __('הסיסמאות אינן תואמות.', 'giovanni')]);
  }

  // Automatically set username as the first part of the email
  $email = sanitize_email($formData['email']);
  if (empty($email)) {
    wp_send_json_error(['message' => __('יש צורך באימייל תקף.', 'giovanni')]);
  }

  $username_raw = explode('@', $email)[0];
  $username = sanitize_user($username_raw, true);

  // Check if username is valid
  if (empty($username) || !validate_username($username)) {
    wp_send_json_error(['message' => __('לא ניתן ליצור שם משתמש חוקי. אנא השתמש באימייל אחר.', 'giovanni')]);
  }

  // Check if username already exists and append a number if necessary
  $base_username = $username;
  $i = 1;
  while (username_exists($username)) {
    $username = $base_username . $i;
    $i++;
  }

  // Register the user
  $user_id = wp_insert_user([
    'user_login'  => $username,
    'first_name'  => sanitize_text_field($formData['first_name']),
    'last_name'   => sanitize_text_field($formData['last_name']),
    'user_email'  => $email,
    'user_pass'   => $formData['password'],
    'role'        => 'customer',
  ]);

  if (is_wp_error($user_id)) {
    wp_send_json_error(['message' => $user_id->get_error_message()]);
  }

  // Save additional meta fields
  if (!empty($formData['birth_date'])) {
    update_user_meta($user_id, 'birth_date', sanitize_text_field($formData['birth_date']));
  }

  update_user_meta($user_id, 'receive_updates', isset($formData['receive_updates']) ? 1 : 0);
  update_user_meta($user_id, 'privacy_policy', 1); // Privacy policy is required.

  // Success message
  wp_send_json_success(['message' => __('ההרשמה הצליחה! נא להיכנס.', 'giovanni')]);
}
add_action('wp_ajax_giovanni_register_user', 'handle_user_registration');
add_action('wp_ajax_nopriv_giovanni_register_user', 'handle_user_registration');

// Helper to validate date
function validate_date($date, $format = 'Y-m-d') {
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}

