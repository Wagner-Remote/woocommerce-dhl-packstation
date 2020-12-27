<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_packstation_availability_check', 'tim_packstation_availability_check' );
add_action( 'wp_ajax_nopriv_packstation_availability_check', 'tim_packstation_availability_check' );
function tim_packstation_availability_check() {
	global $wpdb;

    if ( false == tim_packstation_is_packstation_available() ){
        $available = false;
    }else{
        $available = true;
    }
    
    $return = array(
        "available" => $available,
    );

    echo json_encode($return);

	wp_die(); 
}