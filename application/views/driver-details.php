<!DOCTYPE html>
<?php
$id=$_GET['id'];
$query=$this->db->query("SELECT * FROM `driver_details` INNER JOIN `cabdetails` ON cabdetails.cab_id=driver_details.car_type WHERE driver_details.id=$id");
$row = $query->row('driver_details');
?>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Driver Details - NaqilCom</title>
	
	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/bootstrap/bootstrap.min.css" />
	
	<!-- RTL support - for demo only -->
	<script src="js/demo-rtl.js"></script>	
	<!-- 
	If you need RTL support just include here RTL CSS file <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-rtl.min.css" />
	And add "rtl" class to <body> element - e.g. <body class="rtl"> 
	-->
	
	<!-- libraries -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/libs/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/libs/nanoscroller.css" />

	<!-- global styles -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/compiled/theme_styles.css" />

	<!-- this page specific styles -->
	<link rel="stylesheet" href="<?php echo base_url();?>application/views/css/libs/daterangepicker.css" type="text/css" />
  	
	<!-- Favicon -->
	<link type="image/x-icon" href="<?php echo base_url();?>upload/favicon.png" rel="shortcut icon" />

	<!-- google font libraries -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>

	<!--[if lt IE 9]>
		<script src="<?php echo base_url();?>application/views/js/html5shiv.js"></script>
		<script src="<?php echo base_url();?>application/views/js/respond.min.js"></script>
	<![endif]-->
  
  <style type="text/css">.modal-open .modal{ background:url(<?php echo base_url();?>application/views/img/transpharant.png) top left repeat;}</style>
