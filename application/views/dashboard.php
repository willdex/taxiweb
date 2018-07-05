<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="google-translate-customization" content="e6d13f48b4352bb5-f08d3373b31c17a6-g7407ad622769509b-12"></meta>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Dashboard - 24/7 Taxi</title>
	
	<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/bootstrap/bootstrap.min.css" />
	<!-- RTL support - for demo only -->
	<script src="<?php echo base_url();?>application/views/js/demo-rtl.js"></script>
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
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/libs/timeline.css">
	
	<!-- Favicon -->
	<link type="image/x-icon" href="<?php echo base_url();?>upload/favicon.ico" rel="shortcut icon" />

	<!-- google font libraries -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>

	<!--[if lt IE 9]>
		<script src="<?php echo base_url();?>application/views/js/html5shiv.js"></script>
		<script src="<?php echo base_url();?>application/views/js/respond.min.js"></script>
	<![endif]-->
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
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-12">
									<div id="content-header" class="clearfix">
										<div class="pull-left">
											<ol class="breadcrumb">
												<li><a href="#">Home</a></li>
												<li class="active"><span>Dashboard</span></li>
											</ol>
											<h1>Dashboard</h1>
										</div>

										<div class="pull-right hidden-xs">
											<div class="xs-graph pull-left">
												<div class="graph-label">
													<?php
													$query = $this->db->query("Select count(id) as count From `bookingdetails` where status=9");
													$row = $query->row('bookigndetails');
													?>
													<b><i class="fa fa-car"></i> <?php echo $row->count; ?></b> Rides
												</div>
												<div class="graph-content spark-orders"></div>
											</div>

											<div class="xs-graph pull-left mrg-l-lg mrg-r-sm">
												<div class="graph-label">
													<?php
													$query = $this->db->query("SELECT SUM(final_amount) as sum FROM `bookingdetails`");
													$row = $query->row('bookingdetails');
													if($row->sum) {
														?>
														<b>ريال <?php echo $row->sum; ?></b> Earnings <!--Revenues-->
														<?php
													}else{
														?>
														<b>&dollar; 0</b> Earnings <!--Revenues-->
													<?php
													}
													?>
												</div>
												<div class="graph-content spark-revenues"></div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored emerald-bg">
										<i class="glyphicon glyphicon-user"></i>
										<?php
										$query = $this->db->query("Select count(id) as count From `bookingdetails` where status='6'");
										$row = $query->row('bookingdetails');
										?>
										<span class="headline">Driver Unavailable</span>
										<span class="value"><?php echo $row->count;?></span>
									</div>
								</div>

								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored green-bg">
										<i class="fa fa-money"></i>
										<?php
										$query = $this->db->query("SELECT SUM(final_amount) as sum FROM `bookingdetails`");
										$row = $query->row('bookingdetails');
										?>
										<span class="headline">Money Earned</span>
										<?php if($row->sum) { ?>
										<span class="value"><?php echo $row->sum;?></span>
										<?php } else { ?>
										<span class="value">0</span>
										<?php } ?>
									</div>
								</div>

								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored red-bg">
										<i class="fa fa-user"></i>
										<?php
										$query = $this->db->query("Select count(id) as count From `userdetails` where user_status='Active'");
										$row = $query->row('userdetails');
										?>
										<span class="headline">Users</span>
										<span class="value"><?php echo $row->count;?></span>
									</div>
								</div>

								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored purple-bg">
										<i class="glyphicon glyphicon-user"></i>
										<?php
										$query = $this->db->query("Select count(id) as count From `driver_details`  ");
										$row = $query->row('driver_details');
										?>
										<span class="headline">Drivers</span>
										<span class="value"><?php echo $row->count;?></span>
									</div>
								</div>
                
                <div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored purple-bg">
										<i class="fa fa-ticket"></i>
										<?php
										$query = $this->db->query("Select count(id) as count From `bookingdetails` where status=9");
										$row = $query->row('bookingdetails');
										?>
										<span class="headline">Successful Booking</span>
										<span class="value"><?php echo $row->count;?></span>
									</div>
								</div>
              	
                <div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored red-bg">
										<i class="fa fa-ticket"></i>
										<?php
										$query = $this->db->query("Select count(id) as count From `bookingdetails` where status=1");
										$row = $query->row('bookingdetails');
										?>
										<span class="headline">Pending Booking</span>
										<span class="value"><?php echo $row->count;?></span>
									</div>
								</div>
                
                <div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored green-bg">
										<i class="fa fa-ticket"></i>
										<?php
										$query = $this->db->query("Select count(id) as count From `bookingdetails` where status=4");
										$row = $query->row('bookingdetails');
										?>
										<span class="headline">User Canceled</span>
										<span class="value"><?php echo $row->count; ?></span>
									</div>
								</div>
                
                <div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored emerald-bg">
										<i class="fa fa-ticket"></i>
										<?php
										$query = $this->db->query("Select count(id) as count From `bookingdetails` where status=5");
										$row = $query->row('bookingdetails');
										?>
										<span class="headline">Driver Canceled</span>
										<span class="value"><?php echo $row->count;?></span>
									</div>
								</div>  
							</div>
						</div>
					</div>
          
          <div class="row">
			  <?php
			  $query1 = $this->db->query("SELECT count('*') FROM `userdetails` u RIGHT JOIN `bookingdetails` b ON u.id=b.user_id");
			  if($query1) {
				  ?>
								<div class="col-lg-12">
										<section id="cd-timeline" class="cd-container">
											<?php
											//$query1 = $this->db->query("SELECT * FROM `bookingdetails` ORDER by id DESC LIMIT 10");
											$query1 = $this->db->query("SELECT * FROM `userdetails` u RIGHT JOIN `bookingdetails` b ON u.id=b.user_id ORDER by b.id DESC LIMIT 10");
											?>
											<?php
											$i = 0;
											foreach ($query1->result_array('bookingdetails ') as $row1)
											{
												if ($i[0] && $i[2] && $i[4] && $i[6] && $i[8] && $i[10])
												{
												?>
												<?php
												}
												else
												{
												?>
														<div class="cd-timeline-block">
															<div class="cd-timeline-img cd-movie">
																<style>
																	.img-circle {
																		border-radius: 50%;
																		margin-top: -13px;
																		margin-left: -9px;
																	}
																</style>
																<!--													<i class="fa fa-photo"></i>-->
																<?php
																if ($row1['image']) {
																		?>
																		<i><img class="img-circle"
																				src="<?php echo base_url() . 'user_image/' . $row1['image']; ?>"
																				height="61" width="60"
																				style="margin-left:-14px;margin-top:-18px"></i>
																		<?php
																} else {
																	?>
																	<i><img class="img-circle"
																			src="<?php echo base_url() ?>upload/no-image-icon.png"
																			height="61" width="60"
																			style="margin-left:-14px;margin-top:-18px"></i>
																	<?php
																}
																?>
															</div>

															<div class="cd-timeline-content">
																<?php
																$timestamp = $row1['pickup_date_time'];
																//$timestamp ='2016-07-09 12:56:00';
																$newtime = strtotime($timestamp);

																$splitTimeStamp = explode(" ", $timestamp);
																$date = $splitTimeStamp[0];
																$time = $splitTimeStamp[1];
																//$time1=date('g:ia', $time);;
																$newDate = date("d-m-Y", strtotime($date));
																?>
																<div class="row">
																	<div class="col-lg-6">
																		<h2><?php echo date('d F,Y', strtotime($newDate)); ?></h2>
																	</div>
																	<div class="col-lg-6">
																		<!--																<p class=""><b>Pickup Form :</b><br />The Imperail Heights,<br />150ft Ring Road, <br />Rajkot.</p>-->
																		<?php
																		if ($row1['status'] == 1) {
																			?>
																			<div class="booking-status pending"><i class="fa fa-circle"></i><span> Pending</span></div>
																			</p>
																			<?php
																		} elseif ($row1['status'] == 2) {
																			?>
																			<div class="booking-status waiting"><i class="fa fa-circle"></i><span> Waiting</span></div>
																			<?php
																		} elseif ($row1['status'] == 3) {
																			?>
																			<div class="booking-status accepted"><i class="fa fa-circle"></i><span> Accepted</span></div>
																			<?php
																		} elseif ($row1['status'] == 4) { ?>
																			<div class="booking-status user-canceled"><i class="fa fa-circle"></i><span> User Cancelled</span></div>
																			<?php
																		} elseif ($row1['status'] == 5) { ?>
																			<div class="booking-status driver-canceled"><i class="fa fa-circle"></i><span> Driver Cancelled</span></div>
																			<?php
																		}
																		elseif ($row1['status'] == 6) { ?>
																			<div class="booking-status driver-unavailable"><i class="fa fa-circle"></i><span> Driver Unavailable</span></div>
																			<?php
																		}
																		elseif ($row1['status'] == 7) { ?>
																			<div class="booking-status driver-arrived"><i class="fa fa-circle"></i><span> Driver Arrived</span></div>
																			<?php
																		}
																		elseif ($row1['status'] == 8) { ?>
																			<div class="booking-status on-trip"><i class="fa fa-circle"></i><span> On Trip</span></div>
																			<?php
																		}
																		elseif ($row1['status'] == 9) { ?>
																			<div class="booking-status completed"><i class="fa fa-circle"></i><span> Completed</span></div>
																			<?php
																		}
																		?>
																	</div>
																</div>
																<div class="row">
																	<div class="col-lg-12">
																		<h4><?php echo $row1['username']; ?></h4>
																	</div>
																</div>
																<div class="row">
																	<div class="col-lg-6">
																		<!--																<p class=""><b>Pickup Form :</b><br />The Imperail Heights,<br />150ft Ring Road, <br />Rajkot.</p>-->
																		<p class="">
																			<b></b><?php echo $row1['pickup_area']; ?></b>
																		</p>
																	</div>
																	<div class="col-lg-6">
																		<p class="">
																			<b></b><?php echo $row1['drop_area']; ?></b>
																		</p>
																	</div>
																</div>
																<div class="clearfix">
																	<a class="btn btn-primary pull-right"
																	   href="<?php echo base_url(); ?>admin/view_booking_details?id=<?php echo $row1['id']; ?>">Read
																		more</a>
																</div>
																	<span
																		class="cd-date"><?php echo date('H:i', $newtime); ?></span>
															</div>
														</div>
												<?php
												}
												$i++;
											}
											?>
										</section>
								</div>
								  <div class="clearfix">
									  <a class="btn btn-primary pull-right" href="<?php echo base_url();?>admin/manage_booking">Read more</a>
								  </div>
					  <?php
					  }
					  ?>
							</div>
					
					<?php include "includes/admin-footer.php"?>
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
	<script src="<?php echo base_url();?>application/views/js/jquery.sparkline.min.js"></script>
  	<script src="<?php echo base_url();?>application/views/js/modernizr.js"></script>
	<script src="<?php echo base_url();?>application/views/js/timeline.js"></script>
	
	<!-- theme scripts -->
	<script src="<?php echo base_url();?>application/views/js/scripts.js"></script>
	<script src="<?php echo base_url();?>application/views/js/pace.min.js"></script>
	
	<?php
		$query = $this->db->query("SELECT YEAR(book_create_date_time) AS y, MONTH(book_create_date_time) AS m, count(id) AS count FROM `bookingdetails` WHERE status=9 AND YEAR(book_create_date_time) = YEAR(CURDATE()) GROUP BY y,m");
		if($query){
			foreach($query->result() as $row)
			{
				for($i=1;$i<=12;$i++){
					if($row->m==$i)
					{
						if($row->count!=0 || $row->count!=NULL){
							$ride_per_month[$i]=$row->count;
						}
						else{
							$ride_per_month[$i]=0;
						}
					}
					else{
						$ride_per_month[$i]=0;
					}
				}
			}
		}
		$push_ride_per_month=implode(',',$ride_per_month);

		$query1 = $this->db->query("SELECT YEAR(book_create_date_time) AS y, MONTH(book_create_date_time) AS m, sum(final_amount) AS sum FROM `bookingdetails` WHERE status=9 AND YEAR(book_create_date_time) = YEAR(CURDATE()) GROUP BY y,m");
		if($query1){
			foreach($query1->result() as $row1)
			{
				for($i=1;$i<=12;$i++){
					if($row1->m==$i)
					{
						if($row1->sum!=0 || $row1->sum!=NULL){
							$earning_per_month[$i]=$row1->sum;
						}
						else{
							$earning_per_month[$i]=0;
						}
					}
					else{
						if(!isset($earning_per_month[$i]))
						{
							$earning_per_month[$i]=0;
						}
					}
				}
			}
		}
		$push_earning_per_month=implode(',',$earning_per_month);
	?>

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
		/* SPARKLINE - graph in header */
		//var orderValues = [10,8,5,7,4,4,3,8,0,7,10,6];
		var orderValues = [<?php echo $push_ride_per_month; ?>];
		$('.spark-orders').sparkline(orderValues, {
			type: 'bar', 
			barColor: '#ced9e2',
			height: 25,
			barWidth: 6
		});

		//var revenuesValues = [8,3,2,6,4,9,1,10,8,2,5,8];
		var revenuesValues = [<?php echo $push_earning_per_month; ?>];
		$('.spark-revenues').sparkline(revenuesValues, {
			type: 'bar', 
			barColor: '#ced9e2',
			height: 25,
			barWidth: 6
		});
		
	});
	</script>
	
</body>
</html>
