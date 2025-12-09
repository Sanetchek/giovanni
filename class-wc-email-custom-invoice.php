<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Email_Custom_Invoice extends WC_Email {

    public function __construct() {

        $this->id          = 'custom_payment_invoice';
        $this->title       = __( 'Custom Payment Invoice', 'giovanni' );
        $this->description = __( 'Sends a custom payment invoice with a secure payment link.', 'giovanni' );

        $this->template_html  = 'emails/custom-payment-invoice.php';
        $this->template_plain = 'emails/plain/custom-payment-invoice.php';
        $this->template_base  = get_stylesheet_directory() . '/woocommerce/';

        $this->placeholders = array(
            '{order_number}' => ''
        );

        $this->subject = __( 'בקשת תשלום להזמנה מספר {order_number}', 'giovanni' );
        $this->heading = __( 'בקשת תשלום להזמנה {order_number}', 'giovanni' );

        add_action( 'woocommerce_order_action_send_custom_invoice', array( $this, 'trigger' ) );

        parent::__construct();
    }


    public function trigger( $order_id ) {

        if ( ! $order_id ) return;

        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        if ( $order->get_status() !== 'pending' ) {
            $order->set_status( 'pending' );
            $order->save();
        }

        $this->object = $order;

        $this->recipient = $order->get_billing_email();
        if ( ! $this->recipient ) return;

        $this->placeholders['{order_number}'] = $order->get_order_number();

        if ( ! $this->is_enabled() ) return;

        $this->send(
            $this->get_recipient(),
            $this->get_subject(),
            $this->get_content(),
            $this->get_headers(),
            $this->get_attachments()
        );
    }



    public function get_content_html() {

        return wc_get_template_html(
            $this->template_html,
            array(
                'order' => $this->object,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }


    public function get_content_plain() {

        return wc_get_template_html(
            $this->template_plain,
            array(
                'order' => $this->object,
                'email' => $this,
            ),
            '',
            $this->template_base
        );
    }
}
