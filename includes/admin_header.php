<header  id="header-navbar">
    <div class="container" >
        <a style="background-color: #2a3133" href="<?php echo base_url();?>admin/dashboard" id="logo" class="navbar-brand">
            <img src="<?php echo base_url();?>upload/taxi_logo.png" alt="" class="normal-logo logo-white" width="100%" height="100%"/>
<!--            <img src="img/logo-black.png" alt="" class="normal-logo logo-black"/>-->
<!--            <img src="img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden"/>-->
        </a>

        <div  class="clearfix">
            <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>

            <div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
                <ul class="nav navbar-nav pull-left">
                    <li>
                        <a class="btn" id="make-small-nav">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
<!--                    <li class="dropdown hidden-xs">-->
<!--                        <a class="btn dropdown-toggle" data-toggle="dropdown">-->
<!--                            <i class="fa fa-bell"></i>-->
<!--                            <span class="count">8</span>-->
<!--                        </a>-->
<!--                        <ul class="dropdown-menu notifications-list">-->
<!--                            <li class="pointer">-->
<!--                                <div class="pointer-inner">-->
<!--                                    <div class="arrow"></div>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                            <li class="item-header">You have 6 new notifications</li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-comment"></i>-->
<!--                                    <span class="content">New comment on ‘Awesome P...</span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-plus"></i>-->
<!--                                    <span class="content">New user registration</span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-envelope"></i>-->
<!--                                    <span class="content">New Message from George</span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-shopping-cart"></i>-->
<!--                                    <span class="content">New purchase</span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-eye"></i>-->
<!--                                    <span class="content">New order</span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item-footer">-->
<!--                                <a href="#">-->
<!--                                    View all notifications-->
<!--                                </a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li class="dropdown hidden-xs">-->
<!--                        <a class="btn dropdown-toggle" data-toggle="dropdown">-->
<!--                            <i class="fa fa-envelope-o"></i>-->
<!--                            <span class="count">16</span>-->
<!--                        </a>-->
<!--                        <ul class="dropdown-menu notifications-list messages-list">-->
<!--                            <li class="pointer">-->
<!--                                <div class="pointer-inner">-->
<!--                                    <div class="arrow"></div>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                            <li class="item first-item">-->
<!--                                <a href="#">-->
<!--                                    <img src="img/samples/messages-photo-1.png" alt=""/>-->
<!--                                        <span class="content">-->
<!--                                            <span class="content-headline">-->
<!--                                                George Clooney-->
<!--                                            </span>-->
<!--                                            <span class="content-text">-->
<!--                                                Look, just because I don't be givin' no man a foot massage don't make it-->
<!--                                                right for Marsellus to throw...-->
<!--                                            </span>-->
<!--                                        </span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <img src="img/samples/messages-photo-2.png" alt=""/>-->
<!--                                        <span class="content">-->
<!--                                            <span class="content-headline">-->
<!--                                                Emma Watson-->
<!--                                            </span>-->
<!--                                            <span class="content-text">-->
<!--                                                Look, just because I don't be givin' no man a foot massage don't make it-->
<!--                                                right for Marsellus to throw...-->
<!--                                            </span>-->
<!--                                        </span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <img src="img/samples/messages-photo-3.png" alt=""/>-->
<!--                                        <span class="content">-->
<!--                                            <span class="content-headline">-->
<!--                                                Robert Downey Jr.-->
<!--                                            </span>-->
<!--                                            <span class="content-text">-->
<!--                                                Look, just because I don't be givin' no man a foot massage don't make it-->
<!--                                                right for Marsellus to throw...-->
<!--                                            </span>-->
<!--                                        </span>-->
<!--                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item-footer">-->
<!--                                <a href="#">-->
<!--                                    View all messages-->
<!--                                </a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li class="dropdown hidden-xs">-->
<!--                        <a class="btn dropdown-toggle" data-toggle="dropdown">-->
<!--                            New Item-->
<!--                            <i class="fa fa-caret-down"></i>-->
<!--                        </a>-->
<!--                        <ul class="dropdown-menu">-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-archive"></i>-->
<!--                                    New Product-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-shopping-cart"></i>-->
<!--                                    New Order-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-sitemap"></i>-->
<!--                                    New Category-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-file-text"></i>-->
<!--                                    New Page-->
<!--                                </a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li class="dropdown hidden-xs">-->
<!--                        <a class="btn dropdown-toggle" data-toggle="dropdown">-->
<!--                            English-->
<!--                            <i class="fa fa-caret-down"></i>-->
<!--                        </a>-->
<!--                        <ul class="dropdown-menu">-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    Spanish-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    German-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="item">-->
<!--                                <a href="#">-->
<!--                                    Italian-->
<!--                                </a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <span class="conveythis notranslate" translate="no"><a href="http://translation-services-usa.com/spanish.php" title="spanish translation services" class="conveythis-image conveythis-drop translate1">spanish translation services</a></span><script src="http://s1.conveythis.com/e4/javascript/e.js?conveythis_src=en"></script>-->
                </ul>
            </div>

            <div class="nav-no-collapse pull-right" id="header-nav">
                <ul class="nav navbar-nav pull-right">
                    <li class="mobile-search">
                        <a class="btn">
                            <i class="fa fa-search"></i>
                        </a>

                        <div class="drowdown-search">
                            <form role="search">
                                <div class="form-group">
                                    <input type="text" id="example1" class="form-control" placeholder="Search...">
                                    <i class="fa fa-search nav-search-icon"></i>
                                </div>
                            </form>
                        </div>

                    </li>
                    <?php
                    $sql="SELECT * FROM `adminlogin` WHERE role='admin'";
                    $qry=mysql_query($sql);
                    $data=mysql_fetch_array($qry);
                    ?>
                    <li class="dropdown profile-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url().$data['image']; ?>" alt=""/>
                            <span class="hidden-xs"><?php echo $data['username'];?></span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="profile"><i class="fa fa-user"></i>Profile</a></li>
                            <li><a href="password_change"><i class="fa fa-cog"></i>Change Password</a></li>
<!--                            <li><a href="#"><i class="fa fa-envelope-o"></i>Messages</a></li>-->
                            <li><a href="logout"><i class="fa fa-power-off"></i>Logout</a></li>
                        </ul>
                    </li>
                    <li class="hidden-xxs">
                        <a class="btn" href="logout">
                            <i class="fa fa-power-off"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>