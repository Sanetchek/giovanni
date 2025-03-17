<?php
/**
 * Customer new order email - Hebrew Version
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-order.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; color: #000000; background-color: #ffffff; direction: rtl; text-align: right;">
    <!-- Logo -->
    <div style="text-align: center; padding: 20px 0;">
        <img src="https://giovanniraspini-shop.co.il/wp-content/uploads/2024/06/logo.png" alt="Giovanni Raspini" style="max-width: 250px; height: auto;">
    </div>

    <!-- Thank You Message -->
    <div style="padding: 20px; text-align: center; border-top: 1px solid #000000; border-bottom: 1px solid #000000; margin: 20px 0;">
        <h1 style="font-size: 24px; color: #000000; margin-bottom: 10px;">תודה על הזמנתך!</h1>
        <p style="font-size: 16px; color: #000000;">
           הזמנה חדשה מספר <?php echo esc_html( $order->get_order_number() ); ?># מאתר Giovanni Raspini
        </p>
        <p style="font-size: 16px; color: #000000;">
           הזמנה חדשה מספר {order_number}# מאתר Giovanni Raspini
        </p>
    </div>

    <!-- Order Details -->
    <div style="padding: 20px;">
        <h2 style="font-size: 18px; color: #000000; border-bottom: 1px solid #000000; padding-bottom: 10px;">פרטי ההזמנה</h2>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; direction: rtl;">
            <thead>
                <tr>
                    <th style="text-align: right; padding: 10px; border-bottom: 1px solid #eeeeee;">מוצר</th>
                    <th style="text-align: center; padding: 10px; border-bottom: 1px solid #eeeeee;">תמונה</th>
                    <th style="text-align: center; padding: 10px; border-bottom: 1px solid #eeeeee;">כמות</th>
                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;">מחיר</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( $order->get_items() as $item_id => $item ) {
                    $product = $item->get_product();
                    $product_id = $product ? $product->get_id() : 0;
                    $product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'thumbnail' );
                    ?>
                    <tr>
                        <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eeeeee;">
                            <?php echo esc_html( $item->get_name() ); ?>
                        </td>
                        <td style="text-align: center; padding: 10px; border-bottom: 1px solid #eeeeee;">
                            <?php if ( $product_image ) : ?>
                                <img src="<?php echo esc_url( $product_image[0] ); ?>" width="50" height="50" style="max-width: 50px; height: auto;">
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center; padding: 10px; border-bottom: 1px solid #eeeeee;">
                            <?php echo esc_html( $item->get_quantity() ); ?>
                        </td>
                        <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;">
                            <?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;">סכום ביניים:</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></td>
                </tr>

                <tr>
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;">משלוח:</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( $order->get_shipping_to_display() ); ?></td>
                </tr>

                <tr>
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;">מע"מ (18%):</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( ($order->get_total() * 0.18) ); ?></td>
                </tr>

                <tr>
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee; font-size: 18px;">סה"כ לתשלום:</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee; font-size: 18px; font-weight: bold;"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Payment Method -->
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; color: #000000; margin-bottom: 10px;">אמצעי תשלום</h3>
            <p style="margin: 0; padding: 0;"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
        </div>

        <!-- Shipping Address -->
        <div style="margin-bottom: 20px; width: 100%; display: inline-block;">
            <div style="width: 48%; float: right;">
                <h3 style="font-size: 16px; color: #000000; margin-bottom: 10px;">כתובת למשלוח</h3>
                <address style="padding: 10px; border: 1px solid #eeeeee; background-color: #fafafa; margin: 0; text-align: right;">
                    <?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?>
                </address>
            </div>

            <div style="width: 48%; float: left;">
                <h3 style="font-size: 16px; color: #000000; margin-bottom: 10px;">כתובת לחיוב</h3>
                <address style="padding: 10px; border: 1px solid #eeeeee; background-color: #fafafa; margin: 0; text-align: right;">
                    <?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
                    <?php if ( $order->get_billing_phone() ) : ?>
                        <br>טלפון: <?php echo esc_html( $order->get_billing_phone() ); ?>
                    <?php endif; ?>
                    <?php if ( $order->get_billing_email() ) : ?>
                        <br>אימייל: <?php echo esc_html( $order->get_billing_email() ); ?>
                    <?php endif; ?>
                </address>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Contact Information -->
    <div style="background-color: #000000; color: #ffffff; padding: 20px; text-align: center; margin-top: 20px;">
        <p style="margin-bottom: 10px;">
            אם יש לך שאלות בנוגע להזמנה שלך, אנא צור איתנו קשר:
            <br>
            <a href="mailto:customercare@giovanniraspini-shop.co.il" style="color: #ffffff; text-decoration: underline;">customercare@giovanniraspini-shop.co.il</a>
        </p>

        <div style="margin-top: 20px;">
            <a href="https://giovanniraspini-shop.co.il/terms-conditions/" style="color: #ffffff; text-decoration: underline; margin: 0 10px;">תנאי שימוש</a>
            <a href="https://giovanniraspini-shop.co.il/privacy-policy/" style="color: #ffffff; text-decoration: underline; margin: 0 10px;">מדיניות פרטיות</a>
            <a href="https://giovanniraspini-shop.co.il/shipping-returns/" style="color: #ffffff; text-decoration: underline; margin: 0 10px;">משלוחים והחזרות</a>
        </div>

        <div style="margin-top: 20px; font-size: 12px;">
            &copy; <?php echo date('Y'); ?> Giovanni Raspini. כל הזכויות שמורות.
        </div>
    </div>
</div>
