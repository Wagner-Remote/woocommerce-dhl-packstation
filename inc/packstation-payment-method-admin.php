<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TimPackstationConditionalPayment' ) ) :

class TimPackstationConditionalPayment {

	private static $_instance = null;

	public static function instance(){
		if ( is_null(self::$_instance) )
			self::$_instance = new self();
		return self::$_instance;
	}

	private function __construct() {
		// handle options
		if ( is_admin() ){
			add_action( 'wp_loaded' , array( &$this , 'add_payment_options'), 99 );
			add_action( 'woocommerce_update_options_checkout' , array( &$this , 'add_payment_options') );
		}
		add_filter( 'woocommerce_available_payment_gateways', array( &$this , 'disable_payment_gateway') );
	}

	function add_payment_options( ) {
		$defaults = null;
		$tax_class_options = null;

		$form_fields = array(
			'tim_packstation_title' => array(
				'title' => __( 'Packstation', 'packstation' ),
				'type' => 'title',
				'class' => 'tim_packstation-title',
				'description' => '',
			),
			'tim_packstation_disable_for_packstation' => array(
				'title' => __( 'Versand an Packstationen' , 'packstation' ),
				'label' => __( 'Zahlunsart deaktivieren wenn Versand an Packstation erfolgt' , 'packstation' ),
				'type' => 'checkbox',
				'desc_tip' => true,
			),		
		);					
		foreach ( WC()->payment_gateways()->payment_gateways() as $gateway_id => $gateway ) {
			$gateway->form_fields += $form_fields;
			add_action( 'woocommerce_update_options_payment_gateways_'.$gateway->id , array($this,'update_payment_options') , 20 );
		}
	}
	
	function update_payment_options() {
		global $current_section;
		//$class = new $current_section();
		$prefix = 'woocommerce_'.$current_section;
		$opt_name = $prefix.'_settings';
		$options = get_option( $opt_name );
		// validate
		$extra = array(
			'tim_packstation_disable_for_packstation'	=> $this->_get_bool( $prefix.'_tim_packstation_disable_for_packstation' ), 
		
		);
		$options += $extra;
		update_option( $opt_name , $options );
	}

	private function _get_bool( $key ) {
		return isset($_POST[ $key ]) && $_POST[ $key ] === '1' ? 'yes' : 'no';
	}
	private function _get_float( $key ) {
		return isset($_POST[ $key ]) && $_POST[ $key ] === '1' ? 'yes' : 'no';
	}
	
	// disable payment on checkout page
	public function disable_payment_gateway( $available_gateways ) {
		global $woocommerce;
		$shippingAddress = $woocommerce->customer->get_shipping_address();
		if( strpos($shippingAddress, 'Pack station') !== false || strpos($shippingAddress, 'Packstation') !== false ){
			// loop through all available gateways and check if disable for packstation option is true (yes)
			foreach ($available_gateways as $key => $value) {
				if( isset( $value->settings['tim_packstation_disable_for_packstation'] ) && 'yes' == $value->settings['tim_packstation_disable_for_packstation'] ){
					unset(  $available_gateways[$key] ); // unset gateway when disable packstation option is true (yes)
				}
			}
		}	
		return $available_gateways;
	}	
}

TimPackstationConditionalPayment::instance();

endif;