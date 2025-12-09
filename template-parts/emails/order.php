<?php
/**
 * Order Email Template
 *
 * This template displays order details in WooCommerce emails.
 * Handles cases where $order might be null (e.g., email preview mode).
 */

// Retrieve order object if not already available
if ( ! isset( $order ) || ! is_a( $order, 'WC_Order' ) ) {
	// Try to get order from email object context (WooCommerce sets $email->object)
	if ( isset( $email ) && is_a( $email, 'WC_Email' ) && isset( $email->object ) && is_a( $email->object, 'WC_Order' ) ) {
		$order = $email->object;
	} else {
		// Fall back to getting the latest order for preview purposes
		$orders = wc_get_orders( array(
			'limit'   => 1,
			'orderby' => 'date',
			'order'   => 'DESC',
		) );

		if ( ! empty( $orders ) && is_a( $orders[0], 'WC_Order' ) ) {
			$order = $orders[0];
		} else {
			// No valid order found - return early to prevent fatal errors
			return;
		}
	}
}

// Validate order one more time before proceeding
if ( ! isset( $order ) || ! is_a( $order, 'WC_Order' ) ) {
	return;
}
?>
<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; color: #000000; background-color: #ffffff; direction: rtl; text-align: right;">
    <!-- Header -->
    <?php get_template_part('template-parts/emails/header') ?>

    <!-- Thank You Message -->
    <div style="padding: 20px; text-align: center; border-top: 1px solid #000000; border-bottom: 1px solid #000000; margin: 20px 0;">
        <h1 style="font-size: 24px; color: #000000; margin-bottom: 10px; text-align:center;"><?= __('תודה על הזמנתך!', 'giovanni') ?></h1>
        <p style="font-size: 16px; color: #000000;">
            <?php
            if ( $order && is_a( $order, 'WC_Order' ) ) {
                echo __('הזמנה חדשה מספר', 'giovanni') . ' ' . esc_html( $order->get_order_number() ) . ' ' . __('# מאתר Giovanni Raspini ISRL', 'giovanni');
            } else {
                echo __('הזמנה חדשה', 'giovanni') . ' ' . __('# מאתר Giovanni Raspini ISRL', 'giovanni');
            }
            ?>
        </p>
    </div>

    <!-- Order Details -->
    <div style="padding: 20px;">
        <h2 style="font-size: 18px; color: #000000; border-bottom: 1px solid #000000; padding-bottom: 10px;"><?= __('פרטי ההזמנה', 'giovanni') ?></h2>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; direction: rtl;">
            <thead>
                <tr>
                    <th style="text-align: right; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('מוצר', 'giovanni') ?></th>
                    <th style="text-align: center; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('תמונה', 'giovanni') ?></th>
                    <th style="text-align: center; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('כמות', 'giovanni') ?></th>
                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('מחיר', 'giovanni') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ( $order && is_a( $order, 'WC_Order' ) ) {
                    $order_items = $order->get_items();
                    if ( ! empty( $order_items ) ) {
                        foreach ( $order_items as $item_id => $item ) {
                            $product = $item->get_product();
                            $product_id = $product ? $product->get_id() : 0;
                            $product_image_id = get_post_thumbnail_id( $product_id );
                            ?>
                            <tr>
                                <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eeeeee;">
                                    <?php echo esc_html( $item->get_name() ); ?>
                                </td>
                                <td style="text-align: center; padding: 10px; border-bottom: 1px solid #eeeeee;">
                                    <?php if ( $product_image_id && $product ) : ?>
                                        <?php
                                        $data = [
                                            'thumb' => [50, 50],
                                            'args' => [
                                                'alt' => esc_attr( $product->get_name() ),
                                            ],
                                        ];
                                        echo liteimage( $product_image_id, $data );
                                        ?>
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
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <?php if ( $order && is_a( $order, 'WC_Order' ) ) : ?>
                    <tr>
                        <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('סכום ביניים:', 'giovanni') ?></th>
                        <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></td>
                    </tr>

                    <tr>
                        <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('משלוח:', 'giovanni') ?></th>
                        <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( $order->get_shipping_to_display() ); ?></td>
                    </tr>

                    <tr>
                        <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('מע"מ (18%):', 'giovanni') ?></th>
                        <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( wc_price( $order->get_total() * 0.18 ) ); ?></td>
                    </tr>

                    <tr>
                        <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee; font-size: 18px;"><?= __('סה"כ לתשלום:', 'giovanni') ?></th>
                        <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee; font-size: 18px; font-weight: bold;"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
                    </tr>
                <?php endif; ?>
            </tfoot>
        </table>

        <!-- Payment Method -->
        <?php if ( $order && is_a( $order, 'WC_Order' ) ) : ?>
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 16px; color: #000000; margin-bottom: 10px;"><?= __('אמצעי תשלום', 'giovanni') ?></h3>
                <p style="margin: 0; padding: 0;"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
            </div>
        <?php endif; ?>

        <!-- Shipping Address -->
        <?php if ( $order && is_a( $order, 'WC_Order' ) ) : ?>
            <div style="margin-bottom: 20px; width: 100%; display: inline-block;">
                <div style="width: 48%; float: right;">
                    <h3 style="font-size: 16px; color: #000000; margin-bottom: 10px;"><?= __('כתובת למשלוח', 'giovanni') ?></h3>
                    <address style="padding: 10px; border: 1px solid #eeeeee; background-color: #fafafa; margin: 0; text-align: right;">
                        <?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?>
                    </address>
                </div>

                <div style="width: 48%; float: left;">
                    <h3 style="font-size: 16px; color: #000000; margin-bottom: 10px;"><?= __('כתובת לחיוב', 'giovanni') ?></h3>
                    <address style="padding: 10px; border: 1px solid #eeeeee; background-color: #fafafa; margin: 0; text-align: right;">
                        <?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
                        <?php if ( $order->get_billing_phone() ) : ?>
                            <br><?= __('טלפון:', 'giovanni') ?> <?php echo esc_html( $order->get_billing_phone() ); ?>
                        <?php endif; ?>
                        <?php if ( $order->get_billing_email() ) : ?>
                            <br><?= __('אימייל:', 'giovanni') ?> <?php echo esc_html( $order->get_billing_email() ); ?>
                        <?php endif; ?>
                    </address>
                </div>
            </div>
        <?php endif; ?>
        <div style="clear: both;"></div>
    </div>

    <!-- Contact Information -->
    <?php get_template_part('template-parts/emails/footer') ?>
</div>
