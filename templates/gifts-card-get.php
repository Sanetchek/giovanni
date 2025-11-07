<?php
/**
 * Template Name: Gifts Card Get
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package giovanni
 */

get_header();

$pattern = '^[a-zA-Z0-9.%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,6}$';
?>

<main id="primary" class="site-main giftspage page-container">

  <div class="gift archive-header">
    <?php
      $term = get_queried_object();

      $bg_image = get_field('background_image_default', 'option');
      $bg_image_mob = get_field('background_image_default_mob', 'option');
      $banner_image = get_field('banner');
      $banner_image_mob = get_field('banner_mob');
    ?>

    <?php if ($banner_image) : ?>
      <?php
        $data = [
          'thumb' => [1920, 400],
          'max' => [
            '1024' => [768, 400],
          ],
        ];
        echo liteimage( $banner_image, $data, $banner_image_mob );
      ?>
    <?php else: ?>
      <?php
        $data = [
          'thumb' => [1920, 400],
          'max' => [
            '1024' => [768, 400],
          ],
        ];
        echo liteimage($bg_image, $data, $bg_image_mob);
      ?>
    <?php endif; ?>

    <div class="archive-header-content gift-header">
      <div class="container">
        <div class="gift-header-container">
          <?php the_title('<h1 class="gift-title">', '</h1>'); ?>

          <div class="gift-header-content"><?= get_field('description') ?></div>
        </div>
      </div>
    </div>
  </div>

  <form id="gift_form">
    <div class="gift-certificate gift-certificate__select-amount">
      <div class="container">
        <div class="gift-certificate-container">
          <div class="certificate-item">
            <div class="gift-certificate__step-counter">
              <span>1/2</span>
            </div>
          </div>
          <div class="certificate-item">
            <h2 class="gift-certificate__title">
              <?= __('בחר את הסכום', 'giovanni') ?>
            </h2>
          </div>
          <div class="certificate-item">
            <div class="gift-certificate__title">
              <p class="gift-certificate__text"><?= __('בחר את סכום כרטיס המתנה או הזן אחד לבחירתך.', 'giovanni') ?></p>
            </div>
          </div>
        </div>
        <div class="gift-certificate__select-amount-container">
          <div class="gift-certificate__amounts">
            <div class="wrapper-gift-certificate__amount">

              <label class="gift-amount-item">
                <input type="radio" name="giftAmount" class="gift-radio hidden" value="50">
                <span class="gift-amount button button-hover">₪50</span>
              </label>

              <label class="gift-amount-item">
                <input type="radio" name="giftAmount" class="gift-radio hidden" value="100">
                <span class="gift-amount button button-hover">₪100</span>
              </label>

              <label class="gift-amount-item">
                <input type="radio" name="giftAmount" class="gift-radio hidden" value="150">
                <span class="gift-amount button button-hover">₪150</span>
              </label>

              <label class="gift-amount-item">
                <input type="radio" name="giftAmount" class="gift-radio hidden" value="200">
                <span class="gift-amount button button-hover">₪200</span>
              </label>

              <label class="gift-amount-item">
                <input type="radio" name="giftAmount" class="gift-radio hidden" value="250">
                <span class="gift-amount button button-hover">₪250</span>
              </label>

              <div class="gift-amount-error">
                <input type="number" class="gift-certificate__custom-amount" placeholder="<?= __('סכום אחר', 'giovanni') ?>">
              </div>
              <div class="error-label">
                <p><?= __('המספר חייב להיות בין 50 ל-1000', 'giovanni') ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="wrapper-gift-certificate__next-step">
          <button type="button" id="submit_gift_certificate" class="gift-certificate__button gift-certificate__next-step button button-black button-hover white" disabled><?= __('המשיכו', 'giovanni') ?></button>
        </div>
      </div>
    </div>

    <div class="gift-certificate gift-certificate__form-section disabled">
      <div class="container">
        <div class="gift-certificate__form-container">
          <div class="gift-certificate__step-counter">
            2/2
          </div>
          <h2 class="gift-certificate__title">
            <?= __('הוסף את הנתונים שלך והודעה מותאמת אישית', 'giovanni') ?>
          </h2>
          <p class="gift-certificate__text"><?= __('הזן את פרטי הנמען והוסף הודעה מותאמת אישית אם תרצה.', 'giovanni') ?></p>

          <div class="button-confirm">
            <div class="wrapper-form">
              <div class="form-group">
                <label for="senderName" class="form-control-label"><?= __('השם שלך', 'giovanni') ?></label>
                <input type="string" class="form-control" id="senderName" name="senderName" required="" aria-required="true" value="" maxlength="50">
                <div class="invalid-feedback" id="senderNameFeedback"><?= __('אנא מלא שדה זה', 'giovanni') ?></div>
              </div>

              <div class="form-group">
                <label for="reciverName" class="form-control-label"><?= __('שם הנמען', 'giovanni') ?></label>
                <input type="string" class="form-control" id="reciverName" name="reciverName" required="" aria-required="true" value="" maxlength="50">
                <div class="invalid-feedback" id="reciverNameFeedback"><?= __('אנא מלא שדה זה', 'giovanni') ?></div>
              </div>

              <div class="form-group">
                <label for="reciverEmail" class="form-control-label"><?= __('האימייל של הנמען', 'giovanni') ?></label>
                <input type="email" class="form-control" id="reciverEmail" name="reciverEmail" required="" aria-required="true" value="" maxlength="50" pattern="<?= $pattern ?>">
                <div class="invalid-feedback" id="reciverEmailFeedback"><?= __('אנא מלא שדה זה', 'giovanni') ?></div>
                <div class="emailError"><?= __('כתובות האימייל אינן תואמות.', 'giovanni') ?></div>
              </div>

              <div class="form-group">
                <label for="reciverConfirmEmail" class="form-control-label"><?= __('אשר את האימייל של הנמען', 'giovanni') ?></label>
                <input type="email" class="form-control" id="reciverConfirmEmail" name="reciverConfirmEmail" required="" aria-required="true" value="" maxlength="50" pattern="<?= $pattern ?>">
                <div class="invalid-feedback" id="reciverConfirmEmailFeedback"><?= __('אנא מלא שדה זה', 'giovanni') ?></div>
                <div class="emailError"><?= __('כתובות האימייל אינן תואמות.', 'giovanni') ?></div>
              </div>

              <div class="form-group">
                <label for="message" class="form-control-label"><?= __('הוסף הודעה אישית (אופציונלי)', 'giovanni') ?></label>
                <input type="string" class="form-control" id="message" name="message" value="" maxlength="250">
              </div>
            </div>

            <div class="gift-certificate__card-wrap">
              <div class="gift-certificate__card-clone">
                <div class="icon-logo-white"></div>
                <p><span><?= __('כרטיס מתנה', 'giovanni') ?></span></p>
              </div>
            </div>
          </div>

          <div class="wrapper-button-confirm">
            <div class="button-confirm">
              <button type="submit" class="gift-certificate__button gift-certificate__form-submit button button-black button-hover white"><?= __('הוסף לעגלה', 'giovanni') ?></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

</main><!-- #main -->

<?php get_footer(); ?>