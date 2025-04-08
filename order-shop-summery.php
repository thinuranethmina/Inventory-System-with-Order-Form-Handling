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
                background-image: url('assets/images/back-ground/blue-half.png');
                background-repeat: no-repeat;
                background-position: top;
                background-size: contain; 
                min-width:fit-content;
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
            <div class="pcoded-content">

                <div id="modal-1" class="modal-1 show-modal">
                    <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content">

                    </div>
                </div>

                <!-- [ Main Content ] start -->
                <div class="row px-0 px-md-4">
                    <div class="col-12 mb-3 px-2">
                        <h1 class="text-white">Orders Summery Shop-wise</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-3 mb-2">
                                    <select id="user" class="form-control" onchange="changeResult('orderShopSummery');">
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
                                        <input id="from" class="form-control" type="date" onchange="changeResult('orderShopSummery');" value="<?= date('Y-m-d'); ?>" />
                                    </div>
                                    <div class="d-flex align-items-center gap-3 w-100">
                                        <span class="text-white">To</span>
                                        <input id="to" class="form-control" type="date" onchange="changeResult('orderShopSummery');" value="<?= date('Y-m-d'); ?>" />
                                    </div>
                            </div>

                            
                        </div>
                    </div>

                    <?php

                    $today = date('Y-m-d');
                    $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,
                            SUM(`invoice_item`.`qty`) AS total_qty,
                            SUM(`invoice_item`.`free_qty`) AS total_free_qty,
                            COALESCE(ri.total_returned_qty, 0) AS total_returned_qty
                            FROM `invoice` INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
                            INNER JOIN `invoice_item` ON `invoice`.`id` = `invoice_item`.`invoice_id`
                            LEFT JOIN (
                                SELECT `invoice_id`, SUM(`qty`) AS total_returned_qty
                                FROM `return_item`
                                GROUP BY `invoice_id`
                            ) AS ri ON `invoice`.`id` = ri.`invoice_id`
                            WHERE `invoice`.`date_time` >= '$today 00:00:00'
                            AND `invoice`.`date_time` <= '$today 23:59:59'
                            AND `invoice`.`id` >= 0 
                            AND `invoice`.`is_delete` = 0
                            GROUP BY invoice.id";


                    $order_rs = Database::search($sql. " ORDER BY invoice.order_id DESC");

                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        
                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Shop</th>
                                    <th>Invoiced qty</th>
                                    <th>Free qty</th>
                                    <th>Total qty</th>
                                    <th>Returned qty</th>
                                    <th>Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="summeryTable">
                                <?php

                                if ($order_rs->num_rows <= 0) {
                                ?>
                                    <tr>
                                        <td class="text-center rounded-3" colspan="9">
                                            <h4 class="m-auto">No Result</h4>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                
                                $x = 1;
                                $total_sales = 0;
                                $total_items = 0;
                                $date = null;

                                while ($order = $order_rs->fetch_assoc()) {
                                    $total_sales = $total_sales + $order['total'];
                                    $total_items = $total_items + $order['total_qty'] + $order['total_free_qty'];
                                    ?>
                                    <tr data-bs-toggle="collapse" data-bs-target="#row<?= $x ?>" aria-expanded="false" aria-controls="row<?= $x ?>" style="cursor:pointer;">
                                        <td>
                                            <?= $x ?>
                                        </td>
                                        <td><?= $order['order_id'] ?></td>
                                        <td><?= $order['shop'] ?></td>
                                        <td><?= $order['total_qty'] ?></td>
                                        <td><?= $order['total_free_qty'] ?></td>
                                        <td><?= $order['total_qty'] + $order['total_free_qty'] ?></td>
                                        <td><?= $order['total_returned_qty'] ?></td>
                                        <td>Rs.<?= number_format($order['total'],2) ?>/=</td>
                                        <td>
                                            <a href="view-order.php?id=<?= $order['id'] ?>" target="_blank">
                                                    <img class="me-2 action-icon" src="assets/images/icons/view.png">
                                                </a>
                                        </td>
                                    </tr>
                                    
                                    <?php 
                                    
                                    $items_rs = Database::search("SELECT * FROM `invoice_item` INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id` WHERE `invoice_id` = '".$order['id']."' ORDER BY invoice_item.qty DESC");
                                    $return_rs = Database::search("SELECT *, `return_item`.`qty` AS `return_qty` FROM `return_item` INNER JOIN `product` ON `product`.`id` = `return_item`.`product_id` WHERE `invoice_id` = '".$order['id']."' ORDER BY return_item.qty DESC");
                                    
                                    ?>
                                    
                                    <tr style="box-shadow:none;" class="collapse" data-bs-parent="#summeryTable" id="row<?= $x ?>">
                                        <td class="bg-transparent"></td>
                                        <td class="bg-transparent" colspan="7">
                                           <table>
                                               
                                                <tr style="height: 8px !important;">
                                                  <th>Item(s)</th>
                                                  <th>Invoiced Qty</th>
                                                  <th>Free Qty</th>
                                                  <th>Price</th>
                                                  <th>Total</th>
                                               </tr>
                                               
                                               
                                            <?php
                                                while ($item = $items_rs->fetch_assoc()) {
                                            ?>
                                                <tr style="height: 8px !important;">
                                                    <td><?= $item['model_no'] ?></td>
                                                    <td><?= $item['qty'] ?></td>
                                                    <td><?= $item['free_qty'] ?></td>
                                                    <td>Rs.<?= number_format($item['sold_price_per_item'],2) ?>/=</td>
                                                    <td>Rs.<?= number_format($item['sold_price_per_item'] * $item['qty'],2) ?>/=</td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                            
                                            </table>
                                           
                                            <?php
                                                if ($return_rs->num_rows >0) {
                                            ?>
                                           
                                                <table>
                                                   
                                                    <tr style="height: 8px !important;">
                                                      <th style="background-color: #d10e00 !important;">Item(s)</th>
                                                      <th style="background-color: #d10e00 !important;">Qty</th>
                                                      <th style="background-color: #d10e00 !important;">Price</th>
                                                      <th style="background-color: #d10e00 !important;">Total</th>
                                                   </tr>
                                                   
                                                   
                                                <?php
                                                    while ($item = $return_rs->fetch_assoc()) {
                                                ?>
                                                    <tr style="height: 8px !important;">
                                                        <td><?= $item['model_no'] ?></td>
                                                        <td><?= $item['return_qty'] ?></td>
                                                        <td>Rs.<?= number_format($item['price'],2) ?>/=</td>
                                                        <td>Rs.<?= number_format($item['price'] * $item['return_qty'],2) ?>/=</td>
                                                    </tr>
                                                <?php
                                                    }
                                                ?>
                                                
                                               </table>
                                               
                                            <?php
                                                }
                                            ?>
                                            
                                        </td>
                                    </tr>
                                    <?php
                                    
                                    if($order_rs->num_rows == $x) {
                                        ?>
                                            <tr>
                                                <td class="text-center rounded-3" colspan="9">
                                                    <h5 class="m-auto">Total Qty : <?= number_format($total_items) ?> <span class="text-muted">|</span> Total Sales : Rs.<?= number_format($total_sales, 2) ?>/=</h5>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

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