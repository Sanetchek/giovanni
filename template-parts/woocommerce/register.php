<section class="reg-section">
  <?php $group = get_field('reg_form', 'option'); ?>
  <?php if ($group['title']) : ?>
    <h2 class="reg-title"><?= esc_html($group['title']); ?></h2>
  <?php endif; ?>

  <form id="ajax-registration-form" class="reg-form">
    <div class="form-row-name">
      <p>
        <label class="form-label" for="first-name"><?php _e('שם פרטי', 'giovanni'); ?>*</label>
        <input type="text" name="first_name" id="first-name" class="form-input" placeholder="<?php _e('שם', 'giovanni'); ?>" required>
      </p>
      <p>
        <label class="form-label" for="last-name"><?php _e('שם משפחה', 'giovanni'); ?>*</label>
        <input type="text" name="last_name" id="last-name" class="form-input" placeholder="<?php _e('שם משפחה', 'giovanni'); ?>" required>
      </p>
    </div>

    <div class="form-row-register">
      <label class="form-label" for="birth-date"><?php _e('תאריך לידה', 'giovanni'); ?></label>
      <input type="date" id="birth-date" name="birth_date" placeholder="mm/dd/yyyy" />
    </div>

    <div class="form-row-register">
      <label class="form-label" for="email"><?php _e('כתובת אימייל', 'giovanni'); ?>*</label>
      <input type="email" name="email" id="email" class="form-input" placeholder="name.surname@gmail.com" required>
    </div>

    <div class="form-row-register">
      <label class="form-label" for="confirm-email"><?php _e('אשר אימייל', 'giovanni'); ?>*</label>
      <input type="email" name="confirm_email" id="confirm-email" class="form-input" placeholder="name.surname@gmail.com" required>
    </div>

    <div class="form-row-register">
      <label class="form-label" for="main-password"><?php _e('סיסמה', 'giovanni'); ?>*</label>
      <input type="password" name="password" id="main-password" class="form-input" placeholder="<?php _e('הזן לפחות 8 תווים, רישיות אחת ותו מיוחד', 'giovanni'); ?>" required>
    </div>

    <div class="form-row-register">
      <label class="form-label" for="confirm-password"><?php _e('אשר את הסיסמה', 'giovanni'); ?>*</label>
      <input type="password" name="confirm_password" id="confirm-password" class="form-input" placeholder="<?php _e('הזן לפחות 8 תווים, רישיות אחת ותו מיוחד', 'giovanni'); ?>" required>
    </div>

    <div class="form-group">
      <label>
        <input type="checkbox" id="receive-updates" name="receive_updates" class="filter-checkbox" />
        <span class="fake-checkbox"></span>
        <span><?php _e('ברצוני לקבל חדשות ועדכונים על הטרנדים האחרונים, כניסות חדשות ואירועים מיוחדים.', 'giovanni'); ?></span>
      </label>
    </div>

    <div class="form-group">
      <label>
        <input type="checkbox" id="privacy-policy" name="privacy_policy" class="filter-checkbox" required />
        <span class="fake-checkbox"></span>
        <span><?php _e('אני מצהיר שקראתי והבנתי את <a href="/privacy-policy">מדיניות הפרטיות</a> ואני מסכים לעיבוד הנתונים האישיים שלי*', 'giovanni'); ?></span>
      </label>
    </div>

    <div class="form-row-register">
      <button type="submit" id="ajax-registration-form-submit" class="button button-hover white"><?php _e('צור חשבון', 'giovanni'); ?></button>
    </div>

    <div class="form-row-register">
      <p class="recaptcha-text"><?php _e('אתר זה מוגן על ידי reCAPTCHA. השימוש בו כפוף למדיניות הפרטיות ולתנאי השימוש של גוגל.', 'giovanni'); ?></p>
    </div>
  </form>
</section>
