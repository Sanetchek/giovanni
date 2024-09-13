<h2><?php esc_html_e( 'הרשמה', 'giovanni' ); ?></h2>

<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

  <?php do_action( 'woocommerce_register_form_start' ); ?>

  <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
      <label for="reg_username"><?php esc_html_e( 'שם המשתמש', 'giovanni' ); ?>&nbsp;<span class="required">*</span></label>
      <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
    </p>

  <?php endif; ?>

  <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="reg_email"><?php esc_html_e( 'כתובת אימייל', 'giovanni' ); ?>&nbsp;<span class="required">*</span></label>
    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
  </p>

  <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
      <label for="reg_password"><?php esc_html_e( 'סיסמא', 'giovanni' ); ?>&nbsp;<span class="required">*</span></label>
      <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
    </p>

  <?php else : ?>

    <p><?php esc_html_e( 'קישור להגדרה של סיסמה חדשה יישלח לכתובת האימייל שלך.', 'giovanni' ); ?></p>

  <?php endif; ?>

  <?php
    $privacy_policy_url = get_privacy_policy_url();
    $privacy_policy_name = get_the_title(get_option('wp_page_for_privacy_policy'));
  ?>
  <?php if ($privacy_policy_url && $privacy_policy_name) : ?>
    <p><?= __('אנחנו נשתמש בפרטים האישיים כדי להציע לך תמיכה בתהליך באתר זה, לנהל את הגישה לחשבון וכדי לבצע פעולות נוספות כפי שמפורט ב', 'giovanni') ?> <a href="<?= esc_url($privacy_policy_url) ?>" class="woocommerce-privacy-policy-link" target="_blank"><?= esc_html($privacy_policy_name)  ?></a>.</p>
  <?php endif ?>

  <p class="woocommerce-form-row form-row">
    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
    <button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'הרשמה', 'giovanni' ); ?>"><?php esc_html_e( 'הרשמה', 'giovanni' ); ?></button>
  </p>

  <?php do_action( 'woocommerce_register_form_end' ); ?>

</form>