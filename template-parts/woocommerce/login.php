<h2><?php esc_html_e( 'התחברות', 'giovanni' ); ?></h2>
<p class="woo-login-message"><?= __('אם יש לך חשבון, היכנס עם הדוא"ל והסיסמה שלך כדי לראות את כל ההזמנות שלך ', 'giovanni') ?></p>

<form class="woocommerce-form woocommerce-form-login login" method="post">

  <?php do_action( 'woocommerce_login_form_start' ); ?>

  <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="username"><?php esc_html_e( 'שם משתמש או כתובת אימייל', 'giovanni' ); ?>&nbsp;<span class="required">*</span></label>
    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
  </p>
  <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="password"><?php esc_html_e( 'סיסמא', 'giovanni' ); ?>&nbsp;<span class="required">*</span></label>
    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
  </p>

  <?php do_action( 'woocommerce_login_form' ); ?>

  <p class="form-row woo-login-submit">
    <span class="woo-login-wrap">
      <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'תזכור אותי', 'giovanni' ); ?></span>
      </label>

      <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
      <button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'התחברות', 'giovanni' ); ?>"><?php esc_html_e( 'התחברות', 'giovanni' ); ?></button>
    </span>

    <span class="woocommerce-LostPassword lost_password">
      <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'איבדת את הסיסמה שלך?', 'giovanni' ); ?></a>
    </span>
  </p>

  <?php do_action( 'woocommerce_login_form_end' ); ?>

</form>