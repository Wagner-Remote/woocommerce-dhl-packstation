<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'woocommerce_checkout_fields' , 'packstation_add_fields' );
function packstation_add_fields( $fields ) {
    if ( false == tim_packstation_is_packstation_available() ) return $fields;

    $send_to_packstation_element = array(
        'type' => 'checkbox',
        'class' => array('form-row-wide'),
        'label' => __('An <i class="packstation-label"><span>Packstation</span></i> senden ', 'packstation'),
    );

    $fields['shipping'] = add_array_element_before_index($fields['shipping'], $send_to_packstation_element, 'send_to_packstation', 2 );

    // search station
    $packstation_finder_placeholder = array(
        'type' => 'text',
        'class' => array('packstation-hidden' ),
        'label' => __('<a id="packstation-finder" class="button"><i class="fa fa-location-arrow" aria-hidden="true"></i> Packstation finden</a>', 'packstation'),
        //'required' => true
    );
    $fields['shipping'] = add_array_element_before_index($fields['shipping'], $packstation_finder_placeholder, 'packstation_finder_placeholder', 3 );

    // Add customer number
    $packstation_customer_number = array(
        'type' => 'number',
        'class' => array('col4-set', 'form-row-first', 'packstation-hidden'),
        'label' => __('Postnummer', 'packstation'),
        'required' => true
    );
    $fields['shipping'] = add_array_element_before_index($fields['shipping'], $packstation_customer_number, 'packstation_customer_number', 4 );

    // Add station number
    $packstation_packstation_number = array(
        'type' => 'number',
        'class' => array('col4-set', 'form-row-last', 'packstation-hidden'),
        'label' => __('Packstation Nummer', 'packstation'),
        'required' => true,
        'clear' => true,
    );
    $fields['shipping'] = add_array_element_before_index($fields['shipping'], $packstation_packstation_number, 'packstation_packstation_number', 5 );

    return $fields;
}

/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'wr_custom_checkout_field_process');

function wr_custom_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( isset ( $_POST['send_to_packstation'] ) && 1 == $_POST['send_to_packstation'] ){

        if ( ! isset ( $_POST['packstation_customer_number'] ) || empty( $_POST['packstation_customer_number'] ) )
            wc_add_notice( __( '<strong>Postnummer: </strong> Bitte gib deine Postnummer ein' ), 'error' );
        if ( ! isset ( $_POST['packstation_packstation_number'] ) || empty( $_POST['packstation_packstation_number'] ) )
            wc_add_notice( __( '<strong>Packstation Nummer: </strong> Bitte gib eine Packstation Nummer ein oder wähle eine Packstation über die Packstation Suche aus.' ), 'error' );

        if ( isset ( $_POST['shipping_country'] ) && 'DE' != $_POST['shipping_country'] )
            wc_add_notice( __( '<strong>Packstation: </strong> Der Versand an Packstationen kann nur innerhalb Deutschlands erfolgen. Bitte wähle eine andere Adresse oder anderes Land.' ), 'error' );
    }      
}

function add_array_element_before_index( $array, $new_element, $new_key, $index ) {

    $new_array = array();

    $x = 0;
    foreach ($array as $key => $value) {
        
        if( $index == $x ){
            $new_array[$new_key] = $new_element;
        }
        $new_array[$key] = $value;

        $x++;
    }

    return $new_array;

}