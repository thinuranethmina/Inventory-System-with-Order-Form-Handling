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

        <style>
                
            .pc-container {
                z-index: 1;
                position: relative;
            }
        
            .blue-box {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                background-color: var(--theme-color-blue, #007bff);
                min-width: 100%;
                height: 350px;
                z-index: 0;
            }
            
            .pcoded-content {
                position: relative; 
                z-index: 1;
                padding: 1rem; 
            }

            .chosen-single {
                /* display: flex !important; */
                background-image: none !important;
                background-color: white !important;
                font-size: 15px !important;
                height: 38px !important;
                padding-top: 5px !important;
                border-color: #c9c9c9 !important;
            }

            .chosen-single div,
            .chosen-single span {
                margin: auto 10px !important;
            }

            .chosen-single div b {
                /* color: var(--button-primary-color) !important; */
            }
        </style>

    </head>

    <body class="">

        <!-- [ Header ] start -->
        <?php include "include/header.php"; ?>
        <!-- [ Header ] end -->

        <!-- [ Main Content ] start -->
        <div class="pc-container">
        
            <div class="blue-box">
                
            </div>
            
            <div class="pcoded-content">

                <div id="modal-1" class="modal-1 show-modal">
                    <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content">

                    </div>
                </div>
                

                <!-- [ Main Content ] start -->
                <div class="row px-0 px-md-4">
                    <div class="col-12 mb-3 px-2">
                        <h1 class="text-white">Orders Summery Item-wise</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-3 mb-2">
                                    <select id="user" class="form-control" onchange="changeResult('orderSummery');">
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
                            </div>
                            
                            <div class="col-12 col-md-6 mb-2 d-flex flex-column flex-md-row align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-3 w-100">
                                        <span class="text-white">From</span>
                                        <input id="from" class="form-control" type="date" onchange="changeResult('orderSummery');" value="<?= date('Y-m-d'); ?>" />
                                    </div>
                                    <div class="d-flex align-items-center gap-3 w-100">
                                        <span class="text-white">To</span>
                                        <input id="to" class="form-control" type="date" onchange="changeResult('orderSummery');" value="<?= date('Y-m-d'); ?>" />
                                    </div>
                            </div>

                            <div class="col-12 mt-2">
                                <select id="shop" onchange="changeResult('orderSummery');" class="w-100 ms-md-3 px-4 rounded rounded-5 select-chosen" data-placeholder="All shops" multiple>
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
                            
                        </div>
                    </div>

                    <?php

                   $today = date('Y-m-d');
                    $sql = "SELECT 
                              `product`.`model_no`, 
                              `product`.`cover_image`, 
                              COUNT(*) AS total_items,
                              SUM(`invoice_item`.`qty`) AS total_qty,
                              SUM(`invoice_item`.`free_qty`) AS total_free_qty,
                              SUM(`invoice_item`.`qty` * `invoice_item`.`sold_price_per_item`) AS total_sales
                            FROM `invoice`
                            INNER JOIN `invoice_item` ON `invoice`.`id` = `invoice_item`.`invoice_id`
                            INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id`
                            WHERE `invoice`.`date_time` >= '$today 00:00:00'
                              AND `invoice`.`date_time` <= '$today 23:59:59'
                              AND `invoice`.`id` >= 0 
                              AND `invoice`.`is_delete` = 0
                            GROUP BY `invoice_item`.`product_id`";


                    $order_rs = Database::search($sql. " ORDER BY total_qty DESC");


                    $sql = "SELECT 
                              `product`.`model_no`, 
                              `product`.`cover_image`, 
                              COUNT(*) AS total_items,
                              SUM(`return_item`.`qty`) AS total_qty,
                                SUM(`return_item`.`qty` * `return_item`.`price`) AS total_loss
                            FROM `invoice`
                            INNER JOIN `return_item` ON `invoice`.`id` = `return_item`.`invoice_id`
                            INNER JOIN `product` ON `product`.`id` = `return_item`.`product_id`
                            WHERE `invoice`.`date_time` >= '$today 00:00:00'
                              AND `invoice`.`date_time` <= '$today 23:59:59'
                              AND `invoice`.`id` >= '0' AND `invoice`.`is_delete` = '0' 
                            GROUP BY `return_item`.`product_id`";

                    $return_order_rs = Database::search($sql. " ORDER BY total_qty DESC");
                    
                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        
                        <div class="row">
                            <div class="col-12 mt-3">
                                <h4 class="text-white text-center">Invoiced Items</h4>
                            </div>
                        </div>

                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none d-md-table-cell">Image</th>
                                    <th>Item(s)</th>
                                    <th>Invoiced qty</th>
                                    <th>Free qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if ($order_rs->num_rows <= 0) {
                                ?>
                                    <tr>
                                        <td class="text-center rounded-3" colspan="6">
                                            <h4 class="m-auto">No Result</h4>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                
                                $x = 1;
                                $total_sales = 0;
                                $date = null;

                                while ($order = $order_rs->fetch_assoc()) {
                                    $total_sales = $total_sales + $order['total_sales'];
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <img src="<?= $order['cover_image'] ?>" class="table-main-image">
                                        </td>
                                        <td><?= $order['model_no'] ?></td>
                                        <td><?= $order['total_qty'] ?></td>
                                        <td><?= $order['total_free_qty'] ?></td>
                                        <td>Rs.<?= number_format($order['total_sales'], 2) ?>/=</td>
                                    </tr>
                                    <?php
                                    
                                    if($order_rs->num_rows == $x) {
                                        ?>
                                            <tr>
                                                <td class="text-center rounded-3" colspan="6">
                                                    <h5 class="m-auto">Total Sales : Rs.<?= number_format($total_sales, 2) ?>/=</h5>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                    
                                    $x++;
                                }


                                ?>
                                    
                            </tbody>
                        </table>
                       
                        <div class="row">
                            <div class="col-12 mt-4">
                                <h4 class="text-center">Returned Items</h4>
                            </div>
                        </div>
                        
                        <table class="mb-3" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="background-color: #d10e00 !important;">#</th>
                                    <th style="background-color: #d10e00 !important;">Image</th>
                                    <th style="background-color: #d10e00 !important;">Item(s)</th>
                                    <th style="background-color: #d10e00 !important;">Qty</th>
                                    <th style="background-color: #d10e00 !important;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                    
                                if ($return_order_rs->num_rows <= 0) {
                                ?>
                                    <tr>
                                        <td class="text-center rounded-3" colspan="5">
                                            <h4 class="m-auto">No Result</h4>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                $x = 1;
                                $total_loss = 0;
                                $date = null;

                                while ($order = $return_order_rs->fetch_assoc()) {
                                    $total_loss = $total_loss + $order['total_loss']
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <img src="<?= $order['cover_image'] ?>" class="table-main-image">
                                        </td>
                                        <td><?= $order['model_no'] ?></td>
                                        <td><?= $order['total_qty'] ?></td>
                                        <td>Rs.<?= number_format($order['total_loss'], 2) ?>/=</td>
                                    </tr>
                                    <?php
                                    
                                    if($return_order_rs->num_rows == $x) {
                                        ?>
                                            <tr>
                                                <td class="text-center rounded-3" colspan="5">
                                                    <h5 class="m-auto">Total Loss : Rs.<?= number_format($total_loss, 2) ?>/=</h5>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
        <script>
            $(".select-chosen").chosen();
        </script>

    </body>

    </html>
<?php
}
?>