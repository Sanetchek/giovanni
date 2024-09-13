<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 50.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="profile-dashboard">
	<div class="dashboard-item">
		<a href="/my-account/orders/" class="dashboard-link">
			<span><?= __('הזמנות והחזרות ', 'giovanni') ?></span>
		</a>
		<p><?= __('בדוק את סטטוס ההזמנות שלך או ראה הזמנות קודמות ', 'giovanni') ?></p>
		<div class="content-box">
			<?php
			$user_id = get_current_user_id();
			$orders = wc_get_orders(array(
				'customer_id' => $user_id,
				'return' => 'ids',
			));
			$order_count = count($orders);

			if ($order_count > 0) {
				// Display the order count if there are orders
				echo sprintf('<p>' . __('יש לך %d הזמנות', 'giovanni') . '</p>', $order_count);
			} else {
				// Display the message if no orders are found
				echo '<p>' . __('אין הזמנות ממתינות', 'giovanni') . '</p>';
			}
			?>
	</div>
	</div>
	<div class="dashboard-item">
		<a href="/my-account/edit-account/" class="dashboard-link">
			<span><?= __('פרטים אישיים ', 'giovanni') ?></span>
		</a>
		<p><?= __('הצג או עדכן את המידע האישי שלך ', 'giovanni') ?></p>
		<div class="content-box">
			<p class="dashboard-mail"><?= esc_html(wp_get_current_user()->user_email); ?></p>
		</div>
	</div>
	<div class="dashboard-item">
		<a href="/my-account/edit-address/" class="dashboard-link">
			<span><?= __('כתובת ', 'giovanni') ?></span>
		</a>
		<p><?= __('נהל את הכתובות למשלוח שלך ', 'giovanni') ?></p>
	</div>

	<div class="dashboard-item logout-item">
		<a href="<?= wp_logout_url(); ?>" class="logout-button">
			<?= __('התנתקות ', 'giovanni') ?>
		</a>
	</div>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
