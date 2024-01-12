<?php


require "util/userStatus.php";

if (User::is_allow()) {

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Nsonic</title>

        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="author" content="Nsonic">

        <!-- Favicon icon -->
        <link rel="icon" href="assets/images/favi/favi.png" type="image/x-icon">

        <!-- font css -->
        <link rel="stylesheet" href="assets/fonts/feather.css">
        <link rel="stylesheet" href="assets/fonts/fontawesome.css">
        <link rel="stylesheet" href="assets/fonts/material.css">

        <!-- vendor css -->
        <link rel="stylesheet" href="assets/css/templateStyle.css" id="main-style-link" />
        <link rel="stylesheet" href="assets/css/style.css" />

        <!-- Include Chosen CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

        <!-- Carousel -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <style>
            .col-center {
                margin: 0 auto;
                float: none !important;
            }

            .carousel {
                padding: 0 70px;
            }

            .carousel .carousel-item {
                color: #999;
                font-size: 14px;
                text-align: center;
                overflow: hidden;
                min-height: 290px;
            }

            .carousel .carousel-item .img-box {
                width: 135px;
                height: 135px;
                margin: 0 auto;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 50%;
            }

            .carousel .img-box img {
                width: 100%;
                height: 100%;
                display: block;
                border-radius: 50%;
            }

            .carousel .testimonial {
                padding: 30px 0 10px;
            }

            .carousel .overview {
                font-style: italic;
            }

            .carousel .overview b {
                text-transform: uppercase;
                color: #7AA641;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 40px;
                height: 40px;
                margin-top: -20px;
                top: 50%;
                background: none;
            }

            .carousel-control-prev i,
            .carousel-control-next i {
                font-size: 40px;
                line-height: 42px;
                position: absolute;
                display: inline-block;
                color: rgba(0, 0, 0, 0.8);
                text-shadow: 0 3px 3px #e6e6e6, 0 0 0 #000;
            }

            .carousel-indicators {
                bottom: 0px;
            }

            .carousel-indicators li,
            .carousel-indicators li.active {
                width: 12px;
                height: 12px;
                margin: 1px 3px;
                border-radius: 50%;
                border: none;
            }

            .carousel-indicators li {
                background: #999;
                border-color: transparent;
                box-shadow: inset 0 2px 1px rgba(0, 0, 0, 0.2);
            }

            .carousel-indicators li.active {
                background: #555;
                box-shadow: inset 0 2px 1px rgba(0, 0, 0, 0.2);
            }

            .pc-container {
                background-image: url('assets/images/back-ground/blue-half.png');
                background-repeat: no-repeat;
                background-position: top;
                /* background-size: cover; */
            }
        </style>

    </head>

    <body class="">

        <!-- [ Header ] start -->
        <?php include "include/header.php"; ?>
        <!-- [ Header ] end -->

        <!-- [ Main Content ] start -->
        <div class="pc-container">
            <div class="pcoded-content">

                <div id="modal-1" class="modal-1 show-modal ">

                </div>

                <!-- [ breadcrumb ] start -->
                <!-- // add after .pcoded-content div
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Dashboard sale</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item">Dashboard sale</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> -->
                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->
                <div class="row px-0 px-md-4">
                    <div class="col-12 mb-3 px-2">
                        <h1 class="text-white">Shops</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-3 mb-2">
                                <select id="province" class="form-control" onchange="changeResult('shop');loadDistricts();">
                                    <option value="0">Select Province</option>
                                    <?php
                                    $province_rs = Database::search("SELECT * FROM `province` ORDER BY `name` ASC");

                                    while ($province = $province_rs->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $province['id'] ?>">
                                            <?= $province['name'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-2">
                                <select id="district" class="form-control" onchange="loadCities();changeResult('shop');">
                                    <option value="0">Select District</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-2">
                                <select id="city" class="form-control" onchange="changeResult('shop');">
                                    <option value="0">Select City</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-2">
                                <input type="text" class="form-control" id="search" onkeyup="changeResult('shop');" placeholder="Search">
                            </div>

                        </div>
                    </div>

                    <?php


                    if ($_SESSION['user']['user_type'] > 2) {
                        $shop_rs = Database::search("SELECT *,`shop`.`id` AS `id`,`shop`.`name` AS `shop`, `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province` FROM `shop`  INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id` WHERE `shop`.`id`!='0' AND `status_id` = '1' ORDER BY  `shop`.`date_time` DESC ");
                    } else {
                        $shop_rs = Database::search("SELECT *,`shop`.`id` AS `id`,`shop`.`name` AS `shop`, `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province` FROM `shop`  INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id` WHERE `shop`.`id`!='0' ORDER BY  `shop`.`date_time` DESC ");
                    }

                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        <div class="row">
                            <div class="col-12 d-none d-md-block">
                                <span class="text-white f-w-300">Showing <?= $shop_rs->num_rows ?> of <?= $shop_rs->num_rows ?> entries</span>
                            </div>
                        </div>

                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none d-md-table-cell">Image</th>
                                    <th>Shop</th>
                                    <th class="d-none d-md-table-cell text-center">Last Invoice Date</th>
                                    <th class="d-none d-md-table-cell">Address</th>
                                    <?php
                                    if ($_SESSION['user']['user_type'] <= 2) {
                                    ?>
                                        <th class="d-none d-md-table-cell text-center">Status</th>
                                    <?php
                                    }
                                    ?>
                                    <th class=" text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $x = 1;
                                while ($shop = $shop_rs->fetch_assoc()) {
                                    $order_rs = Database::search("SELECT * FROM `invoice` WHERE `shop_id` = ? ORDER BY `date_time` DESC ", "s", [$shop['id']]);
                                ?>

                                    <tr>
                                        <td>
                                            <?= $x ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <img src="<?= $shop['image'] ?>" class="table-main-image">
                                        </td>
                                        <td>
                                            <span class="text-truncate-1"><?= $shop['shop'] ?></span>
                                        </td>
                                        <td class="d-none d-md-table-cell text-center">
                                            <?php
                                            if ($order_rs->num_rows > 0) {
                                                $invoice = $order_rs->fetch_assoc();
                                            ?>
                                                <span><?= date('Y-m-d', strtotime($invoice['date_time'])) . " (" . $invoice['order_id'] . ")" ?></span>
                                            <?php
                                            } else {
                                            ?>
                                                <span>No Order yet</span>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="text-truncate-1"><?= $shop['address'] . ", " . $shop['city'] . ", " . $shop['district'] . " district, " . $shop['province'] . " province." ?></span>
                                        </td>
                                        <?php
                                        if ($_SESSION['user']['user_type'] <= 2) {
                                        ?>
                                            <td class="d-none d-md-table-cell text-center" id="status<?= $shop['id'] ?>">
                                                <?= $shop['status_id'] == 1 ? '<div class="status status-active mx-auto">Active</div>' : '<div class="status status-deactive mx-auto">Deactive</div>' ?>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                        <td class=" text-center" style="min-width: 106px;">
                                            <?php
                                            if ($_SESSION['user']['user_type'] <= 2) {
                                            ?>
                                                <div class="check-box2 p-2 d-inline-block mr-1 z-0">
                                                    <input class="z-0" id="toggleStatus" onchange="changeStatus('Shop',<?= $shop['id'] ?>);" type="checkbox" <?= $shop['status_id'] == 1 ? "checked" : "" ?>>
                                                </div>
                                                <img class="mr-1 action-icon" onclick="viewModal('<?= $shop['id'] ?>','shop');" src="assets/images/icons/view.png">
                                                <a href="update-shop.php?id=<?= $shop['id'] ?>" target="_blank"><img class="action-icon" src="assets/images/icons/edit.png"></a>
                                            <?php
                                            } else {
                                            ?>
                                                <img class="mr-1 action-icon" onclick="viewModal('<?= $shop['id'] ?>','shop');" src="assets/images/icons/view.png">
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                <?php

                                    $x++;
                                }


                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
        <!-- [ Main Content ] end -->


        <?php require_once('include/footer.php'); ?>
    </body>

    </html>
<?php
}
?>