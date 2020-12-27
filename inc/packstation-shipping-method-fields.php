<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function packstation_shipping_method_form_fields($defaults){
    $defaults['packstation'] = array(
				'title'       => __( 'Packstation', 'woocommerce' ),
				'type'        => 'checkbox',
				'placeholder' => wc_format_localized_price( 0 ),
				'description' => __( 'Users will need to spend this amount to get free shipping (if enabled above).', 'woocommerce' ),
				'default'     => '0',
				'desc_tip'    => true,
                'label' => __('Versand an Packstationen für diese Versandart deaktivieren', 'packstation'),
			);
    return $defaults;
}

add_action('admin_init', 'packstation_admin_init_test');

function packstation_admin_init_test() {
    $shipping_methods = WC()->shipping->get_shipping_methods();

    foreach ($shipping_methods as $id => $shipping_method) {
       add_filter('woocommerce_shipping_instance_form_fields_' . $id, 'packstation_shipping_method_form_fields', 10, 1);
    }
}

function tim_packstation_hide_shipping_if_for_packstation_if_not_available_for_packstation( $rates ) {
	$free = array();

    $shippingAddress = '';
    
    if( isset( $_POST["s_address"] ) ) $shippingAddress = $_POST["s_address"];

    if( strpos($shippingAddress, 'Pack station') !== false || strpos($shippingAddress, 'Packstation') !== false ){       
        foreach ( $rates as $rate_id => $rate ) {
            
            $shippingMethodTitle = explode(":", $rate_id);
            $shippingMethodOptions = get_option( 'woocommerce_' . $shippingMethodTitle[0] . '_' . $shippingMethodTitle[1] . '_settings', false );
            $disablePackstation = $shippingMethodOptions['packstation'];
            if( 'yes' == $disablePackstation ){
               unset($rates[$rate_id]);
            }
        }
    }

    return $rates;
}
add_filter( 'woocommerce_package_rates', 'tim_packstation_hide_shipping_if_for_packstation_if_not_available_for_packstation', 100 );