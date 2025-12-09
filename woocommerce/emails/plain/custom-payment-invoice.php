<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$key = $order->get_order_key();
$payment_url = $order->get_checkout_payment_url();

if ( strpos($payment_url, 'key=') !== false ) {
    $payment_url = preg_replace('/key=$/', 'key=' . $key, $payment_url);
} else {
    $payment_url = add_query_arg( 'key', $key, $payment_url );
}

?>

<div style="max-width:600px;margin:0 auto;background:#fff;direction:rtl;text-align:right;font-family:Arial;color:#000;">

    <?php wc_get_template( 'emails/header.php', array(), '', get_stylesheet_directory() . '/woocommerce/' ); ?>

    <div style="padding:20px;border-bottom:1px solid #000;text-align:center;">
        <h1 style="margin:0;font-size:24px;"><?= __('בקשת תשלום להזמנה', 'giovanni') ?> #<?= $order->get_order_number(); ?></h1>
    </div>

    <div style="padding:20px;font-size:16px;line-height:1.6;">
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

    <?php wc_get_template( 'emails/order.php', array( 'order' => $order ), '', get_stylesheet_directory() . '/woocommerce/' ); ?>

    <?php wc_get_template( 'emails/footer.php', array(), '', get_stylesheet_directory() . '/woocommerce/' ); ?>

</div>
