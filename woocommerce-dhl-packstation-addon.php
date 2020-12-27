<?php
/*
Plugin Name: WooCommerce DHL Packstation Addon
Plugin URI: https://wagner-remote.de
Description: DHL Packstation als Lieferadresse
Version: 1.0
Author: Tim Wagner | Wagner-Remote
Author URI: https://wagner-remote.de
Text Domain: packstation
Domain Path: /languages/
Requires at least: 5.0
Tested up to: 5.3.2
WC requires at least: 4.0.0
WC tested up to: 4.0.1

Logo bei Auswahl Lieferadresse @ initial-load
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Packstation_Plugin
{
	
	function __construct()
	{
		add_action( 'init', array(&$this, 'load_textdomain' ) );		
		define('PACKSTATION_DIR_URI', plugin_dir_url( __FILE__ ) );
		$this->load_other_dependencies();
		$this->enqueue_scripts();
	}

	private function load_other_dependencies(){
		include "inc/additional-fields.php";
		include "inc/popup.php";
		include "inc/packstation-finder.php";
		include "inc/packstation-product-field.php";
		include "inc/packstation-shipping-method-fields.php";
		include "inc/packstation-availability-check.php";
		include "inc/packstation-payment-method-admin.php";
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'packstation', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}

	public function enqueue_scripts() {
		add_action( 'wp_enqueue_scripts', function(){
            wp_enqueue_script( 'packstation-conditional-fields', plugins_url('assets/js/conditional-fields.js', __FILE__), array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'popup', plugins_url('assets/js/popup.js', __FILE__), array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'init-load', plugins_url('assets/js/initial-load.js', __FILE__), array('jquery'), '1.0.0', true );
            
			wp_enqueue_style( 'packstation-style', plugins_url('assets/css/packstation.css', __FILE__), array(), '1.0.0', 'all' );
			//wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), '4.7.0', 'all' );
		} );
	}
}

new Packstation_Plugin();