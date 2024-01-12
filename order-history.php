<?php


require "util/userStatus.php";

if (User::is_allow()) {

    function getRelativeTime($publishDate)
    {
        $diff = time() - strtotime($publishDate);
        $intervals = array(
            31536000 => 'year',
            2592000 => 'month',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
        );

        foreach ($intervals as $secs => $label) {
            $div = $diff / $secs;

            if (date('Y-m-d', strtotime($publishDate)) != date("Y-m-d")) {
                return date('h:i A', strtotime($publishDate));
            } else {
                if ($div >= 1) {

                    $timeAgo = round($div);
                    return $timeAgo . ' ' . $label . ($timeAgo > 1 ? 's' : '') . ' ago';
                }
            }
        }

        return 'just now';
    }
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

        <style>
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

                <div id="modal-1" class="modal-1 show-modal">
                    <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content">

                    </div>
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
                        <h1 class="text-white">Orders History</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-3 mb-2">
                                <?php

                                if ($_SESSION['user']['user_type'] == '4') {
                                ?>
                                    <select id="user" class="form-control" disabled>
                                        <option value="<?= $_SESSION['user']['id'] ?>">Your Orders</option>
                                    </select>
                                <?php
                                } else {
                                ?>
                                    <select id="user" class="form-control" onchange="changeResult('orderHistory');">
                                        <option value="">All Users</option>
                                        <?php
                                        $shop_rs = Database::search("SELECT * FROM `user`");

                                        while ($shop = $shop_rs->fetch_assoc()) {
                                        ?>
                                            <option value="<?= $shop['id'] ?>">
                                                <?= $shop['name'] ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                }


                                ?>


                            </div>

                            <div class="col-12 col-md-3 mb-2">
                                <select id="shop" class="form-control" onchange="changeResult('orderHistory');">
                                    <option value="">All Shops</option>
                                    <option value="0">
                                        Retail Order
                                    </option>
                                    <?php
                                    $shop_rs = Database::search("SELECT * FROM `shop` WHERE `id` != '0' ORDER BY `name` ASC");

                                    while ($shop = $shop_rs->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $shop['id'] ?>">
                                            <?= $shop['name'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-2">
                                <select id="district" class="form-control" onchange="changeResult('orderHistory');loadCities();">
                                    <option value="0">Select District</option>
                                    <?php
                                    $send_id_rs = Database::search("SELECT * FROM `district` ORDER BY `name` ASC");

                                    while ($send_id = $send_id_rs->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $send_id['id'] ?>">
                                            <?= $send_id['name'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-2">
                                <select id="city" class="form-control" onchange="changeResult('orderHistory');">
                                    <option value="0">Select City</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-2">
                                <select id="product" class="form-control" onchange="changeResult('orderHistory');">
                                    <option value="0">All Products</option>
                                    <?php
                                    $category_rs = Database::search("SELECT * FROM `category` ORDER BY `name`");

                                    while ($category = $category_rs->fetch_assoc()) {
                                    ?>
                                        <optgroup label="<?= $category['name'] ?>">

                                            <?php
                                            $sub_category_rs = Database::search("SELECT * FROM `sub_category` WHERE `category_id` = ? ORDER BY `name`", "s", [$category['id']]);

                                            if ($sub_category_rs->num_rows > 0) {

                                                while ($sub_category = $sub_category_rs->fetch_assoc()) {
                                            ?>

                                        <optgroup class="text-black-50" label="&nbsp;&nbsp;&nbsp;&nbsp;<?= $sub_category['name'] ?>">
                                            <?php
                                                    $product_rs = Database::search("SELECT * FROM `product` WHERE `category_id` = ? AND `sub_category_id` = ? ORDER BY `model_no`", "ss", [$category['id'], $sub_category['id']]);

                                                    while ($product = $product_rs->fetch_assoc()) {
                                            ?>
                                                <option class="text-dark" value="<?= $product['id'] ?>" <?php if (isset($_GET['pid'])) {
                                                                                                            if ($_GET['pid'] == $product['id']) {
                                                                                                                echo " selected ";
                                                                                                            }
                                                                                                        } ?>>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<?= $product['model_no']  ?>
                                                </option>
                                            <?php
                                                    }
                                            ?>
                                        </optgroup>

                                    <?php
                                                }
                                            } else {
                                                $product_rs = Database::search("SELECT * FROM `product` WHERE `category_id` = ? ", "s", [$category['id']]);

                                                while ($product = $product_rs->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $product['id'] ?>" <?php if (isset($_GET['pid'])) {
                                                                                    if ($_GET['pid'] == $product['id']) {
                                                                                        echo " selected ";
                                                                                    }
                                                                                } ?>>
                                            <?= $product['model_no'] ?>
                                        </option>
                                <?php
                                                }
                                            }

                                ?>

                                </optgroup>
                            <?php
                                    }
                            ?>

                                </select>
                            </div>

                            <div class="col-12 col-md-2 mb-2">
                                <select id="deliver" class="form-control" onchange="changeResult('orderHistory');">
                                    <option value="all">Deliver Status</option>
                                    <option value="1">Delivered</option>
                                    <option value="0" <?php if (isset($_GET['deliver'])) {
                                                            if ($_GET['deliver'] == 0) {
                                                                echo " selected ";
                                                            }
                                                        } ?>>Deliver Pending</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2 mb-2">
                                <select id="payment" class="form-control" onchange="changeResult('orderHistory');">
                                    <option value="all">Payment Status</option>
                                    <option value="1">Paid</option>
                                    <option value="0">Not Payments Completed</option>
                                </select>
                            </div>

                            <?php

                            if ($_SESSION['user']['user_type'] != 1) {
                            ?>
                                <div class="col-12 col-md-2 mb-2">
                                    <select id="status" class="form-control" disabled>
                                        <option value="all">All Orders</option>
                                    </select>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="col-12 col-md-2 mb-2">
                                    <select id="status" class="form-control" onchange="changeResult('orderHistory');">
                                        <option value="all">All Orders</option>
                                        <option value="1">Deleted Only</option>
                                        <option value="0" <?php if (isset($_GET['status'])) {
                                                                if ($_GET['status'] == 0) {
                                                                    echo " selected ";
                                                                }
                                                            } ?>>Not Deleted</option>
                                    </select>
                                </div>
                            <?php
                            }
                            ?>


                            <div class="col-12 col-md-3 mb-2">
                                <input type="text" class="form-control" id="search" onkeyup="changeResult('orderHistory');" placeholder="Search">
                            </div>

                        </div>
                    </div>

                    <?php

                    $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` FROM `invoice` INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` WHERE `invoice`.`id` >= '0' ";


                    if ($_SESSION['user']['user_type'] == '4') {
                        $sql .= " AND `invoice`.`user_id` = '" . $_SESSION['user']['id'] . "' ";
                    }

                    if ($_SESSION['user']['user_type'] != '1') {
                        $sql .= " AND `invoice`.`is_delete` = '0' ";
                    }

                    $order_rs = Database::search($sql . " ORDER BY `invoice`.`date_time` DESC ");

                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        <div class="row">
                            <div class="col-12 d-none d-md-block">
                                <span class="text-white f-w-300">Showing <?= $order_rs->num_rows ?> of <?= $order_rs->num_rows ?> entries</span>
                            </div>
                        </div>

                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Id</th>
                                    <th>Shop</th>
                                    <th class="d-none d-lg-table-cell text-center" colspan="2">Status</th>
                                    <th class="text-center">Time</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $x = 1;
                                $date = null;

                                while ($order = $order_rs->fetch_assoc()) {

                                    if ($date == date('Y-m-d', strtotime($order['date_time']))) {
                                ?>

                                        <tr <?= $order['is_delete'] == 1 ? 'style="opacity: 0.7;"' : '' ?>>
                                            <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $x ?>
                                            </td>
                                            <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $order['order_id'] ?>
                                            </td>
                                            <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <span class="text-truncate-1">
                                                    <?= $order['shop'] ?>
                                                </span>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $order['is_delivered'] == 1 ? '<div class="d-inline-block status status-active m-auto">Delivered</div>' : '<div class="d-inline-block status status-deactive mx-auto">Not Delivered</div>' ?>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $order['is_completed'] == 1 ? '<div class="d-inline-block status status-active m-auto text-center">Paid</div>' : '<div class="d-inline-block status status-deactive text-center mx-auto">Not Paid</div>' ?>
                                            </td>
                                            <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>><?= getRelativeTime($order['date_time']) ?></td>
                                            <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <a href="view-order.php?id=<?= $order['id'] ?>" target="_blank">
                                                    <img class="me-2 action-icon" src="assets/images/icons/view.png">
                                                </a>
                                            </td>
                                        </tr>

                                    <?php

                                    } else {
                                        $date = date('Y-m-d', strtotime($order['date_time']));
                                    ?>
                                        <tr class="shadow-none" style="height: 8px !important;">
                                            <td colspan="8" class="text-center date-row"><?= date("Y-m-d") == $date ? "Today" : $date ?>
                                            </td>
                                        </tr>

                                        <tr <?= $order['is_delete'] == 1 ? 'style="opacity: 0.7;"' : '' ?>>
                                            <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $x ?>
                                            </td>
                                            <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $order['order_id'] ?>
                                            </td>
                                            <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <span class="text-truncate-1">
                                                    <?= $order['shop'] ?>
                                                </span>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $order['is_delivered'] == 1 ? '<div class="d-inline-block status status-active m-auto">Delivered</div>' : '<div class="d-inline-block status status-deactive mx-auto">Not Delivered</div>' ?>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <?= $order['is_completed'] == 1 ? '<div class="d-inline-block status status-active m-auto text-center">Paid</div>' : '<div class="d-inline-block status status-deactive text-center mx-auto">Not Paid</div>' ?>
                                            </td>
                                            <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>><?= getRelativeTime($order['date_time']) ?></td>
                                            <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                                                <a href="view-order.php?id=<?= $order['id'] ?>" target="_blank">
                                                    <img class="me-2 action-icon" src="assets/images/icons/view.png">
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    }
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


        <?php require_once('include/footer.php');

        if (isset($_GET['pid'])) {
        ?>
            <script>
                changeResult('orderHistory');
            </script>
        <?php
        }
        ?>

    </body>

    </html>
<?php
}
?>