<div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; color: #000000; background-color: #ffffff; direction: rtl; text-align: right;">
    <!-- Header -->
    <?php get_template_part('template-parts/emails/header') ?>

    <!-- Thank You Message -->
    <div style="padding: 20px; text-align: center; border-top: 1px solid #000000; border-bottom: 1px solid #000000; margin: 20px 0;">
        <h1 style="font-size: 24px; color: #000000; margin-bottom: 10px;"><?= __('תודה על הזמנתך!', 'giovanni') ?></h1>
        <p style="font-size: 16px; color: #000000;">
            <?php echo __('הזמנה חדשה מספר', 'giovanni') . ' ' . esc_html( $order->get_order_number() ) . ' ' . __('# מאתר Giovanni Raspini ISRL', 'giovanni') ?>
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
                                <img src="<?php echo esc_url( $product_image[0] ); ?>" width="50" height="50" style="max-width: 50px; height: auto;" loading="lazy">
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
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('סכום ביניים:', 'giovanni') ?></th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></td>
                </tr>

                <tr>
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('משלוח:', 'giovanni') ?></th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( $order->get_shipping_to_display() ); ?></td>
                </tr>

                <tr>
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?= __('מע"מ (18%):', 'giovanni') ?></th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee;"><?php echo wp_kses_post( ($order->get_total() * 0.18) ); ?></td>
                </tr>

                <tr>
                    <th scope="row" colspan="3" style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee; font-size: 18px;"><?= __('סה"כ לתשלום:', 'giovanni') ?></th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #eeeeee; font-size: 18px; font-weight: bold;"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Payment Method -->
        <div style="margin-bottom: 20px;">
            <h3 style="font-size: 16px; color: #000000; margin-bottom: 10px;"><?= __('אמצעי תשלום', 'giovanni') ?></h3>
            <p style="margin: 0; padding: 0;"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
        </div>

        <!-- Shipping Address -->
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
        <div style="clear: both;"></div>
    </div>

    <!-- Contact Information -->
    <?php get_template_part('template-parts/emails/footer') ?>
</div>
