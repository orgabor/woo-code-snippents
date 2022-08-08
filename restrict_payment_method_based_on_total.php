<?php
add_filter('woocommerce_available_payment_gateways', 'turnPaymentOff');

function turnPaymentOff($available_gateways)
{
    /* Turn on if you do not want to see it if you are admin
    if (is_admin()) {
    return $available_gateways;
    }
     */
    // STEP 1: Get order/cart total without shipping costs
    if (is_wc_endpoint_url('order-pay')) {
        // Vertrauen ist gut, kontrollen ist besser
        $key = filter_var($_GET['key'], FILTER_SANITIZE_STRING);

        $order_id = wc_get_order_id_by_order_key($key);
        $order = wc_get_order($order_id);
        $order_total = $order->get_total();

    } else { // Cart/Checkout page
        $order_total = WC()->cart->subtotal;
    }

    // STEP 2: Disable payment gateway if order/cart total is more than 1000
    if ($order_total > 1000) {
        unset($available_gateways['cod']); // unset Cash on Delivery
    }

    return $available_gateways;

}
