<?php
/**
 * Custom Payment Invoice Email
 */

if ( ! isset( $order ) || ! is_a( $order, 'WC_Order' ) ) {
    if ( isset( $email ) && is_a( $email->object, 'WC_Order' ) ) {
        $order = $email->object;
    } else {
        return;
    }
}

$key = $order->get_order_key();
$payment_url = $order->get_checkout_payment_url();

if ( strpos($payment_url, 'key=') !== false ) {
    $payment_url = preg_replace('/key=$/', 'key=' . $key, $payment_url);
} else {
    $payment_url = add_query_arg( 'key', $key, $payment_url );
}

?>

<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; color: #000; background-color: #fff; direction: rtl; text-align: right;">

    <?php include get_stylesheet_directory() . '/template-parts/emails/header.php'; ?>

    <div style="padding: 20px; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000; margin: 20px 0;">
        <h1 style="font-size: 24px; margin-bottom: 10px;text-align:center;"><?= __('השלם את ההזמנה שלך', 'giovanni') ?></h1>
        <p style="font-size: 16px;">
            <?= __('הזמנה מספר', 'giovanni') . ' ' . $order->get_order_number(); ?>
        </p>
    </div>

    <div style="padding:20px; text-align:center;">
        <p style="font-size:18px; margin-bottom:15px;">
            <?= __('כדי להשלים את התשלום, לחץ על הכפתור למטה:', 'giovanni') ?>
        </p>

        <a href="<?= esc_url($payment_url); ?>" 
           style="display:inline-block; padding:15px 30px; background:#000; color:#fff; text-decoration:none; font-size:18px; border-radius:4px;">
           <?= __('לתשלום עכשיו', 'giovanni') ?>
        </a>

        <?php if ( $order->get_customer_note() ) : ?>
            <p style="margin-top:25px; text-align:center;font-size: 18px;">
                <?= nl2br( esc_html( $order->get_customer_note() ) ); ?>
            </p>
        <?php endif; ?>


        <?php if ( isset($email) && !empty($email->additional_content) ) : ?>
            <p style="margin-top:20px; font-size:16px;">
                <?= wp_kses_post( wpautop( $email->additional_content ) ); ?>
            </p>
        <?php endif; ?>

        <p style="margin-top:25px; font-size:16px;">
            <?= __('אם יש לך קופון, ניתן להזין אותו בעמוד התשלום.', 'giovanni') ?>
        </p>
    </div>

    <div style="padding:20px;">
        <?php include get_stylesheet_directory() . '/template-parts/emails/order.php'; ?>
    </div>

</div>
