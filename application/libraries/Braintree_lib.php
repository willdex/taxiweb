<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'third_party/Braintree.php';

/*
 *  Braintree_lib
 *  This is a codeigniter wrapper around the braintree sdk, any new sdk can be wrapped around here
 *  License: MIT to accomodate braintree open source sdk license (BSD)
 *  Author: Clint Canada
 *  Library tests (and parameters for lower Braintree functions) are found in:
 *  https://github.com/braintree/braintree_php/tree/master/tests/integration
 */

/**
    General Usage:
        In Codeigniter controller
        function __construct(){
            parent::__construct();
            $this->load->library("braintree_lib");
        }

        function <function>{
            $token = $this->braintree_lib->create_client_token();
            $data['client_token'] = $token;
            $this->load->view('myview',$data);
        }

        In View section
        <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
        <script>
              braintree.setup("<?php echo $client_token;?>", "<integration>", options);
        </script>

    For more information on javascript client: 
    https://developers.braintreepayments.com/javascript+php/sdk/client/setup
 */

class Braintree_lib extends Braintree{

	function __construct() {
        parent::__construct();
        // We will load the configuration for braintree
        $CI = &get_instance();
        $CI->config->load('braintree', TRUE);
        $braintree = $CI->config->item('braintree');
        // Let us load the configurations for the braintree library
        Braintree_Configuration::environment($braintree['braintree_environment']);
		Braintree_Configuration::merchantId($braintree['braintree_merchant_id']);
		Braintree_Configuration::publicKey($braintree['braintree_public_key']);
		Braintree_Configuration::privateKey($braintree['braintree_private_key']);
    }

    // This function simply creates a client token for the javascript sdk
    function create_client_token(){
    	$clientToken = Braintree_ClientToken::generate();
    	return $clientToken;
    }
}