<?php


require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_GET['id'])) {

        $invoice_rs = Database::search("SELECT *,`invoice`.`id` AS `id`,`shop`.`id` AS `shopID`,`shop`.`name` AS `shop`, `invoice`.`date_time` AS `date_time`, `invoice`.`note` AS `invoiceNote` , `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province`  FROM `invoice` INNER JOIN `shop`  ON `shop`.`id` = `invoice`.`shop_id`  INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id`  WHERE `invoice`.`id` = ? AND `is_delete` = '0' AND `shop`.`id`!= '0' ", "s", [$_GET['id']]);

        if ($invoice_rs->num_rows == 1) {
            $invoice = $invoice_rs->fetch_assoc();
?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <title>Nsonic</title>

                <!-- Meta -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="description" content="DashboardKit is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
                <meta name="keywords" content="DashboardKit, Dashboard Kit, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Free Bootstrap Admin Template">
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

                <!-- quill css -->
                <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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
                            <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">

                            </div>
                        </div>

                        <!-- [ Main Content ] start -->
                        <div class="row px-0 px-md-1 px-lg-4 ">
                            <div class="col-12">

                                <h1 class="mb-5 text-white">Order ID (<?= $invoice['order_id'] ?>)</h1>

                                <div class="row">
                                    <div class="col-12 col-md-11 col-lg-12 col-xl-11 col-xxl-10 mx-auto p-0 p-sm-2 p-lg-3 rounded rounded-5 bg-white box-shadow mb-5">
                                        <div class="card-border py-5 p-2 p-lg-5 rounded rounded-5">

                                            <?php
                                            if ($invoice['shopID'] != 0) {
                                            ?>
                                                <div class="row mb-3 text-center">
                                                    <div class="col-12 mb-3">
                                                        <img class="rounded rounded-5 send-msg-shop-img" src="<?= $invoice['image'] ?>" alt="Shop Image">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="my-auto px-xl-5 flex-fill">
                                                            <h4><?= $invoice['shop'] ?></h4>
                                                            <span><?= $invoice['address'] . ", " . $invoice['city'] . ", " . $invoice['district'] . " district, " . $invoice['province'] . " province." ?></span>
                                                            <br>
                                                            <span><?= $invoice['mobile'] ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>


                                            <div class="row mt-3">
                                                <div class="col-12 d-flex justify-content-between">
                                                    <span>Order ID: <?= $invoice['order_id'] ?></span>

                                                    <span class="fst-italic"><?= date('Y-m-d h:i:s A', strtotime($invoice['date_time'])) ?></span>
                                                </div>
                                            </div>
                                            <input type="hidden" id="priceType" value="<?= $invoice['is_credit'] == 1 ? 2 : 1  ?>">
                                            <div class="row mb-3 mb-lg-5">
                                                <h4 class="text-center">Billing Items</h4>
                                                <div class="col-12">
                                                    <table id="invoiceTable" style="width:100%; font-size: 10px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="min-width: 40px !important;">#</th>
                                                                <th>Image</th>
                                                                <th>Item(s)</th>
                                                                <th>free qty</th>
                                                                <th>qty</th>
                                                                <th>Rate (Rs.)</th>
                                                                <th>Price (Rs.)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $invoice_item_rs = Database::search("SELECT * FROM `invoice_item` INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id` WHERE `invoice_id` = ? ", "s", [$_GET['id']]);

                                                            $x = 1;
                                                            while ($invoice_item = $invoice_item_rs->fetch_assoc()) {
                                                            ?>
                                                                <tr>
                                                                    <td id="<?= $invoice_item['product_id'] ?>" onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);">
                                                                        <span class="icon-on-hover<?= $invoice_item['product_id'] ?>"><?= $x ?></span>
                                                                        <img onclick="removeProduct(<?= $invoice_item['product_id'] ?>);" class="icon-on-hover<?= $invoice_item['product_id'] ?> d-none mb-1 action-icon" src="assets/images/icons/remove.png"> <br>
                                                                        <img onclick="editProductModalOpen(<?= $invoice_item['product_id'] ?>,<?= $x ?>);" class="icon-on-hover<?= $invoice_item['product_id'] ?> d-none action-icon" src="assets/images/icons/edit.png">
                                                                    </td>
                                                                    <td onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);">
                                                                        <img src="<?= $invoice_item['cover_image'] ?>" class="table-main-image">
                                                                    </td>
                                                                    <td onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);"><?= $invoice_item['model_no'] ?></td>
                                                                    <td onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);"><?= $invoice_item['free_qty'] ?></td>
                                                                    <td onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);"><?= $invoice_item['qty'] ?></td>
                                                                    <?php
                                                                    if ($invoice_item['is_special_price'] == '1') {
                                                                    ?>
                                                                        <td class="text-danger f-w-500" onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);"><?= number_format($invoice_item['sold_price_per_item'], 2) ?></td>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <td onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);"><?= number_format($invoice_item['sold_price_per_item'], 2) ?></td>
                                                                    <?php
                                                                    }

                                                                    ?>
                                                                    <td onmouseout="showOptions(<?= $invoice_item['product_id'] ?>);" onmouseover="showOptions(<?= $invoice_item['product_id'] ?>);">
                                                                        <?= number_format($invoice_item['sold_price_per_item'] * $invoice_item['qty'], 2) ?>
                                                                    </td>

                                                                </tr>
                                                            <?php
                                                                $x++;
                                                            }
                                                            ?>

                                                            <tr class=" p-0 m-0" style="height: 8px !important;">
                                                                <td class="text-center p-0 m-0" colspan="7">
                                                                    <button class="btn btn-secondary w-100" onclick="viewAddProductModalInUpdateAddOrder();">Add Product</button>
                                                                </td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php
                                                $invoice_return_item_rs = Database::search("SELECT * FROM `return_item` INNER JOIN `product` ON `product`.`id` = `return_item`.`product_id` WHERE `invoice_id` = ? ", "s", [$_GET['id']]);

                                                $x = 1;
                                                ?>
                                                <hr class="mt-2 mt-lg-4">
                                                <div class="col-12">
                                                    <h4 class="text-center">Return Items</h4>
                                                    <table id="returnItemTable" style="width:100%; font-size: 10px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="min-width: 40px !important; background-color: #d10e00 !important;">#</th>
                                                                <th style="background-color: #d10e00 !important;">Image</th>
                                                                <th style="background-color: #d10e00 !important;">Item(s)</th>
                                                                <th style="background-color: #d10e00 !important;">qty</th>
                                                                <th style="background-color: #d10e00 !important;">Rate (Rs.)</th>
                                                                <th style="background-color: #d10e00 !important;">Price (Rs.)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            while ($invoice_return_item = $invoice_return_item_rs->fetch_assoc()) {
                                                            ?>
                                                                <tr>
                                                                    <td id="<?= $invoice_return_item['product_id'] ?>" onmouseout="showOptions2(<?= $invoice_return_item['product_id'] ?>);" onmouseover="showOptions2(<?= $invoice_return_item['product_id'] ?>);">
                                                                        <span class="icon-on-hover2<?= $invoice_return_item['product_id'] ?>"><?= $x ?></span>
                                                                        <img onclick="removeReturnProduct(<?= $invoice_return_item['product_id'] ?>);" class="icon-on-hover2<?= $invoice_return_item['product_id'] ?> d-none mb-1 action-icon" src="assets/images/icons/remove.png"> <br>
                                                                        <img onclick="editReturnProductModalOpen(<?= $invoice_return_item['product_id'] ?>,<?= $x ?>);" class="icon-on-hover2<?= $invoice_return_item['product_id'] ?> d-none action-icon" src="assets/images/icons/edit.png">
                                                                    </td>
                                                                    <td onmouseout="showOptions2(<?= $invoice_return_item['product_id'] ?>);" onmouseover="showOptions2(<?= $invoice_return_item['product_id'] ?>);">
                                                                        <img src="<?= $invoice_return_item['cover_image'] ?>" class="table-main-image">
                                                                    </td>
                                                                    <td onmouseout="showOptions2(<?= $invoice_return_item['product_id'] ?>);" onmouseover="showOptions2(<?= $invoice_return_item['product_id'] ?>);"><?= $invoice_return_item['model_no'] ?></td>
                                                                    <td onmouseout="showOptions2(<?= $invoice_return_item['product_id'] ?>);" onmouseover="showOptions2(<?= $invoice_return_item['product_id'] ?>);"><?= $invoice_return_item['qty'] ?></td>
                                                                    <td onmouseout="showOptions2(<?= $invoice_return_item['product_id'] ?>);" onmouseover="showOptions2(<?= $invoice_return_item['product_id'] ?>);">
                                                                        <?= number_format($invoice_return_item['price'], 2) ?>
                                                                    </td>
                                                                    <td onmouseout="showOptions2(<?= $invoice_return_item['product_id'] ?>);" onmouseover="showOptions2(<?= $invoice_return_item['product_id'] ?>);">
                                                                        <?= number_format($invoice_return_item['price'] * $invoice_return_item['qty'], 2) ?>
                                                                    </td>

                                                                </tr>
                                                            <?php
                                                                $x++;
                                                            }

                                                            ?>
                                                            <tr class=" p-0 m-0" style="height: 8px !important;">
                                                                <td class="text-center p-0 m-0" colspan="7">
                                                                    <button class="btn btn-secondary w-100" onclick="viewAddProductModalInAddReturnOrder();">Add Product</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>


                                                    <div class="col-12 col-md-6 col-xl-5 mr-0 ml-auto mt-3" id="orderSummeryContent">
                                                        <table>
                                                            <thead>
                                                                <tr style="height: 8px !important;">
                                                                    <th class="text-center rounded rounded-3" colspan="2">Order Summery</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr style="height: 8px !important;">
                                                                    <td>Sub Total:</td>
                                                                    <td>Rs.<?= number_format($invoice['sub_total'], 2) ?>/=</td>
                                                                </tr>
                                                                <tr style="height: 8px !important;">
                                                                    <td>Discount:</td>
                                                                    <td>
                                                                        <?= $invoice['discount'] ?>%
                                                                    </td>
                                                                </tr>
                                                                <tr style="height: 8px !important;">
                                                                    <td>Return Items Total:</td>
                                                                    <td>Rs.<?= number_format($invoice['return_total'], 2) ?>/=</td>
                                                                </tr>

                                                                <?php
                                                                $additional_amount_rs = Database::search("SELECT * FROM `invoice_payment` INNER JOIN `payment_type` ON `payment_type`.`id` = `invoice_payment`.`payment_type_id` WHERE `invoice_id` = ? AND `is_additional_amount`=? ORDER BY `date_time` ASC", "ss", [$_GET['id'], '1']);
                                                                $additional_amount = 0;
                                                                while ($additional_adding = $additional_amount_rs->fetch_assoc()) {
                                                                    $additional_amount = $additional_amount + $additional_adding['paid_amount'];
                                                                }

                                                                if ($additional_amount_rs->num_rows > 0) {
                                                                ?>
                                                                    <tr style="height: 8px !important;">
                                                                        <td>Additional Am.:</td>
                                                                        <td>Rs.<?= number_format($additional_amount, 2) ?>/=</td>
                                                                    </tr>
                                                                <?php
                                                                }

                                                                ?>

                                                                <tr style="height: 8px !important;">
                                                                    <td>Total:</td>
                                                                    <td>Rs.<?= number_format($invoice['total'], 2) ?>/=</td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-12 col-md-6 col-xl-5 mr-0 ml-auto">
                                                        <table>
                                                            <tbody>
                                                                <tr style="height: 8px !important;">
                                                                    <td colspan="2">
                                                                        <select id="chequeTerm" class="form-control p-0 px-1 border-0" style="height: min-content !important;">
                                                                            <option value="0">Select Cheque Term</option>
                                                                            <?php
                                                                            $cheque_term_rs = Database::search("SELECT * FROM `cheque_term`");
                                    
                                                                            while ($cheque_term = $cheque_term_rs->fetch_assoc()) {
                                                                            ?>
                                                                                <option value="<?= $cheque_term['id'] ?>" <?= $invoice['cheque_term_id'] == $cheque_term['id'] ? 'selected':'' ?>><?= $cheque_term['name'] ?></option>
                                                                            <?php
                                                                            }
                                    
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                        
                                                        
                                                    <div class="col-12 mr-0 ml-auto mt-3">
                                                        <h3>Payments</h3>
                                                        <table>
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Method</th>
                                                                    <th>Paid Amount (Rs.)</th>
                                                                    <th>Balance (Rs.)</th>
                                                                    <th>Date Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                $invoice_payment_rs = Database::search("SELECT * FROM `invoice_payment` INNER JOIN `payment_type` ON `payment_type`.`id` = `invoice_payment`.`payment_type_id` WHERE `invoice_id` = ? ORDER BY `date_time` ASC", "s", [$_GET['id']]);

                                                                $totalPaidAmount = 0;
                                                                $totalBalance = $invoice['total'];
                                                                $y = 1;
                                                                while ($invoice_payment = $invoice_payment_rs->fetch_assoc()) {
                                                                    $totalBalance = $invoice_payment['balance'];

                                                                    if ($invoice_payment['is_additional_amount'] == '1') {
                                                                ?>
                                                                        <tr>
                                                                            <td class="text-center bg-warning" colspan="2">Additional Amount</td>
                                                                            <td class="bg-warning"><?= number_format($invoice_payment['paid_amount'], 2) ?></td>
                                                                            <td class="bg-warning"><?= number_format($invoice_payment['balance'], 2) ?></td>
                                                                            <td class="bg-warning"><?= date('Y-m-d h:i:s A', strtotime($invoice_payment['date_time'])) ?></td>
                                                                        </tr>
                                                                    <?php
                                                                    } else {
                                                                        $totalPaidAmount = $totalPaidAmount + $invoice_payment['paid_amount'];
                                                                    ?>
                                                                        <tr>
                                                                            <td><?= $y ?></td>
                                                                            <td><?= $invoice_payment['name'] ?></td>
                                                                            <td><?= number_format($invoice_payment['paid_amount'], 2) ?></td>
                                                                            <td><?= number_format($invoice_payment['balance'], 2) ?></td>
                                                                            <td><?= date('Y-m-d h:i:s A', strtotime($invoice_payment['date_time'])) ?></td>
                                                                        </tr>
                                                                <?php
                                                                        $y++;
                                                                    }
                                                                }
                                                                ?>

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-12 mr-0 ml-auto mt-3">
                                                        <h3>Status</h3>
                                                        <?= $invoice['is_delivered'] == 1 ? '<div class="d-inline-block status status-active m-auto">Delivered</div>' : '<div class="d-inline-block status status-deactive mx-auto">Not Delivered</div>' ?>
                                                        <?= $invoice['is_completed'] == 1 ? '<div class="d-inline-block status status-active m-auto text-center">Payments Completed</div>' : '<div class="d-inline-block status status-deactive text-center mx-auto">Not Payments Completed</div>' ?>
                                                    </div>

                                                </div>

                                                <div class="row my-3">
                                                    <div class="col-12">
                                                        <textarea class="w-100 form-control" id="note" rows="5"><?= $invoice['invoiceNote'] ?></textarea>

                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="row mt-3">
                                                    <div class="col-12 text-end">
                                                        <button class="btn delete-btn my-2" onclick="DeleteOrderForm('<?= $invoice['id'] ?>');">Delete this Order Form</button>
                                                        <button class="btn submit-btn my-2" onclick="UpdateOrderForm('<?= $invoice['id'] ?>');">Save change</button>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                    <!-- [ Main Content ] end -->

                    <?php require 'include/footer.php'; ?>

            </body>

            </html>

<?php
        } else {
            header("Location: ./");
        }
    } else {
        header("Location: ./");
    }
}
?>