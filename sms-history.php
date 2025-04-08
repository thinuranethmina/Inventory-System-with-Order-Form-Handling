<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {

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
                        <div class="row">
                            <div class="modal--header">
                                <div class="d-flex ">
                                    <h2 class="my-auto ">Activity</h2>
                                    <span class="close ml-auto my-auto" onclick="closeModel();">&times;</span>
                                </div>
                                <hr class="text-dark my-2">
                            </div>
                            <div class="modal--body">
                                <div class="row p-3 text-center">
                                    <div class="col-12">
                                        <span class="my-auto">You added new product(NSCKE).</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal--footer d-sm-flex text-secondary">
                                <span class="text-start ms-0 my-auto"> Date & Time:<span class="fst-italic text-secondary">2023-10-06 06:13:54</span>
                                </span>
                                <div class="ml-auto float-right p-2">
                                    <button class="btn btn-primary">close</button>
                                    <button class="btn btn-primary">View</button>
                                </div>

                            </div>
                        </div>
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
                        <h1 class="text-white">SMS History</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-4 col-xl-3 offset-xl-3 mb-2 mb-md-0">

                                <select id="shop" class="form-control" onchange="changeResult('smsHistory');">
                                    <option value="0">All Shops</option>
                                    <?php
                                    $shop_rs = Database::search("SELECT * FROM `shop` WHERE `shop`.`id` != '0' AND `shop`.`mobile` != '' ");

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

                            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">

                                <select id="message_send_id" class="form-control" onchange="changeResult('smsHistory');">
                                    <option value="0">Select Sender Id</option>
                                    <?php
                                    $send_id_rs = Database::search("SELECT * FROM `message_send_id`");

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

                            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                                <input type="text" class="form-control" onkeyup="changeResult('smsHistory');" id="search" placeholder="Search">
                            </div>

                        </div>
                    </div>

                    <?php
                    $msg_rs = Database::search("SELECT *,`shop`.`id` AS `shopId`,`shop`.`name` AS `shop`,`message`.`id` AS `id`,`message_send_id`.`name` AS `send_id`, `message`.`date_time` AS `date_time` FROM `message` INNER JOIN `message_send_id` ON `message_send_id`.`id` = `message`.`message_send_id_id` INNER JOIN `shop` ON `shop`.`id`=`message`.`shop_id` ORDER BY  `message`.`date_time` DESC ");
                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        <div class="row">
                            <div class="col-12 d-none d-md-block">
                                <span class="text-white f-w-300">Showing <?= $msg_rs->num_rows ?> of <?= $msg_rs->num_rows ?> entries</span>
                            </div>
                        </div>

                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Shop</th>
                                    <th>Message</th>
                                    <th class="d-none d-md-table-cell text-center">Time</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $x = 1;
                                $date = null;
                                while ($msg = $msg_rs->fetch_assoc()) {

                                    if ($date == date('Y-m-d', strtotime($msg['date_time']))) {
                                ?>

                                        <tr>
                                            <td>
                                                <?= $x ?>
                                            </td>
                                            <td>
                                                <span class="text-truncate-1">
                                                    <?php
                                                    if ($msg['shopId'] == '0') {
                                                        echo $msg['phone'];
                                                    } else {
                                                        echo $msg['shop'];
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= $msg['send_id'] . ": " . $msg['message'] ?>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center"><?= getRelativeTime($msg['date_time']) ?></td>
                                            <td class="text-center">
                                                <img class="me-2 action-icon" onclick="viewModal('<?= $msg['id'] ?>','SMS');" src="assets/images/icons/view.png">
                                            </td>
                                        </tr>

                                    <?php

                                    } else {
                                        $date = date('Y-m-d', strtotime($msg['date_time']));
                                    ?>
                                        <tr class="shadow-none" style="height: 8px !important;">
                                            <td colspan="8" class="text-center date-row"><?= date("Y-m-d") == $date ? "Today" : $date ?>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <?= $x ?>
                                            </td>
                                            <td>
                                                <span class="text-truncate-1">
                                                    <?php
                                                    if ($msg['shopId'] == '0') {
                                                        echo $msg['phone'];
                                                    } else {
                                                        echo $msg['shop'];
                                                    }
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-truncate-1"><?= $msg['send_id'] . ": " . $msg['message'] ?></span>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center" style="min-width: 120px;"><?= getRelativeTime($msg['date_time']) ?></td>
                                            <td class="text-center">
                                                <img class="me-2 action-icon" onclick="viewModal('<?= $msg['id'] ?>','SMS');" src="assets/images/icons/view.png">
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



        <?php require_once('include/footer.php'); ?>

    </body>

    </html>
<?php
}
?>