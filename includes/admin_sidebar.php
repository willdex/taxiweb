<div id="nav-col">
    <?php
    $sql="SELECT * FROM `adminlogin` WHERE role='admin'";
    $qry=mysql_query($sql);
    $data=mysql_fetch_array($qry);
    ?>
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div id="user-left-box" class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">
                <img alt="" src="<?php echo base_url().$data['image']; ?>" />
                <div class="user-box">
                                    <span class="name">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <?php echo $data['username'];?>
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="profile"><i class="fa fa-user"></i>Profile</a></li>
                                            <li><a href="password_change"><i class="fa fa-cog"></i>Change Password</a></li>
                                            <li><a href="logout"><i class="fa fa-power-off"></i>Logout</a></li>
                                        </ul>
                                    </span>
                                    <span class="status">
                                        <i class="fa fa-circle"></i> Online
                                    </span>
                </div>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <li class="nav-header nav-header-first hidden-sm hidden-xs">
                        Navigation
                    </li>
                    <?php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $url=base_url().'admin/dashboard';
                    if($actual_link==$url) {
                        ?>
                        <li class="active">
                            <a href="<?php echo base_url(); ?>admin/dashboard">
                                <i class="fa fa-dashboard"></i>
                                <span>Dashboard</span>
                                <span class="label label-primary label-circle pull-right"></span>
                            </a>
                        </li>
                        <?php
                    }
                    else {
                        ?>
                        <li>
                            <a href="<?php echo base_url(); ?>admin/dashboard">
                                <i class="fa fa-dashboard"></i>
                                <span>Dashboard</span>
                                <span class="label label-primary label-circle pull-right"></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $url7=base_url().'admin/manage_user';
                    $url8=base_url().'admin/manage_user?flag=yes';
                    if($actual_link==$url7) {
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-user"></i>
                                <span>Manage User</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_user'">
                                        All Users
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_user?flag=yes'">
                                        Flagged Users
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    else if($actual_link==$url8){
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-user"></i>
                                <span>Manage User</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_user'">
                                        All Users
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_user?flag=yes'">
                                        Flagged Users
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    else {
                        ?>
                        <li>
                            <!--onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'"-->
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-ticket"></i>
                                <span>Manage Users</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: none;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_user'">
                                        All Users
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_user?flag=yes'">
                                        Flagged Users
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $url1=base_url().'admin/manage_booking?status_code=pending';
                    $url2=base_url().'admin/manage_booking?status_code=completed';
                    $url3=base_url().'admin/manage_booking?status_code=user-cancelled';
                    $url4=base_url().'admin/manage_booking?status_code=driver-unavailable';
                    $url5=base_url().'admin/manage_booking';
                    if($actual_link==$url5) {
                    ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-ticket"></i>
                                <span>Manage Booking</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                 <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'">
                                        All Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=pending'">
                                        Pending Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=completed'">
                                        Completed Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=user-cancelled'">
                                       User Cancelled Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=driver-unavailable'">
                                        Driver Cancelled Booking
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php    
                    }
                    else if($actual_link==$url1) {
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-ticket"></i>
                                <span>Manage Booking</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'">
                                        All Booking
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=pending'">
                                        Pending Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=completed'">
                                        Completed Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=user-cancelled'">
                                       User Cancelled Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=driver-unavailable'">
                                        Driver Cancelled Booking
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    else if($actual_link==$url2){
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-ticket"></i>
                                <span>Manage Booking</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'">
                                        All Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=pending'">
                                        Pending Booking
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=completed'">
                                        Completed Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=user-cancelled'">
                                        User Cancelled Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=driver-unavailable'">
                                        Driver Cancelled Booking
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    else if($actual_link==$url3){
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-ticket"></i>
                                <span>Manage Booking</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'">
                                        All Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=pending'">
                                        Pending Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=completed'">
                                        Completed Booking
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=user-cancelled'">
                                        User Cancelled Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=driver-unavailable'">
                                        Driver Cancelled Booking
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    else if($actual_link==$url4){
                    ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-ticket"></i>
                                <span>Manage Booking</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'">
                                        All Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=pending'">
                                        Pending Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=completed'">
                                        Completed Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=user-cancelled'">
                                        User Cancelled Booking
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=driver-unavailable'">
                                        Driver Cancelled Booking
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php
                    }
                    else {
                        ?>
                        <li>
                            <!--onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'"-->
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-ticket"></i>
                                <span>Manage Booking</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: none;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'">
                                        All Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=pending'">
                                        Pending Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=completed'">
                                        Completed Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=user-cancelled'">
                                        User Cancelled Booking
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking?status_code=driver-unavailable'">
                                        Driver Cancelled Booking
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $url5=base_url().'admin/manage_driver';
                    $url6=base_url().'admin/manage_driver?flag=yes';
                    if($actual_link==$url5) {
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-user"></i>
                                <span>Manage Driver</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_driver'">
                                        All Drivers
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_driver?flag=yes'">
                                        Flagged Drivers
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    else if($actual_link==$url6){
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-user"></i>
                                <span>Manage Driver</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: block;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_driver'">
                                        All Drivers
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_driver?flag=yes'">
                                        Flagged Drivers
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    else {
                        ?>
                        <li>
                            <!--onclick="window.location.href='<?php echo base_url(); ?>admin/manage_booking'"-->
                            <a href="javascript:void(0);" class="dropdown-toggle">
                                <i class="fa fa-user"></i>
                                <span>Manage Driver</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu" style="display: none;">
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_driver'">
                                        All Drivers
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onclick="window.location.href='<?php echo base_url(); ?>admin/manage_driver?flag=yes'">
                                        Flagged Drivers
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    $url=base_url().'admin/manage_car_type';
                    if($actual_link==$url)
                    {
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url(); ?>admin/manage_car_type'">

                                <i class="fa fa-taxi"></i>
                                <span>Manage Car Type</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                        </li>
                        <?php
                    }
                    else {
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url(); ?>admin/manage_car_type'">

                                <i class="fa fa-taxi"></i>
                            <span>Manage Car Type
</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                        </li>
                        <?php
                    }
                    $url=base_url().'admin/manage_time_type';
                    if($actual_link==$url)
                    {
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url(); ?>admin/manage_time_type'">

                                <i class="fa fa-clock-o"></i>
                                <span>Manage Day Time</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                        </li>
                        <?php
                    }
                    else {
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url(); ?>admin/manage_time_type'">

                                <i class="fa fa-clock-o"></i>
                            <span>Manage Day Time
</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    $url=base_url().'admin/manage_delay_reasons';
                    if($actual_link==$url)
                    {
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url();?>admin/manage_delay_reasons'">

                                <i class="fa fa-cog"></i>
                                <span>Manage Delay Reasons</span>
                            </a>
                        </li>
                        <?php
                    }
                    else {
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url();?>admin/manage_delay_reasons'">

                                <i class="fa fa-cog"></i>
                                <span>Manage Delay Reasons</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    $url=base_url().'admin/update_settings';
                    if($actual_link==$url)
                    {
                        ?>
                        <li class="active">
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url();?>admin/update_settings'">

                                <i class="fa fa-cog"></i>
                                <span>Settings</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                        </li>
                        <?php
                    }
                    else {
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-toggle"
                               onclick="window.location.href='<?php echo base_url();?>admin/update_settings'">

                                <i class="fa fa-cog"></i>
                            <span>Settings
</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </section>
    <div id="nav-col-submenu"></div>
</div>