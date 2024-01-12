<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"><?php include "loading.php"; ?></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->
<!-- [ Mobile header ] start -->
<div class="pc-mob-header pc-header">
    <div class="pcm-logo">
        <img src="assets/images/logo/logo.png" alt="" class="logo logo-lg">
    </div>
    <div class="pcm-toolbar">
        <a href="#!" class="pc-head-link" id="mobile-collapse">
            <div class="hamburger hamburger--arrowturn">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
        </a>
        <a href="#!" class="pc-head-link" id="headerdrp-collapse">
            <i data-feather="align-right"></i>
        </a>
        <a href="#!" class="pc-head-link" id="header-collapse">
            <i data-feather="more-vertical"></i>
        </a>
    </div>
</div>
<!-- [ Mobile header ] End -->

<!-- [ navigation menu ] start -->
<nav class="pc-sidebar " style="z-index: 99 !important;">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="index.php" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="assets/images/logo/logo.png" alt="" style="width: 180px;" class="logo logo-lg">
                <img src="assets/images/logo/logo.png" alt="" style="width: 180px;" class="logo logo-sm">
            </a>
        </div>
        <div class="navbar-content pr-4">
            <ul class="pc-navbar">
                <!--li class="pc-item pc-caption">
						<label>Navigation</label>
					</li-->
                <li class="pc-item">
                    <a href="./" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">home</i></span><span class="pc-mtext">Dashboard</span></a>
                </li>
                <?php
                if ($_SESSION['user']['user_type'] <= 2) {
                ?>
                    <li class="pc-item">
                        <a href="update-stock.php" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">layers</i></span><span class="pc-mtext">Stock Management</span></a>
                    </li>
                <?php
                }
                ?>
                <li class="pc-item">
                    <a href="add-order.php" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">assignment</i></span><span class="pc-mtext">Add Order Form</span></a>
                </li>

                <?php
                if ($_SESSION['user']['user_type'] <= 2) {
                ?>
                    <li class="pc-item">
                        <a href="add-retail-order.php" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">receipt_long</i></span><span class="pc-mtext">Add Retail Order</span></a>
                    </li>
                <?php
                }
                ?>
                <!--li class="pc-item pc-caption">
						<label>Elements</label>
						<span>UI Components</span>
					</li-->

                <?php
                if ($_SESSION['user']['user_type'] <= 2) {
                ?>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">history</i></span><span class="pc-mtext">System History</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="order-history.php">Orders</a></li>
                            <li class="pc-item"><a class="pc-link" href="stock-history.php">Stock</a></li>
                            <li class="pc-item"><a class="pc-link" href="sms-history.php">SMS</a></li>
                        </ul>
                    </li>
                <?php
                } else {
                ?>
                    <li class="pc-item">
                        <a href="order-history.php" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">manage_history</i></span><span class="pc-mtext">Order History</span></a>
                    </li>
                <?php
                }
                ?>

                <?php
                if ($_SESSION['user']['user_type'] <= 2) {
                ?>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">sms</i></span><span class="pc-mtext">SMS</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="sms.php">Send SMS</a></li>
                            <li class="pc-item"><a class="pc-link" href="sms-template.php">SMS Templates</a></li>
                        </ul>
                    </li>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['user']['user_type'] == 1) {
                ?>
                    <li class="pc-item">
                        <a href="activity.php" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">bar_chart</i></span><span class="pc-mtext">Activities</span></a>
                    </li>
                <?php
                }
                ?>

                <?php
                if ($_SESSION['user']['user_type'] <= 2) {
                ?>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">widgets</i></span><span class="pc-mtext">Products</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="add-product.php">Add New Product</a></li>
                            <li class="pc-item"><a class="pc-link" href="products.php">All Products</a></li>
                            <?php
                            if ($_SESSION['user']['user_type'] == 1) {
                            ?>
                                <li class="pc-item"><a class="pc-link" href="category.php">All Categories</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </li>
                <?php
                } else {
                ?>
                    <li class="pc-item">
                        <a href="products.php" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">home</i></span><span class="pc-mtext">All Products</span></a>
                    </li>
                <?php
                }
                ?>

                <li class="pc-item pc-hasmenu">
                    <a class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">storefront</i></span><span class="pc-mtext">Shops</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="add-shop.php">Add New Shop</a></li>
                        <li class="pc-item"><a class="pc-link" href="shops.php">All Shops</a></li>
                        <?php
                        if ($_SESSION['user']['user_type'] == 1 || $_SESSION['user']['user_type'] == 2) {
                        ?>
                            <li class="pc-item"><a class="pc-link" href="city.php">Add New City</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>

                <?php
                if ($_SESSION['user']['user_type'] == 1) {
                ?>
                    <li class="pc-item pc-hasmenu">
                        <a class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">group</i></span><span class="pc-mtext">User Management</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="add-user.php">Add New User</a></li>
                            <li class="pc-item"><a class="pc-link" href="user-manage.php">All Users</a></li>
                        </ul>
                    </li>
                <?php
                }
                ?>

                <li class="pc-item pc-hasmenu">
                    <a class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">settings</i></span><span class="pc-mtext">Settings</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="settings.php">Privacy Settings</a></li>

                        <?php
                        if ($_SESSION['user']['user_type'] != 1) {
                        ?>
                            <li class="pc-item"><a class="pc-link" href="my-profile.php">My Profile</a></li>
                        <?php
                        }
                        ?>

                    </ul>
                </li>

                <!-- <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">business_center</i></span><span class="pc-mtext">Basic</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="bc_alert.html">Alert</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_button.html">Button</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_badges.html">Badges</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_breadcrumb-pagination.html">Breadcrumb & paggination</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_card.html">Cards</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_collapse.html">Collapse</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_carousel.html">Carousel</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_progress.html">Progress</a></li>
                        <li class="pc-item"><a class="pc-link" href="bc_modal.html">Modal</a></li>

                        <li class="pc-item"><a class="pc-link" href="bc_typography.html">Typography</a></li>
                    </ul>
                </li>
                <li class="pc-item">
                    <a href="icon-feather.html" class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">history_edu</i></span><span class="pc-mtext">Icons</span></a>
                </li> -->
                <!--li class="pc-item pc-caption">
						<label>Forms</label>
					</li-->
                <!-- <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">edit</i></span><span class="pc-mtext">Forms Elements</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="form_elements.html">Form Basic</a></li>
                        <li class="pc-item"><a class="pc-link" href="form2_input_group.html">Input Groups</a></li>
                        <li class="pc-item"><a class="pc-link" href="form2_checkbox.html">Checkbox</a></li>
                        <li class="pc-item"><a class="pc-link" href="form2_radio.html">Radio</a></li>
                    </ul>
                </li> -->
                <!--li class="pc-item pc-caption">
						<label>table</label>
					</li-->
                <!-- <li class="pc-item">
                    <a href="tbl_bootstrap.html" class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">table_rows</i></span><span class="pc-mtext">Bootstrap table</span></a>
                </li> -->
                <!--li class="pc-item pc-caption">
						<label>Chart & Maps</label>
						<span>Tones of readymade charts</span>
					</li-->
                <!-- <li class="pc-item">
                    <a href="chart-apex.html" class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">bubble_chart</i></span><span class="pc-mtext">Chart</span></a>
                </li>
                <li class="pc-item">
                    <a href="map-google.html" class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">my_location</i></span><span class="pc-mtext">Maps</span></a>
                </li> -->
                <!--li class="pc-item pc-caption">
						<label>Pages</label>
						<span>Redymade Pages</span>
					</li-->
                <!-- <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">https</i></span><span class="pc-mtext">Authentication</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="auth-signup.html" target="_blank">Sign up</a></li>
                        <li class="pc-item"><a class="pc-link" href="auth-signin.html" target="_blank">Sign in</a></li>
                    </ul>
                </li> -->
                <!--li class="pc-item pc-caption">
						<label>Other</label>
						<span>Extra more things</span>
					</li-->
                <!-- <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon"><i class="material-icons-two-tone">list_alt</i></span><span class="pc-mtext">Menu levels</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="#!">Menu Level 2.1</a></li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#!" class="pc-link">Menu level 2.2<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="#!">Menu level 3.1</a></li>
                                <li class="pc-item"><a class="pc-link" href="#!">Menu level 3.2</a></li>
                                <li class="pc-item pc-hasmenu">
                                    <a href="#!" class="pc-link">Menu level 3.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                                    <ul class="pc-submenu">
                                        <li class="pc-item"><a class="pc-link" href="#!">Menu level 4.1</a></li>
                                        <li class="pc-item"><a class="pc-link" href="#!">Menu level 4.2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu">
                            <a href="#!" class="pc-link">Menu level 2.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="#!">Menu level 3.1</a></li>
                                <li class="pc-item"><a class="pc-link" href="#!">Menu level 3.2</a></li>
                                <li class="pc-item pc-hasmenu">
                                    <a href="#!" class="pc-link">Menu level 3.3<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                                    <ul class="pc-submenu">
                                        <li class="pc-item"><a class="pc-link" href="#!">Menu level 4.1</a></li>
                                        <li class="pc-item"><a class="pc-link" href="#!">Menu level 4.2</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="pc-item"><a href="sample-page.html" class="pc-link "><span class="pc-micon"><i class="material-icons-two-tone">storefront</i></span><span class="pc-mtext">Sample page</span></a></li> -->

            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->
<!-- [ Header ] start -->
<header class="pc-header" style="box-shadow: rgba(0, 0, 0, 0.1) 10px 3px 10px;">
    <div class="header-wrapper">
        <div class="ml-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="material-icons-two-tone">search</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pc-h-dropdown drp-search">
                        <form class="px-3">
                            <div class="form-group mb-0 d-flex align-items-center">
                                <i data-feather="search"></i>
                                <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
                            </div>
                        </form>
                    </div>
                </li>
                <?php

                $userDetail_rs = Database::search("SELECT * FROM `user` WHERE `id` = ? ", "s", [$_SESSION['user']['id']]);

                if ($userDetail_rs->num_rows == 1) {
                    $userData = $userDetail_rs->fetch_assoc();
                } else {
                    header("location:signout.php");
                }

                ?>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="<?= $userData['profile_image'] ?>" alt="user-image" class="user-avtar">
                        <span>
                            <span class="user-name"><?php if ($userData['user_type_id'] == '1') {
                                                        echo "Director";
                                                    } else {
                                                        echo $userData['name'];
                                                    } ?></span>
                            <span class="user-desc"><?php
                                                    if ($userData['user_type_id'] == '1') {
                                                        echo "Super Admin";
                                                    } else if ($userData['user_type_id'] == '2') {
                                                        echo "Admin";
                                                    } else if ($userData['user_type_id'] == '3') {
                                                        echo "Sales Manager";
                                                    } else if ($userData['user_type_id'] == '4') {
                                                        echo "Seller";
                                                    }  ?></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pc-h-dropdown">

                        <a href="settings.php" class="dropdown-item">
                            <i class="material-icons-two-tone">chrome_reader_mode</i>
                            <span>Settings</span>
                        </a>

                        <a href="signout.php" class="dropdown-item">
                            <i class="material-icons-two-tone">chrome_reader_mode</i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</header>
<!-- [ Header ] end -->