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
        <meta name="description"
            content="DashboardKit is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
        <meta name="keywords"
            content="DashboardKit, Dashboard Kit, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Free Bootstrap Admin Template">
        <meta name="author" content="DashboardKit ">


        <!-- Favicon icon -->
        <link rel="icon" href="assets/images/favi/favi.png" type="image/x-icon">

        <!-- font css -->
        <link rel="stylesheet" href="assets/fonts/feather.css">
        <link rel="stylesheet" href="assets/fonts/fontawesome.css">
        <link rel="stylesheet" href="assets/fonts/material.css">

        <!-- vendor css -->
        <link rel="stylesheet" href="assets/css/templateStyle.css" id="main-style-link" />
        <link rel="stylesheet" href="assets/css/style.css" />

        <style>
            .tooltips {}

            .tooltips .tooltiptext {
                visibility: hidden;
                width: 250px;
                background-color: black;
                color: #fff;
                text-align: left;
                border-radius: 6px;
                padding: 5px;
                position: absolute;
                z-index: 1;
            }

            .tooltips:hover .tooltiptext {
                visibility: visible;
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
                <div class="row px-2 px-lg-4" id="stockContent">
                    <?php
                    $primary_stock = 0;
                    $ongoing_stock = 0;
                    $deliver_pending_qty = 0;

                    $category_rs = Database::search("SELECT * FROM `category` ");

                    while ($category = $category_rs->fetch_assoc()) {
                        ${'category_id_' . $category['id'] . '_primary_stock'} = 0;
                        ${'category_id_' . $category['id'] . '_ongoing_stock'} = 0;
                        ${'category_id_' . $category['id'] . '_deliver_pending_qty'} = 0;
                    }

                    ?>
                    <div class="col-12 mb-4">
                        <h1>Products Stock</h1>
                    </div>

                    <!-- Product stock status start -->

                    <?php

                    $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `stock` ON `stock`.`product_id` = `product`.`id` WHERE `status_id`= '1' ");

                    while ($product = $product_rs->fetch_assoc()) {
                        $primary_stock = $primary_stock + $product['qty'];
                        $ongoing_stock = $ongoing_stock + $product['ongoing_qty'];
                        $deliver_pending_qty = $deliver_pending_qty + $product['deliver_pending_qty'];

                        ${'category_id_' . $product['category_id'] . '_primary_stock'} = ${'category_id_' . $product['category_id'] . '_primary_stock'} + $product['qty'];
                        ${'category_id_' . $product['category_id'] . '_ongoing_stock'} = ${'category_id_' . $product['category_id'] . '_ongoing_stock'} + $product['ongoing_qty'];
                        ${'category_id_' . $product['category_id'] . '_deliver_pending_qty'} = ${'category_id_' . $product['category_id'] . '_deliver_pending_qty'} + $product['deliver_pending_qty'];

                        ?>

                        <div class="col-12 col-sm-6 col-xl-4 col-xxl-3 bg-white rounded rounded-5 p-2 tooltips">
                            <a href="order-history.php?pid=<?= $product['id'] ?>&deliver=0&status=0">
                                <div class="tooltiptext">
                                    <div class="text-center">
                                        <b><?= $product['model_no'] ?></b>
                                    </div>
                                    <?= $product['details'] ?>
                                </div>
                            </a>
                            <div class=" p-3 rounded rounded-5 card-border <?php if ($product['ongoing_qty'] + $product['qty'] <= $product['warning_no']) {
                                echo "stock-warning";
                            } ?> ">
                                <div class=" d-flex align-self-center">
                                    <img src="<?= $product['cover_image'] ?>" class="rounded rounded-3 card-img">
                                    <div class="pl-3">
                                        <h4 class=""><?= $product['model_no'] ?></h4>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mt-auto mb-0 w-100">
                                        <div class="d-flex justify-content-between mt-2">
                                            <h5 class="my-auto d-inline-block">Primary Stock</h5>
                                            <h5 class="my-auto card-count"><?= $product['qty'] ?></h5>
                                        </div>
                                        <div class="d-flex justify-content-between my-1">
                                            <h5 class="my-auto d-inline-block">Ongoing Stock</h5>
                                            <h5 class="my-auto card-count"><?= $product['ongoing_qty'] ?></h5>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <h5 class="my-auto d-inline-block">Deliver Pending Qty</h5>
                                            <h5 class="my-auto card-count"><?= $product['deliver_pending_qty'] ?></h5>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <h5 class="my-auto d-inline-block">Stock Summery</h5>
                                            <?php $summery = $product['qty'] + $product['ongoing_qty'] - $product['deliver_pending_qty'] ?>
                                            <h5 class="my-auto card-count <?= $summery <= 0 ? "text-danger fw-bold" : '' ?>">
                                                <?= $summery ?>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }

                    ?>

                    <!-- Product stock status end -->

                    <!-- Overall stock status start -->
                    <div class="col-12 my-5">
                        <div class="row">
                            <?php $summery = $primary_stock + $ongoing_stock - $deliver_pending_qty ?>
                            <div
                                class="col-12 col-md-10 col-lg-8 mx-auto py-4 px-0 px-sm-2 px-md-4 pb-2 rounded rounded-5 overall-stock-card <?= $summery <= 0 ? "stock-warning-blink" : '' ?>">
                                <h3 class="text-center mb-3 text-decoration-underline">Overall Stock Summery</h3>

                                <table class="" style="width:100%">
                                    <thead>
                                        <tr>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="fw-bold">
                                                Category</td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="fw-bold text-end">Primary Stock</td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="fw-bold text-end">Ongoing Stock</td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="fw-bold text-end">Delivery Pending Qty</td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="fw-bold text-end pe-3">Summery</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $category_rs = Database::search("SELECT * FROM `category` ");

                                        while ($category = $category_rs->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $category['name'] ?>
                                                </td>
                                                <td class="text-end">
                                                    <?php $stock = ${'category_id_' . $category['id'] . '_primary_stock'} ?><span
                                                        class="text-black-50 fw-bold"><?= ($stock == 0 && $primary_stock == 0 || ($primary_stock == 0)) ? " (0%)" : " (" . round((($stock / $primary_stock) * 100), 1) . "%)" ?></span>
                                                    <?= $stock ?>
                                                </td>
                                                <td class="text-end">
                                                    <?php $stock = ${'category_id_' . $category['id'] . '_ongoing_stock'} ?>
                                                    <span class="text-black-50 fw-bold">
                                                        <?= ($stock == 0 && $ongoing_stock == 0 || ($ongoing_stock == 0)) ? " (0%)" : " (" . round((($stock / $ongoing_stock) * 100), 1) . "%)" ?></span>
                                                    <?= $stock ?>
                                                </td>
                                                <td class="text-end">
                                                    <?php $stock = ${'category_id_' . $category['id'] . '_deliver_pending_qty'} ?>
                                                    <span
                                                        class="text-black-50 fw-bold"><?= " (" . round((($stock / $deliver_pending_qty) * 100), 1) . "%)" ?></span>
                                                    <?= $stock ?>
                                                </td>
                                                <?php
                                                $total = ${'category_id_' . $category['id'] . '_primary_stock'} + ${'category_id_' . $category['id'] . '_ongoing_stock'} - ${'category_id_' . $category['id'] . '_deliver_pending_qty'};
                                                ?>
                                                <td class="text-end pe-3 <?= $total < 0 ? 'text-danger fw-bold' : '' ?>">
                                                    <span class="text-black-50 fw-bold">
                                                        <?= ($total == 0 && $summery == 0 || ($summery == 0)) ? " (0%)" : " (" . round((($total / $summery) * 100), 1) . "%)" ?></span>
                                                    <?= $total ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }

                                        ?>
                                        <tr>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="h4">Overall
                                                Stock</td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="h5 fw-bold text-end">
                                                <?= $primary_stock ?>
                                            </td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="h5 fw-bold text-end">
                                                <?= $ongoing_stock ?>
                                            </td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="h5 fw-bold text-end">
                                                <?= $deliver_pending_qty ?>
                                            </td>
                                            <td style="background-color: <?= $summery <= 0 ? "bisque" : 'aliceblue' ?>;"
                                                class="fw-bold text-end pe-3 <?= $summery <= 0 ? "text-danger h4" : 'h5' ?>">
                                                <?= $summery ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <span class="text-start ms-0 mt-5 my-auto">
                                    Date &amp; Time:<span class="fst-italic text-secondary">
                                        <?= date('Y-m-d h:i A', time()) ?></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Overall stock status end -->


                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
        <!-- [ Main Content ] end -->


        <?php require 'include/footer.php'; ?>
        <script>
            setInterval(refreshStock, 15000);
        </script>
    </body>

    </html>

    <?php
}

?>