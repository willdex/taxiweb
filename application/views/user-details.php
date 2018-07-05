<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>User Details - 24/7 Taxi</title>
	
	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/bootstrap/bootstrap.min.css" />

  <!-- custome stylesheet -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/style.css" />

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
  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>application/views/css/libs/timeline.css">
	
	<!-- Favicon -->
	<link type="image/x-icon" href="<?php echo base_url();?>upload/favicon.ico" rel="shortcut icon"/>

	<!-- google font libraries -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

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
									<div class="clearfix" id="content-header">
										<div class="pull-left">
											<h1>User Details</h1>
										</div>
                    <div class="pull-right">
                    	 <ol class="breadcrumb rightside">
                        <li><a href="#">Home</a></li>
                        <li><a href="manage-user.html">Users</a></li>
                        <li class="active"><span>User Details</span></li>
                      </ol>
                    </div>
									</div>
								</div>
							</div>
                            <?php
                            $id=$_GET['id'];
                            $query=$this->db->query("SELECT * FROM `userdetails` WHERE id=$id");
                            $row = $query->row('userdetails');
                            ?>
							<div class="row" id="user-profile">
								<div class="col-lg-3 col-md-4 col-sm-4">
									<div class="main-box clearfix">
										<header class="main-box-header clearfix">
											<h2><?php echo $row->name;?></h2>
										</header>

										<div class="main-box-body clearfix">
											<div class="profile-status">
												<i class="fa fa-circle"></i> <?php echo $row->user_status;?>
											</div>
                                            <?php
                                            if($row->image) {
                                            ?>
                                                    <img src="<?php echo base_url() . 'user_image/' . $row->image; ?>"
                                                         alt="" class="profile-img img-responsive center-block"/>
                                            <?php
                                            }else{
                                                ?>
                                                <img src="<?php echo base_url()?>upload/no-image-icon.png"
                                                     alt="" class="profile-img img-responsive center-block"/>
                                            <?php
                                            }
                                            ?>
											<div class="profile-label">
												<span class="label label-danger"><?php echo $row->username;?></span>
											</div>
											<!--
											<div class="profile-stars">
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
												<i class="fa fa-star-o"></i>
												<span>Super User</span>
											</div>
											-->
                      <br />
											<div class="profile-since">
                                                <?php echo $row->email;?>
											</div>

                      <div class="profile-stars">
                          <?php
                          $query1 = $this->db->query("SELECT SUM(final_amount) as sum FROM `bookingdetails`  WHERE user_id=$id");
                          $row1 = $query1->row('bookingdetails');
                          if($row1->sum) {
                              ?>
                              <span>Total Spend : </span> <b><?php echo $row1->sum; ?></b>
                              <?php
                          }else{
                          ?>
                              <span>Total Spend : </span> <b>0</b>
                          <?php
                          }
                          ?>
											</div>

											<div class="profile-details">
												<ul class="fa-ul">
													<li><i class="fa-li fa fa-mobile"></i>Mobile No: <span><?php echo $row->mobile;?></span></li>
													<li><i class="fa-li fa fa-users"></i>Gender: <span><?php echo $row->gender;?></span></li>
                                                    <li><i class="fa-li fa fa-users"></i>Date of Birth: <span><?php echo $row->dob;?></span></li>
                          <!--
													<li><i class="fa-li fa fa-tasks"></i>Tasks done: <span>1024</span></li>
                          -->
												</ul>
											</div>
											<!--
											<div class="profile-message-btn center-block text-center">
												<a href="#" class="btn btn-success">
													<i class="fa fa-envelope"></i>
													Send message
												</a>
											</div>
                      -->
										</div>

									</div>
								</div>
                                <?php
                                $id=$_GET['id'];
                                $query1 = $this->db->query("SELECT * FROM `userdetails` u RIGHT JOIN `bookingdetails` b ON u.id=b.user_id WHERE u.id=$id");
                                $result= $query1->result_array('bookingdetails');
                                if($result) {
                                    ?>
                                    <div class="col-lg-9 col-md-8 col-sm-8">
                                        <div class="main-box clearfix">
                                            <div class="tabs-wrapper profile-tabs">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab-newsfeed" data-toggle="tab">Booked
                                                            Cabs</a></li>
                                                    <li><a href="#tab-activity" data-toggle="tab">Cancelled</a></li>
                                                </ul>

                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="tab-newsfeed">
                                                        <div class="row">
                                                            <div class="col-lg-12">

                                                                <section id="cd-timeline" class="cd-container">
                                                                    <?php
                                                                    $id = $_GET['id'];
                                                                    //$query1 = $this->db->query("SELECT * FROM `bookingdetails` ORDER by id DESC LIMIT 10");
                                                                    $query1 = $this->db->query("SELECT * FROM `userdetails` u RIGHT JOIN `bookingdetails` b ON u.id=b.user_id WHERE u.id=$id AND b.status !=4 AND b.status!=5 AND b.status!=6 ORDER by b.id DESC LIMIT 10");
                                                                    $result = $query1->result_array('bookingdetails');
                                                                    if ($result) {
                                                                        $i = 0;
                                                                        foreach ($query1->result_array('bookingdetails') as $row1) {
                                                                            if ($i[0] && $i[2] && $i[4] && $i[6] && $i[8] && $i[10]) {
                                                                            } else {
                                                                                ?>
                                                                                <div class="cd-timeline-block">
                                                                                    <div
                                                                                        class="cd-timeline-img cd-movie">
                                                                                        <style>
                                                                                            .img-circle {
                                                                                                border-radius: 50%;
                                                                                                margin-top: -13px;
                                                                                                margin-left: -9px;
                                                                                            }
                                                                                        </style>
                                                                                        <!--													<i class="fa fa-photo"></i>-->
                                                                                        <?php if ($row1['image']) { ?>
                                                                                                <i><img class="img-circle"
                                                                                                        src=" <?php echo base_url() . 'user_image/' . $row1['image']; ?>"
                                                                                                        height="50"
                                                                                                        width="50"></i>
                                                                                                <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <i><img class="img-circle"
                                                                                                    src="<?php echo base_url() ?>upload/no-image-icon.png"
                                                                                                    height="50"
                                                                                                    width="50"></i>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>

                                                                                    <div class="cd-timeline-content">
                                                                                        <?php
                                                                                        $timestamp = $row1['pickup_date_time'];
                                                                                        //$timestamp ='2016-07-09 12:56:00';
                                                                                        $splitTimeStamp = explode(" ", $timestamp);
                                                                                        $date = $splitTimeStamp[0];
                                                                                        $time = $splitTimeStamp[1];
                                                                                        $newDate = date("d-m-Y", strtotime($date)); ?>
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6">
                                                                                                <h2><?php echo date('d  F,Y', strtotime($newDate)); ?></h2>
                                                                                            </div>
                                                                                            <div class="col-lg-6">
                                                                                                <!--																<p class=""><b>Pickup Form :</b><br />The Imperail Heights,<br />150ft Ring Road, <br />Rajkot.</p>-->
                                                                                                <?php
                                                                                                if ($row1['status'] == 1) {
                                                                                                    ?>
                                                                                                    <div
                                                                                                        class="booking-status pending">
                                                                                                        <i class="fa fa-circle"></i><span> Pending</span>
                                                                                                    </div>
                                                                                                    </p>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 2) {
                                                                                                    ?>
                                                                                                    <div
                                                                                                        class="booking-status waiting">
                                                                                                        <i class="fa fa-circle"></i><span> Waiting</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 3) {
                                                                                                    ?>
                                                                                                    <div
                                                                                                        class="booking-status accepted">
                                                                                                        <i class="fa fa-circle"></i><span> Accepted</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 4) { ?>
                                                                                                    <div
                                                                                                        class="booking-status user-canceled">
                                                                                                        <i class="fa fa-circle"></i><span> User Cancelled</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 5) { ?>
                                                                                                    <div
                                                                                                        class="booking-status driver-canceled">
                                                                                                        <i class="fa fa-circle"></i><span> Driver Cancelled</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 6) { ?>
                                                                                                    <div
                                                                                                        class="booking-status driver-unavailable">
                                                                                                        <i class="fa fa-circle"></i><span> Driver Unavailable</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 7) { ?>
                                                                                                    <div
                                                                                                        class="booking-status driver-arrived">
                                                                                                        <i class="fa fa-circle"></i><span> Driver Arrived</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 8) { ?>
                                                                                                    <div
                                                                                                        class="booking-status on-trip">
                                                                                                        <i class="fa fa-circle"></i><span> On Trip</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 9) { ?>
                                                                                                    <div
                                                                                                        class="booking-status completed">
                                                                                                        <i class="fa fa-circle"></i><span> Completed</span>
                                                                                                    </div>
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
                                                                                    class="cd-date"><?php echo $time; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                </section>

                                                            </div>
                                                            <div class="clearfix">
                                                                <a class="btn btn-primary pull-right"
                                                                   href="<?php echo base_url(); ?>admin/manage_booking?user_id=<?php echo $this->input->get('id'); ?>">Read
                                                                    more</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab-activity">
                                                        <div class="row">
                                                            <div class="col-lg-12">

                                                                <section id="cd-timeline" class="cd-container">
                                                                    <?php
                                                                    $id = $_GET['id'];
                                                                    //$query1 = $this->db->query("SELECT * FROM `bookingdetails` ORDER by id DESC LIMIT 10");
                                                                    $query1 = $this->db->query("SELECT * FROM `userdetails` u RIGHT JOIN `bookingdetails` b ON u.id=b.user_id WHERE u.id=$id AND b.status!=1 AND b.status!=2 AND b.status!=3 AND b.status!=7 AND b.status!=8 AND b.status!=9 ORDER by b.id DESC LIMIT 10");
                                                                    $result = $query1->result_array('bookingdetails');
                                                                    if ($result) {
                                                                        $i = 0;
                                                                        foreach ($query1->result_array('bookingdetails') as $row1) {
                                                                            if ($i[0] && $i[2] && $i[4] && $i[6] && $i[8] && $i[10]) {
                                                                            } else {
                                                                                ?>
                                                                                <div class="cd-timeline-block">
                                                                                    <div
                                                                                        class="cd-timeline-img cd-movie">
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
                                                                                                        src="<?php echo base_url().'user_image/'.$row1['image']; ?>"
                                                                                                        height="50"
                                                                                                        width="50"></i>
                                                                                                <?php
                                                                                        } else {
                                                                                            ?>
                                                                                            <i><img class="img-circle"
                                                                                                    src="<?php echo base_url() ?>upload/no-image-icon.png"
                                                                                                    height="50"
                                                                                                    width="50"></i>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>

                                                                                    <div class="cd-timeline-content">
                                                                                        <?php
                                                                                        $timestamp = $row1['pickup_date_time'];
                                                                                        //$timestamp ='2016-07-09 12:56:00';
                                                                                        $splitTimeStamp = explode(" ", $timestamp);
                                                                                        $date = $splitTimeStamp[0];
                                                                                        $time = $splitTimeStamp[1];
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
                                                                                                    <div
                                                                                                        class="booking-status pending">
                                                                                                        <i class="fa fa-circle"></i><span> Pending</span>
                                                                                                    </div>
                                                                                                    </p>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 2) {
                                                                                                    ?>
                                                                                                    <div
                                                                                                        class="booking-status waiting">
                                                                                                        <i class="fa fa-circle"></i><span> Waiting</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 3) {
                                                                                                    ?>
                                                                                                    <div
                                                                                                        class="booking-status accepted">
                                                                                                        <i class="fa fa-circle"></i><span> Accepted</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 4) { ?>
                                                                                                    <div
                                                                                                        class="booking-status user-canceled">
                                                                                                        <i class="fa fa-circle"></i><span> User Cancelled</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 5) { ?>
                                                                                                    <div
                                                                                                        class="booking-status driver-canceled">
                                                                                                        <i class="fa fa-circle"></i><span> Driver Cancelled</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 6) { ?>
                                                                                                    <div
                                                                                                        class="booking-status driver-unavailable">
                                                                                                        <i class="fa fa-circle"></i><span> Driver Unavailable</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 7) { ?>
                                                                                                    <div
                                                                                                        class="booking-status driver-arrived">
                                                                                                        <i class="fa fa-circle"></i><span> Driver Arrived</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 8) { ?>
                                                                                                    <div
                                                                                                        class="booking-status on-trip">
                                                                                                        <i class="fa fa-circle"></i><span> On Trip</span>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } elseif ($row1['status'] == 9) { ?>
                                                                                                    <div
                                                                                                        class="booking-status completed">
                                                                                                        <i class="fa fa-circle"></i><span> Completed</span>
                                                                                                    </div>
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
                                                                                    class="cd-date"><?php echo $time; ?></span>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                </section>

                                                            </div>
                                                            <div class="clearfix">
                                                                <!--<a class="btn btn-primary pull-right" href="<?php echo base_url(); ?>admin/cancelpointview?id=<?php echo $row1['id']; ?>">Read more</a>-->
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php
                                }
                                else{
                                ?>
                                <div class="col-lg-9 col-md-8 col-sm-8">
                                    <div class="main-box clearfix">
                                        <div class="tabs-wrapper profile-tabs">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab-newsfeed" data-toggle="tab">Booked
                                                        Cabs</a></li>
                                                <li><a href="#tab-activity" data-toggle="tab">Cancelled</a></li>
                                            </ul>

                                            <div class="tab-content">
                                                <div class="tab-pane fade in active" id="tab-newsfeed">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            No Data
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade in" id="tab-activity">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            No Data
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
							</div>

						</div>
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
	<script src="<?php echo base_url();?>application/views/js/jquery.slimscroll.min.js"></script>
	<script src="<?php echo base_url();?>application/views/js/modernizr.js"></script>
	<script src="<?php echo base_url();?>application/views/js/timeline.js"></script>

	<!-- theme scripts -->
	<script src="<?php echo base_url();?>application/views/js/scripts.js"></script>
	<script src="<?php echo base_url();?>application/views/js/pace.min.js"></script>
	
	<!-- this page specific inline scripts -->
	<script type="text/javascript">
        $(window).load(function() {
            $(".cover").fadeOut(2000);
        });
	    // When the window has finished loading create our google map below
	    google.maps.event.addDomListener(window, 'load', init);

	    function init() {
	    	var latlng = new google.maps.LatLng(40.763986, -73.958674);

	        //APPLE MAP
	        var mapOptionsApple = {
	            zoom: 12,
	            scrollwheel: false,
	            center: latlng,

	            // How you would like to style the map.
	            // This is where you would paste any style found on Snazzy Maps.
	            styles: [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.business","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]}]
	        };

	        var mapElementApple = document.getElementById('map-apple');

	        // Create the Google Map using out element and options defined above
	        var mapApple = new google.maps.Map(mapElementApple, mapOptionsApple);

	        var markerApple = new google.maps.Marker({
	    		position: latlng,
	    		map: mapApple
	    	});
	    }

		$(document).ready(function() {
			$('.conversation-inner').slimScroll({
		        height: '340px'
		    });
		});
		
	</script>
	
</body>
</html>