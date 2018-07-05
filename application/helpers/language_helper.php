<?php


function getLanguageForUserApp(){
  /*
  * Note: Please avoid special characters like '?!&...etc' for array key
  */

  $languageForUserApp = array(
                             //Landing.Html//							    
								'Newuser_SignUp_now' => 'New user SignUp now',
								'Or_sign_In_with' => 'Or sign In with',
								'Forgot_Password' => 'Forgot Password',
								'No_network_connection' => 'No network connection',
								'Sign_In' => 'Sign In',
							 //Sign up .Html//
							    'SIGN_UP' => 'SIGN UP',
							    'Enter_your_name' => 'Enter your name',
								'Name' => 'Name',
								'Enter_user_name' => 'Enter user name',
								'Enter_your_number' => 'Enter your number',
								'Enter_valid_mobile_number' => 'Enter valid mobile number',
								'Mobile' => 'Mobile',
								'Enter_email' => 'Enter email',
								'Enter_valid_email' => 'Enter valid email',
								'Enter_Password' => 'Enter Password',
								'Mail' => 'Mail',
                             //Login.html//
                                //'sign_up' => 'Sign Up user',
								'SIGN_IN' => 'SIGN IN',
								'Enter_username_email_mobile' => 'Enter user name / email / mobile',
								'Mobile_User_Name_Email' => 'Mobile / User Name / Email',
								'password' => 'password',								
							 //Main Landing.html//	
								'CallMy_Cab' => 'CallMyCab',
								'Enter_Pickup_location' => 'Enter Pickup location',
								'Enter_Drop_location' => 'Enter Drop location',
								'Toyota_etios_tata_indigo_maruti_dezire' => 'Toyota etios / tata indigo / maruti dezire',
								'Fare_Breakup' => 'Fare Breakup',
								'First' => 'First',
								'After' => 'After',
								'Ridetime_rate' => 'Ride time rate',
								'Airport_rate_may_differ_peaktime_chargesmayapply' => 'Airport rate may differ peak  time charges may apply',
								'RIDE_LATER' => 'RIDE LATER',
								'RIDE_NOW' => 'RIDE NOW',
								'Cancel' => 'Cancel',
								'Book' => 'Book',
							 //Menu.html//
							    'Book_My_Ride' => 'Book My Ride',
								'My_Trips' => 'My Trips',
								'Rate_Card' => 'Rate Card',
								'Logout' => 'Logout',
							 //My Trip.html//
							    'My_Trip' => 'My Trip',
								'ALL_RIDES' => 'ALL RIDES',
								'COMPLETED' => 'COMPLETED',
								'BOOKED' => 'BOOKED',
							 //Rate Card.html//
							    'Rate_Card' => 'Rate Card',
								'NIGHT' => 'NIGHT',
								'DAY' => 'DAY',
							 //Settings.Html//
                                'Profile' => 'Profile',
								'User_Name' => 'User Name',
								'MAIL' => 'MAIL',
								'CHANEGE_PASSWORD' => 'CHANEGE PASSWORD',
								'Enter_new_Password' => 'Enter new Password',
								'Minimum_6_characters' => 'Minimum 6 characters',
								'Passwords_do_not_match' => 'Passwords do not match',
								'Conform_password' => 'Conform password',
								'RESET_PASSWORD' => 'RESET PASSWORD',
							 //Trip Details
                                'Trip_Details' => 'Trip Details',
								'BOOKING_ID' => 'BOOKING ID',
								'PICKUP_POINT' => 'PICKUP POINT',
								'TO' => 'TO',
								'DROP_POINT' => 'DROP POINT',
								'VEHICLE_DETAILS' => 'VEHICLE DETAILS',
								'CAB_TYPE' => 'CAB TYPE',
								'DRIVER_DETAILS' => 'DRIVER DETAILS',
								'Payment_Details' => 'Payment Details',
								'Distance' => 'Distance',
								'Total_Amount' => 'Total Amount',
								'SEND_YOUR_FEED_BACK' => 'SEND YOUR FEED BACK',
                                                              //Alerts
                                                              'Enter_date_and_time' => 'Enter date and time',
                                                               'Cancel' => 'Cancel',
                                                               'Save' => 'Save',
                                                               'success' => 'success',
                                                               'FAILED' => 'FAILED',
                                                               'Try again' => 'Try again',
                                                               'Enter_pickup_location'=>'Enter pickup location',
                                                               'Enter_Drop_location' => 'Enter Drop location',
                                                               'Process_Failed' => 'Process Failed!',
                                                               'Password_successfully_updated'=>'Password successfully updated'
                               );
  return $languageForUserApp;
}

