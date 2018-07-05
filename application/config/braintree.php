<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$send = getsettingsdetails();
$config['braintree_merchant_id'] = $send->braintree_merchant_id;
$config['braintree_public_key'] = $send->braintree_public_key;
$config['braintree_private_key'] = $send->braintree_private_key;
$config['braintree_environment'] = 'sandbox';