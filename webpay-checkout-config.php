<?php

function webpay_checkout_get_settings($key = null) {
    $sets = array(
        'slug' => 'webpay-checkout',
        'nonce' => 'webpay-checkout-nonce',
        'group' => 'webpay-checkout-settings-group',
        'section' => 'webpay-checkout-section',
        'option' => 'webpay-checkout-settings',
        'action' => 'webpay_checkout'
    );

    if (empty($key)) {
        return $sets;
    } else if (isset($sets[$key])) {
        return $sets[$key];
    }

    return null;
}

function webpay_get_public_key() {
    $options = get_option( webpay_checkout_get_settings('option') );

    return isset( $options['public-key'] ) ? $options['public-key'] : '';
}

function webpay_get_private_key() {
    $options = get_option( webpay_checkout_get_settings('option') );

    return isset($options['private-key']) ? $options['private-key'] : '';
}

function webpay_get_currency() {
    $options = get_option( webpay_checkout_get_settings('option') );

    return isset( $options['currency'] ) ? $options['currency'] : '';
}