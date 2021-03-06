<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_packstation_finder', 'tim_packstation_finder_ajax' );
add_action( 'wp_ajax_nopriv_packstation_finder', 'tim_packstation_finder_ajax' );
function tim_packstation_finder_ajax() {
	global $wpdb; // this is how you get access to the database

	$zip = intval( $_POST['zip'] );

    $userName = 'wagnerremote';
    $password = 'R$CZ9RwHdnHmd_4';
    $endpoint = 'https://cig.dhl.de/services/sandbox/soap';
    //$endpoint = 'https://cig.dhl.de/services/production/soap';
	
    $client = new SoapClient("https://cig.dhl.de/cig-wsdls/com/dpdhl/wsdl/standortsuche-api/1.0/standortsuche-api-1.0.wsdl", [
        'login' => $userName,
        'password' => $password,
        'location' => $endpoint,
        'soap_version' => SOAP_1_1
    ]);
    $address = new stdClass();
    $address->zip = $zip;
    $call = new stdClass();
    $call->key = '';
    $call->address = $address;
    $getPackstationsByAddrResponse = $client->__soapCall('getPackstationsByAddress', [$call]);
    $packstations = $getPackstationsByAddrResponse->packstation;

    $code = '';

    foreach ($packstations as $key => $value) {
        $code .= '<div class="result">
                <div>
                    <strong>' . $value->address->remark . '</strong><br>
                    <strong>' . $value->address->street . ' ' . $value->address->streetNo . ', ' . $value->address->zip . ' ' . $value->address->city . '</strong><br>
                    <i>Packstation ' . $value->packstationId . '</i>
                </div>
                <div>
                    <a data-packstationnumber="' . $value->packstationId . '" data-zip="' . $value->address->zip. '" data-city="' . $value->address->city. '" class="set-packstation-button button">Packstation wählen</a>
                </div>
            </div>';
    }

    $return = array(
        "code" => $code,
        "data" => $packstations,
    );

    echo json_encode($return);
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_head', 'tim_packstation_ajaxurl');
function tim_packstation_ajaxurl() {
   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
