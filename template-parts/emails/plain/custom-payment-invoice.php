<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load correct WC Order object
 */
if ( ! isset( $order ) || ! is_a( $order, 'WC_Order' ) ) {
    if ( isset( $email ) && is_a( $email->object, 'WC_Order' ) ) {
        $order = $email->object;
    } else {
        return;
    }
}

$payment_url = $order->get_checkout_payment_url();

?>

<div style="max-width:600px;margin:0 auto;background:#fff;direction:rtl;text-align:right;font-family:Arial;color:#000;">

    <?php 
    wc_get_template(
        'emails/header.php',
        array(),
        '',
        get_stylesheet_directory() . '/template-parts/emails/'
    ); 
    ?>

    <div style="padding:20px;border-bottom:1px solid #000;text-align:center;">
        <h1 style="margin:0;font-size:24px;text-align:center;">
            <?= __('בקשת תשלום להזמנה', 'giovanni') ?> #<?= $order->get_order_number(); ?>
        </h1>
    </div>

    <div style="padding:20px;font-size:16px;line-height:1.6;text-align:center;">
        <?= __('שלום, לצורך השלמת ההזמנה אנא בצע/י תשלום בקישור הבא:', 'giovanni') ?>
        <br><br>

        <a href="<?= esc_url( $payment_url ); ?>" 
           style="display:inline-block;padding:12px 25px;background:#000;color:#fff;text-decoration:none;font-size:18px;">
           <?= __('לתשלום עכשיו', 'giovanni') ?>
        </a>

        <?php if ( isset( $email ) && $email->get_additional_content() ) : ?>
            <div style="margin-top:20px;">
                <?= wpautop( wp_kses_post( $email->get_additional_content() ) ); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php 
    wc_get_template(
        'emails/order.php',
        array( 'order' => $order ),
        '',
        get_stylesheet_directory() . '/template-parts/emails/'
    );
    ?>

    <?php 
    wc_get_template(
        'emails/footer.php',
        array(),
        '',
        get_stylesheet_directory() . '/template-parts/emails/'
    );
    ?>

</div>
