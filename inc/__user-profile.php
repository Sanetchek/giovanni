<?php

// Add custom fields to user profile
function add_custom_user_profile_fields($user) {
    ?>
    <h3><?php _e('Additional Information', 'giovanni'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="birth_date"><?php _e('Birth Date', 'giovanni'); ?></label></th>
            <td>
                <input type="date" name="birth_date" id="birth_date" value="<?php echo esc_attr(get_user_meta($user->ID, 'birth_date', true)); ?>" class="regular-text" />
                <p class="description"><?php _e('Enter your birth date.', 'giovanni'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="receive_updates"><?php _e('Receive Updates', 'giovanni'); ?></label></th>
            <td>
                <input type="checkbox" name="receive_updates" id="receive_updates" value="1" <?php checked(get_user_meta($user->ID, 'receive_updates', true), 1); ?> />
                <p class="description"><?php _e('Check this if you want to receive updates.', 'giovanni'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="privacy_policy"><?php _e('Privacy Policy Consent', 'giovanni'); ?></label></th>
            <td>
                <input type="checkbox" name="privacy_policy" id="privacy_policy" value="1" <?php checked(get_user_meta($user->ID, 'privacy_policy', true), 1); ?> />
                <p class="description"><?php _e('Check this to agree to the Privacy Policy.', 'giovanni'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'add_custom_user_profile_fields');
add_action('edit_user_profile', 'add_custom_user_profile_fields');

// Save custom fields
function save_custom_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'birth_date', sanitize_text_field($_POST['birth_date']));
    update_user_meta($user_id, 'receive_updates', isset($_POST['receive_updates']) ? 1 : 0);
    update_user_meta($user_id, 'privacy_policy', isset($_POST['privacy_policy']) ? 1 : 0);
}
add_action('personal_options_update', 'save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'save_custom_user_profile_fields');
