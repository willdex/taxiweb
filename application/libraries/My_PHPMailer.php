<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_PHPMailer {
    public function My_PHPMailer() {
    	//require_once dirname(__FILE__).'/MY_Email_3_1_x.php';
        require_once dirname(__FILE__).'/PHPMailer/PHPMailerAutoload.php';
    }
}