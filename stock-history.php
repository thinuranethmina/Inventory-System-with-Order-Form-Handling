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
                        <h1 class="text-white">Stock History</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-4 offset-md-0 col-xl-3 mb-2 mb-md-0">

                                <select id="product" class="form-control" onchange="changeResult('stockHistory');">
                                    <option value="0">All Products</option>
                                    <?php
                                    $product_rs = Database::search("SELECT * FROM `product`");

                                    while ($product = $product_rs->fetch_assoc()) {
                                        ?>
                                        <option value="<?= $product['id'] ?>">
                                            <img src="<?= $product['cover_image'] ?>" width="20" height="20" alt="*" />
                                            <?= $product['model_no'] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                                <select id="user" class="form-control" onchange="changeResult('stockHistory');">
                                    <option value="0">By All Users</option>
                                    <?php
                                    $user_rs = Database::search("SELECT * FROM `user`");

                                    while ($users = $user_rs->fetch_assoc()) {
                                        ?>
                                        <option value="<?= $users['id'] ?>"> <?= $users['name'] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                                <select id="stockType" class="form-control" onchange="changeResult('stockHistory');">
                                    <option value="0">Any Stock</option>
                                    <?php
                                    $user_rs = Database::search("SELECT * FROM `stock_type`");

                                    while ($users = $user_rs->fetch_assoc()) {
                                        ?>
                                        <option value="<?= $users['id'] ?>"> <?= $users['name'] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                                <select id="operation" class="form-control" onchange="changeResult('stockHistory');">
                                    <option value="0">All Operations</option>
                                    <?php
                                    $operation_rs = Database::search("SELECT * FROM `operation_type`");

                                    while ($operation = $operation_rs->fetch_assoc()) {
                                        ?>
                                        <option value="<?= $operation['id'] ?>"> <?= $operation['name'] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>


                        </div>
                    </div>

                    <?php
                    $stock_rs = Database::search("SELECT *,`stock_history`.`id`,`stock_history`.`date_time` FROM `stock_history` INNER JOIN `product` ON `product`.`id` = `stock_history`.`product_id` ORDER BY  `stock_history`.`date_time` DESC , `stock_history`.`id` DESC ");
                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        <div class="row">
                            <div class="col-12 d-none d-md-block">
                                <span class="text-white f-w-300">Showing <?= $stock_rs->num_rows ?> of
                                    <?= $stock_rs->num_rows ?> entries</span>
                            </div>
                        </div>

                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Item</th>
                                    <th>Stock</th>
                                    <th class="d-none d-xl-table-cell text-center">Old qty</th>
                                    <th class="fs-4 text-center">±</th>
                                    <th class="d-none d-xl-table-cell text-center">Total qty</th>
                                    <th class="d-none d-lg-table-cell text-center">Time</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $x = 1;
                                $date = null;
                                while ($stock = $stock_rs->fetch_assoc()) {

                                    if ($date == date('Y-m-d', strtotime($stock['date_time']))) {
                                        ?>

                                        <tr>
                                            <td>
                                                <?= $x ?>
                                            </td>
                                            <td>
                                                <img src="<?= $stock['cover_image'] ?>" class="table-main-image">
                                            </td>
                                            <td>
                                                <?= $stock['model_no'] ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($stock['stock_type_id'] == '1') {
                                                    echo "Primary";
                                                } else if ($stock['stock_type_id'] == '2') {
                                                    echo "Ongoing";
                                                }
                                                ?>
                                            </td>
                                            <td class="d-none d-xl-table-cell text-center">
                                                <?= $stock['old_qty'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                if ($stock['operation_type_id'] == '1') {
                                                    ?>
                                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                                    <?php
                                                } else if ($stock['operation_type_id'] == '2') {
                                                    ?>
                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-red.png">
                                                    <?php
                                                } else if ($stock['operation_type_id'] == '3') {
                                                    ?>
                                                            <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                                    <?php
                                                } else if ($stock['operation_type_id'] == '4') {
                                                    ?>
                                                                <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                                        <?php
                                                        if ($stock['stock_type_id'] == '1') {
                                                            ?>
                                                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                                        <?php
                                                        } else if ($stock['stock_type_id'] == '2') {
                                                            ?>
                                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                                        <?php
                                                        }
                                                } else if ($stock['operation_type_id'] == '5') {
                                                    ?>
                                                                    <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                                        <?php
                                                        if ($stock['stock_type_id'] == '1') {
                                                            ?>
                                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                                        <?php
                                                        } else if ($stock['stock_type_id'] == '2') {
                                                            ?>
                                                                            <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                                        <?php
                                                        }
                                                }
                                                ?>
                                                <?= $stock['changed_qty'] ?>
                                            </td>
                                            <td class="d-none d-xl-table-cell text-center">
                                                <?= $stock['total_qty'] ?>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center">
                                                <?= getRelativeTime($stock['date_time']) ?>
                                            </td>
                                            <td class="text-center">
                                                <img class="me-2 action-icon" onclick="viewModal('<?= $stock['id'] ?>','stock');"
                                                    src="assets/images/icons/view.png">
                                            </td>
                                        </tr>

                                        <?php

                                    } else {
                                        $date = date('Y-m-d', strtotime($stock['date_time']));
                                        ?>
                                        <tr class="shadow-none" style="height: 8px !important;">
                                            <td colspan="9" class="text-center date-row">
                                                <?= date("Y-m-d") == $date ? "Today" : $date ?>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <?= $x ?>
                                            </td>
                                            <td>
                                                <img src="<?= $stock['cover_image'] ?>" class="table-main-image">
                                            </td>
                                            <td>
                                                <?= $stock['model_no'] ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($stock['stock_type_id'] == '1') {
                                                    echo "Primary";
                                                } else if ($stock['stock_type_id'] == '2') {
                                                    echo "Ongoing";
                                                }
                                                ?>
                                            </td>
                                            <td class="d-none d-xl-table-cell text-center">
                                                <?= $stock['old_qty'] ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                if ($stock['operation_type_id'] == '1') {
                                                    ?>
                                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                                    <?php
                                                } else if ($stock['operation_type_id'] == '2') {
                                                    ?>
                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-red.png">
                                                    <?php
                                                } else if ($stock['operation_type_id'] == '3') {
                                                    ?>
                                                            <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                                    <?php
                                                } else if ($stock['operation_type_id'] == '4') {
                                                    ?>
                                                                <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                                        <?php
                                                        if ($stock['stock_type_id'] == '1') {
                                                            ?>
                                                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                                        <?php
                                                        } else if ($stock['stock_type_id'] == '2') {
                                                            ?>
                                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                                        <?php
                                                        }
                                                } else if ($stock['operation_type_id'] == '5') {
                                                    ?>
                                                                    <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                                        <?php
                                                        if ($stock['stock_type_id'] == '1') {
                                                            ?>
                                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                                        <?php
                                                        } else if ($stock['stock_type_id'] == '2') {
                                                            ?>
                                                                            <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                                        <?php
                                                        }
                                                }
                                                ?>
                                                <?= $stock['changed_qty'] ?>
                                            </td>
                                            <td class="d-none d-xl-table-cell text-center">
                                                <?= $stock['total_qty'] ?>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center">
                                                <?= getRelativeTime($stock['date_time']) ?>
                                            </td>
                                            <td class="text-center">
                                                <img class="me-2 action-icon" onclick="viewModal('<?= $stock['id'] ?>','stock');"
                                                    src="assets/images/icons/view.png">
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


        <?php require_once ('include/footer.php'); ?>
    </body>

    </html>
    <?php
}
?>