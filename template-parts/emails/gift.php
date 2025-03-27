<?php
/**
 * Gift Card Email Template for WooCommerce - Hebrew Version
 *
 * This template can be used with WooCommerce Gift Cards or similar plugins.
 * Replace the placeholder variables with the actual variables from your gift card plugin.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$gift_card_data = isset($args['data']) ? $args['data'] : [];

// Get recipient data from your gift card plugin
// These are example variables - adjust based on your plugin's data structure
$recipient_name = isset($gift_card_data['recipient_name']) ? $gift_card_data['recipient_name'] : '';
$sender_name = isset($gift_card_data['sender_name']) ? $gift_card_data['sender_name'] : '';
$personal_message = isset($gift_card_data['message']) ? $gift_card_data['message'] : '';
$voucher_code = isset($gift_card_data['code_list']) ? $gift_card_data['code_list'] : '';
$gift_card_amount = isset($gift_card_data['amount']) ? wc_price($gift_card_data['amount']) : '';

// Get gift card image URL - adjust based on your setup
$gift_card_image = 'https://giovanniraspini-shop.co.il/wp-content/uploads/2024/12/GR_Gift-Card.png';
?>

<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; direction: rtl;">
    <!-- Header -->
    <?php get_template_part('template-parts/emails/header') ?>

    <!-- Title -->
    <div style="padding: 30px 20px 0; text-align: center;">
        <h1 style="font-size: 28px; color: #000000; margin: 0; text-transform: uppercase; letter-spacing: 2px;"><?= __('מתנה מיוחדת עבורך', 'giovanni') ?></h1>
    </div>

    <!-- Gift Card Image -->
    <div style="text-align: center; padding: 30px 20px;">
        <img src="<?php echo esc_url($gift_card_image); ?>" alt="כרטיס מתנה Giovanni Raspini ISRL" style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);" loading="lazy">
    </div>

    <!-- Recipient Information -->
    <div style="padding: 0 40px 30px; text-align: center;">
        <p style="font-size: 18px; color: #000000; margin-bottom: 30px; line-height: 1.5;">
            <?= __('שלום', 'giovanni') ?> <span style="font-weight: bold;"><?php echo esc_html($recipient_name); ?></span>,
        </p>

        <p style="font-size: 16px; color: #000000; margin-bottom: 20px; line-height: 1.5;">
            <span style="font-weight: bold;"><?php echo esc_html($sender_name); ?></span> <?= __('שלח/ה לך כרטיס מתנה של Giovanni Raspini ISRL', 'giovanni') ?><?php echo !empty($gift_card_amount) ? __(' בשווי ', 'giovanni') . $gift_card_amount : ''; ?>.
        </p>

        <?php if (!empty($personal_message)) : ?>
        <!-- Personal Message -->
        <div style="padding: 20px; border: 1px solid #d0d0d0; border-radius: 5px; margin-bottom: 30px; background-color: #f9f9f9; text-align: right;">
            <p style="font-size: 16px; color: #000000; margin: 0; font-style: italic; line-height: 1.6;">
                "<?php echo esc_html($personal_message); ?>"
            </p>
        </div>
        <?php endif; ?>

        <!-- Voucher Code Section -->
        <div style="padding: 20px; border: 2px dashed #000000; text-align: center; margin-bottom: 30px;">
            <p style="font-size: 14px; color: #000000; margin: 0 0 10px; text-transform: uppercase; letter-spacing: 1px;"><?= __('קוד כרטיס המתנה שלך', 'giovanni') ?></p>

            <?php if (is_array($voucher_code) && !empty($voucher_code)) : ?>
              <?php foreach ($voucher_code as $value) : ?>
                <p style="font-size: 24px; color: #000000; margin: 0; font-weight: bold; letter-spacing: 2px;"><?php echo esc_html($value); ?></p>
              <?php endforeach ?>
            <?php endif ?>
        </div>

        <!-- Redemption Instructions -->
        <div style="margin-bottom: 30px; text-align: right;">
            <h3 style="font-size: 18px; color: #000000; margin-bottom: 15px;"><?= __('כיצד להשתמש בכרטיס המתנה שלך:', 'giovanni') ?></h3>
            <ol style="text-align: right; color: #000000; padding-right: 20px; line-height: 1.5; margin-right: 0;">
                <li><?= __('בקר/י באתר', 'giovanni') ?> <a href="<?php echo esc_url(home_url()); ?>" style="color: #000000; text-decoration: underline;"><?php echo esc_url(home_url('/')); ?></a></li>
                <li><?= __('בחר/י את הפריטים האהובים עליך של Giovanni Raspini ISRL', 'giovanni') ?></li>
                <li><?= __('הזן/י את קוד כרטיס המתנה שלך בקופה', 'giovanni') ?></li>
                <li><?= __('תהנה/י מתכשיטי Giovanni Raspini ISRL החדשים שלך!', 'giovanni') ?></li>
            </ol>
        </div>

        <!-- CTA Button -->
        <div style="margin-bottom: 40px;">
            <a href="<?php echo esc_url(home_url()); ?>" style="display: inline-block; padding: 15px 30px; background-color: #000000; color: #ffffff; text-decoration: none; font-weight: bold; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;"><?= __('לקנייה עכשיו', 'giovanni') ?></a>
        </div>
    </div>

    <!-- Footer -->
    <?php get_template_part('template-parts/emails/footer') ?>
</div>