function getLanguageForDriverApp(){

/*
* Note: Please avoid special characters like '?!&...etc' for array key
*/

  $languageForDriverApp = array(
                                'New_user_Sign_Up_Now' => 'New user ? Sign Up Now !',
                                'Sign_In' => 'Sign In',
                                'Forgot_Password' => 'Forgot Password',
                                'Or_sign_In_with' => 'Or sign In with',
                                'SIGN_UP' => 'SIGN UP',
                                'Name' => 'Name',
                                'User_Name' => 'User Name',
                                'Mobile' => 'Mobile',
                                'Mail' => 'Mail',
                                'Password' => 'Password',
                                'Confirm_Password' => 'Confirm Password',
                                'Enter_your_name' => 'Enter your name',
                                'Enter_user_name' => 'Enter user name',
                                'Enter_your_number' => 'Enter your number',
                                'Enter_valid_mobile_number' => 'Enter valid mobile number',
                                'Enter_email' => 'Enter email',
                                'Enter_valid_email' => 'Enter valid email',
                                'Enter_Password' => 'Enter Password',
                                'Minimum_6_characters' => 'Minimum 6 characters',
                                'Passwords_do_not_match' => 'Passwords do not match.',
                                'Enter_user_name_email_mobile' => 'Enter user name/email/mobile',
                                'My_Trips' => 'My Trips',
                                'Logout' => 'Logout',
                                'My_Ride' => 'My Ride',
                                'NEW_RIDES' => 'NEW RIDES',
                                'COMPLETED' => 'COMPLETED',
                                'CANCELLED' => 'CANCELLED',
                                'Trip_Details' => 'Trip Details',
                                'BOOKING_ID' => 'BOOKING ID',
                                'BOOKING_ID' => 'BOOKING ID',
                                'PICKUP_POINT' => 'PICKUP POINT',
                                'TO' => 'TO',
                                'DROP_POINT' => 'DROP POINT',
                                'VEHICLE_DETAILS' => 'VEHICLE DETAILS',
                                'CAB_TYPE' => 'CAB TYPE',
                                'DRIVER_DETAILS' => 'DRIVER DETAILS',
                                'Payment_Details' => 'Payment Details',
                                'Distance' => 'Details',
                                'Total_Amount' => 'Total Amount',
                                'Accept' => 'Accept',
                                'SEND_YOUR_FEED_BACK' => 'SEND YOUR FEED BACK',
                                'No_network_connection' => 'No network connection!',
                                'GET_DIRECTIONS' => 'GET DIRECTIONS',
                                'START_NOW' => 'START NOW',
                                'Map_View' => 'Map View',
                                'Rate_Card' => 'Rate Card',
                                'RUNNING_DETAILES' => 'RUNNING DETAILES',
                                'CURRENT_LOCATION' => 'CURRENT LOCATION',
                                'MINIMUM_DISTANCE' => 'MINIMUM DISTANCE',
                                'MINIMUM_RATE' => 'MINIMUM RATE',
                                'STANDARD_RATE' => 'STANDARD RATE',
                                'STANDARD_RATE' => 'STANDARD RATE',
                                'TRIP_TYPE' => 'TRIP TYPE',
                                'TOTAL_TRAVELED' => 'TOTAL TRAVELED',
                                'TOTAL_RATE' => 'TOTAL RATE',
                                'CANCEL' => 'CANCEL',
                                'STOP' => 'STOP',
                                'SUCCESS' => 'SUCCESS',
                                'Password_successfully_updated'=>'Password successfully updated',
                                'Failed' => 'Failed',
                                'Current_password_is_error' => 'Current password is error',
                                'ThankYou'=>'ThankYou',
                                'You_are_successfully_registered'=>'You are successfully registered',
                                 'Receipt' => 'Receipt',
                                 'Billing_Details'=>'Billing Details',
                                 'Extra_Rate' => 'Extra Rate',
                                 'Total_Distance_Travelled'=>'Total Distance Travelled'
                               );
  return $languageForDriverApp;
}






?>
