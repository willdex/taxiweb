<?php
$ci =& get_instance();
$ci->load->model('model_admin');

function getsettingsdetails(){
	
	$ci =& get_instance();
	$s =$ci->model_admin->settings_details();
	
	//print json_encode( $s );
	return $s;
}


?>