</head>
<body>
<div class="cover"></div>
	<div id="theme-wrapper">
		<?php
		include"includes/admin_header.php";
		?>
		<div id="page-wrapper" class="container">
			<div class="row">
				<?php
				include"includes/admin_sidebar.php";
				?>
				<div id="content-wrapper">
					<div class="row" style="opacity: 1;">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-12">
									<div id="content-header" class="clearfix">
										<div class="pull-left">
											<h1>Driver Details</h1>
										</div>
                    <div class="pull-right">
                    	<ol class="breadcrumb">
												<li><a href="#">Home</a></li>
												<li class="active"><span>Driver Details</span></li>
											</ol>
                    </div>
									</div>
								</div>
							</div>
              <div class="row">
								<div class="col-lg-12">
									<div class="main-box clearfix">
                  	<div class="panel" style="margin-bottom:0px;">
                      <div class="panel-body">
                        <h2>Driver Details</h2>
                      </div>
                  	</div>
										<div class="main-box-body clearfix">
											<form action="javascript:void(0);" enctype="multipart/form-data" method="post" class="form-horizontal" id="formAddUser" name="add_user" role="form">
                      <h3><span>Driver Details</span></h3>
                      <br />
												<div class="form-group">
													<label class="col-lg-2 control-label" for="drivername">profile picture</label>
													<div id="inputDriverName" class="col-lg-10">
														<?php
														if($row->image) {
															?>
															<img  src="<?php echo base_url().'driverimages/'.$row->image; ?>" height="100" width="100">
															<?php
														}
														else{
															?>
															<img  src="<?php echo base_url() ?>upload/no-image-icon.png" height="100" width="100">
															<?php
														}
														?>
													</div>
												</div>
                      <div class="form-group">
                        <label class="col-lg-2 control-label" for="drivername">Name</label>
                        <div id="inputDriverName" class="col-lg-10">
                          <input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="drivename" id="driverName" class="form-control" value="<?php echo $row->name;?>" readonly>
                        </div>
                      </div>
						<div class="form-group">
					   <label class="col-lg-2 control-label" for="drivername">Username</label>
					   <div id="inputDriverName" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();" name="drivename" id="driverName" class="form-control" value="<?php echo $row->user_name;?>" readonly>
						</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivername">Email</label>
						<div id="inputDriverName" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="drivename" id="driverName" class="form-control" value="<?php echo $row->email;?>" readonly>
						</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivername">Gender</label>
						<div id="inputDriverName" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="drivename" id="driverName" class="form-control" value="<?php echo $row->gender;?>" readonly>
						</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivername">Date Of Birth</label>
						<div id="inputDriverName" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="drivename" id="driverName" class="form-control" value="<?php echo $row->dob;?>" readonly>
						</div>
						</div>

                      <div class="form-group">
                        <label class="col-lg-2 control-label" for="driveraddress">Address</label>
                        <div id="inputDriverAddress" class="col-lg-10">
                          <textarea rows="3" id="driverAddress" class="form-control"  readonly><?php echo $row->address;?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-2 control-label" for="driverphone">Phone NO</label>
                        <div id="inputDriverPhone" class="col-lg-10">
                          <div class="input-group">
														<span class="input-group-addon"><i class="fa fa-phone" ></i></span>
														<input type="text" id="driverPhone" class="form-control"  value="<?php echo $row->phone;?>" readonly>
													</div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-2 control-label" for="driveremail">Email Address</label>
                        <div id="inputDriverEmail" class="col-lg-10">
                          <input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="droparea" id="dropArea" class="form-control" value="<?php echo $row->email;?>" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-2 control-label" for="driverlicenseno">License NO</label>
                        <div id="inputDriverLicenseNo" class="col-lg-10">
                          <input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser(); name="licenseno" id="DriverLicenseNo" class="form-control" value="<?php echo $row->license_no;?>" readonly>
                        </div>
                      </div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivername">License Expiry Date</label>
						<div id="inputDriverName" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="drivename" id="driverName" class="form-control" value="<?php echo $row->Lieasence_Expiry_Date;?>" readonly>
						</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivername">License Plate</label>
						<div id="inputDriverName" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="drivename" id="driverName" class="form-control" value="<?php echo $row->license_plate;?>" readonly>
						</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivername">Insurance</label>
						<div id="inputDriverName" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="drivename" id="driverName" class="form-control" value="<?php echo $row->Insurance;?>" readonly>
						</div>
						</div>
                      <h3><span>Car Details</span></h3>
                      <br />
                      <div class="form-group">
                        <label class="col-lg-2 control-label" for="drivercartype">Car Type</label>
                        <div id="inputDriverCarType" class="col-lg-10">
                          <input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="cartype" id="DriverCarType" class="form-control" readonly value="<?php echo $row->cartype; ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-2 control-label" for="drivercarno">Car No</label>
                        <div id="inputDriverCarNo" class="col-lg-10">
                          <input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="carno" id="CarLicenseNo" class="form-control" readonly value="<?php echo $row->car_no;?>">
                        </div>
                      </div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivercarno">Car Model</label>
						<div id="inputDriverCarNo" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="carno" id="CarLicenseNo" class="form-control" readonly value="<?php echo $row->Car_Model;?>">
						</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivercarno">Car Make</label>
						<div id="inputDriverCarNo" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="carno" id="CarLicenseNo" class="form-control" readonly value="<?php echo $row->Car_Make;?>">
						</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label" for="drivercarno">Loading Capacity</label>
						<div id="inputDriverCarNo" class="col-lg-10">
						<input type="text" onkeyup="javascript:capitalize(this.id, this.value);" onkeydown="errorValidUser();"  name="carno" id="CarLicenseNo" class="form-control" readonly value="<?php echo $row->Seating_Capacity;?>">
						</div>
						</div>
                      <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                          <button style="display:block;" class="btn btn-success" onclick="return check_User();" id="notification-trigger-bouncyflip" type="submit">
                            <span id="category_button" class="content">SUBMIT</span>
                          </button>
                        </div>
                      </div>
                    </form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<footer class="row" id="footer-bar" style="opacity: 1;">
						<p id="footer-copyright" class="col-xs-12">
							Powered by Naqilcom. 
						</p>
					</footer>
				</div>	
			</div>
		</div>
	</div>
		
	<div id="config-tool" class="closed" style="display:none;">
		<a id="config-tool-cog">
			<i class="fa fa-cog"></i>
		</a>
		
		<div id="config-tool-options">
			<h4>Layout Options</h4>
			<ul>
				<li>
					<div class="checkbox-nice">
						<input type="checkbox" id="config-fixed-header" checked />
						<label for="config-fixed-header">
							Fixed Header
						</label>
					</div>
				</li>
				<li>
					<div class="checkbox-nice">
						<input type="checkbox" id="config-fixed-sidebar" checked />
						<label for="config-fixed-sidebar">
							Fixed Left Menu
						</label>
					</div>
				</li>
				<li>
					<div class="checkbox-nice">
						<input type="checkbox" id="config-fixed-footer" checked />
						<label for="config-fixed-footer">
							Fixed Footer
						</label>
					</div>
				</li>
				<li>
					<div class="checkbox-nice">
						<input type="checkbox" id="config-boxed-layout" />
						<label for="config-boxed-layout">
							Boxed Layout
						</label>
					</div>
				</li>
				<li>
					<div class="checkbox-nice">
						<input type="checkbox" id="config-rtl-layout" />
						<label for="config-rtl-layout">
							Right-to-Left
						</label>
					</div>
				</li>
			</ul>
			<br/>
			<h4>Skin Color</h4>
			<ul id="skin-colors" class="clearfix">
				<li>
					<a class="skin-changer" data-skin="" data-toggle="tooltip" title="Default" style="background-color: #34495e;">
					</a>
				</li>
				<li>
					<a class="skin-changer" data-skin="theme-white" data-toggle="tooltip" title="White/Green" style="background-color: #2ecc71;">
					</a>
				</li>
				<li>
					<a class="skin-changer blue-gradient" data-skin="theme-blue-gradient" data-toggle="tooltip" title="Gradient">
					</a>
				</li>
				<li>
					<a class="skin-changer" data-skin="theme-turquoise" data-toggle="tooltip" title="Green Sea" style="background-color: #1abc9c;">
					</a>
				</li>
				<li>
					<a class="skin-changer" data-skin="theme-amethyst" data-toggle="tooltip" title="Amethyst" style="background-color: #9b59b6;">
					</a>
				</li>
				<li>
					<a class="skin-changer" data-skin="theme-blue" data-toggle="tooltip" title="Blue" style="background-color: #2980b9;">
					</a>
				</li>
				<li>
					<a class="skin-changer" data-skin="theme-red" data-toggle="tooltip" title="Red" style="background-color: #e74c3c;">
					</a>
				</li>
				<li>
					<a class="skin-changer" data-skin="theme-whbl" data-toggle="tooltip" title="White/Blue" style="background-color: #3498db;">
					</a>
				</li>
			</ul>
		</div>
	</div>
	
	<!-- global scripts -->
	<script src="<?php echo base_url();?>application/views/js/demo-skin-changer.js"></script> <!-- only for demo -->
	
	<script src="<?php echo base_url();?>application/views/js/jquery.js"></script>
	<script src="<?php echo base_url();?>application/views/js/bootstrap.js"></script>
	<script src="<?php echo base_url();?>application/views/js/jquery.nanoscroller.min.js"></script>
	
	<script src="<?php echo base_url();?>application/views/js/demo.js"></script> <!-- only for demo -->
	
	<!-- this page specific scripts -->
	<script src="<?php echo base_url();?>application/views/js/moment.min.js"></script>
	<script src="<?php echo base_url();?>application/views/js/gdp-data.js"></script>
	
	<!-- theme scripts -->
	<script src="<?php echo base_url();?>application/views/js/scripts.js"></script>
	<script src="<?php echo base_url();?>application/views/js/pace.min.js"></script>
	
	<!-- this page specific inline scripts -->
	<script type="text/javascript">
		$(window).load(function() {
			$(".cover").fadeOut(2000);
		});
	$(document).ready(function() {
	  //CHARTS
	  function gd(year, day, month) {
			return new Date(year, month - 1, day).getTime();
		}
	});
	</script>
	
</body>
</html>