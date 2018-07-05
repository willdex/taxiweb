

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <?php
	   $query = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
		$row = $query->row('settings');
	  ?>
      <title><?php echo $row->title; ?></title>
<!--      <title>--><?php //echo $row->title; ?><!--</title>-->
       <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
      <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
       <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet">
      <!-- Bootstrap core CSS -->
      <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet">
      <link href='<?php echo base_url();?>assets/css?family=Open+Sans' rel='stylesheet' type='text/css'>
      <!-- Custom styles for this template -->
      <link href="<?php echo base_url();?>assets/css/jumbotron.css" rel="stylesheet">
      <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
      <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
         <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script> <!-- Gem jQuery -->
      <!-- Load jQuery UI Main JS  -->
    
      <script src="<?php echo base_url();?>assets/js/ie-emulation-modes-warning.js"></script>
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      
      <![endif]-->
      
      <link rel="shortcut icon" type="image/icon" href="<?php echo base_url();?>upload/favicon.png"/>
      
      
    <!-- Login -->
    <link href='<?php echo base_url();?>assets/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'> 
 	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/login/style.css"> <!-- Gem style -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/sidebar/component.css"> <!-- Gem style -->
	<script src="<?php echo base_url();?>assets/js/login/modernizr.js"></script> <!-- Modernizr -->
    <script src="<?php echo base_url();?>assets/js/login/main.js"></script> <!-- Gem jQuery -->
    
    <!--datepicker-->
    <!-- Load jQuery from Google's CDN -->
    <!-- Load jQuery UI CSS  -->
    
     
      
      

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    
    
    
      
   <!--google map-->
            <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
         

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
       

         
      
      
      
   </head>
   <body onLoad="initialize()" class="bnr-404 <?php if($row->sidebar=='Vertical'){ echo "verticalcal";}?>"> 
   <!-- Side Bar -->
            <?php
			 if($row->sidebar=='Vertical'){
			 include "includes/callmycab_sidebar.php"; }
			 ?>
              <!-- End Side Bar  -->
     <div class="jumbotron">
        
         <div class="bnr-404">
       
      <?php
	 if($row->sidebar=='Horizontal'){
			 include "includes/callmycab_horizontal.php"; }
	  include "includes/farechart.php";
	 include "includes/callmycab_header.php";
	 ?>
      <!-- Main jumbotron for a primary marketing message or call to action -->
    
            <div class="container">
            <div class="secssion1">
               <div class="row">
                  <div class="col-md-12">
                  
                     <div class="four_four">
                       <div class="four_four_inr"></div>
                      <p> Oops! Wrong way. Let's try another route. You can also reach </p>
                      <div class="four_btn"> <a href="#" class="four_button"> Book Now </a> </div>
                       <div>
                      </div>
                      </div>
                  </div>
                
                  
                 
                  
               </div>
            </div>
         </div>
      </div>
      </div>
      <!-- column1-->
      
      <!--/column1-->
      <!-- column2-->
      
      
      
      
      
      
      
      
      
      
      
      
      
     
      
      
      <!--/column1-->
      <!-- /container -->
      <!-- Bootstrap core JavaScript
         ================================================== -->
         <!--timepicker-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-timepicker.css"/>
<!-- Load jQuery JS -->
<script src="<?php echo base_url();?>assets/js/jquery-timepicker.js"></script>
<!-- Load jQuery UI Main JS -->
<script src="<?php echo base_url();?>assets/js/jquery-timepicker-min.js"></script>
<!--end timepicker-->

      <!-- Placed at the end of the document so the pages load faster -->
      <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
      <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
      <script src="<?php echo base_url();?>assets/js/ie10-viewport-bug-workaround.js"></script>
      
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css" />
        <script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
        
        
        
        
        
        
  
        
     
   </body>
</html>

