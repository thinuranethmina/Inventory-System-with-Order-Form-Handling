<?php


require "util/userStatus.php";

if (User::is_allow()) {

    $sql1 = "SELECT 
              `product`.`model_no`, 
              `product`.`cover_image`, 
              COUNT(*) AS total_items,
              SUM(`invoice_item`.`qty`) AS total_qty,
              SUM(`invoice_item`.`free_qty`) AS total_free_qty,
              SUM(`invoice_item`.`qty` * `invoice_item`.`sold_price_per_item`) AS total_sales
            FROM `invoice`
            INNER JOIN `invoice_item` ON `invoice`.`id` = `invoice_item`.`invoice_id`
            INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id`
            WHERE `invoice`.`id` >= 0 
              AND `invoice`.`is_delete` = 0";
              
              
    if (isset($_POST['from'])) {
        $from = $_POST['from'] ?? null;
        $sql1 .= " AND `invoice`.`date_time` >= '$from 00:00:00'";
    }
    
    if (isset($_POST['to'])) {
        $to = $_POST['to'] ?? null;
        $sql1 .= " AND `invoice`.`date_time` <= '$to 23:59:59'";
    }
    
    if (isset($_POST['shops'])) {
        if (!empty($_POST['shops']) && is_array($_POST['shops'])) {
            
            $shops = array_map('intval', $_POST['shops']);
        
            if (!empty($shops)) {
                $shopIds = implode(",", $shops);
                $sql1 .= " AND `invoice`.`shop_id` IN ($shopIds)";
                $sql2 .= " AND `invoice`.`shop_id` IN ($shopIds)";
            }
        }
    }
    
    $sql2 = "SELECT 
              `product`.`model_no`, 
              `product`.`cover_image`, 
              COUNT(*) AS total_items,
              SUM(`return_item`.`qty`) AS total_qty,
                SUM(`return_item`.`qty` * `return_item`.`price`) AS total_loss
            FROM `invoice`
            INNER JOIN `return_item` ON `invoice`.`id` = `return_item`.`invoice_id`
            INNER JOIN `product` ON `product`.`id` = `return_item`.`product_id`
            WHERE `invoice`.`id` >= '0' AND `invoice`.`is_delete` = '0' ";
            
                 
    if (isset($_POST['from'])) {
        $from = $_POST['from'] ?? null;
        $sql2 .= " AND `invoice`.`date_time` >= '$from 00:00:00'";
    }
    
    if (isset($_POST['to'])) {
        $to = $_POST['to'] ?? null;
        $sql2 .= " AND `invoice`.`date_time` <= '$to 23:59:59'";
    }
    
    if (isset($_POST['user'])) {
        if ($_POST['user'] != '' && $_POST['user'] != null) {
            $sql1 .= " AND `invoice`.`user_id` = '" . $_POST['user'] . "' ";
            $sql2 .= " AND `invoice`.`user_id` = '" . $_POST['user'] . "' ";
        }
    }

    
    $sql1 .= " GROUP BY `invoice_item`.`product_id`";
    $sql2 .= " GROUP BY `return_item`.`product_id`";
            
    $order_rs = Database::search($sql1. " ORDER BY total_qty DESC");
    
    $return_order_rs = Database::search($sql2. " ORDER BY total_qty DESC");
    

?>


    <div class="row">
        <div class="col-12 mt-3">
            <h4 class="text-white text-center">Invoiced Items</h4>
        </div>
    </div>

    <table class="" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
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
<?php

} else {
    echo "reload";
}
