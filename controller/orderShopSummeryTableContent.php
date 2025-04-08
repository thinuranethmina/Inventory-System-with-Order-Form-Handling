<?php


require "util/userStatus.php";

if (User::is_allow()) {

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
                            WHERE `invoice`.`id` >= 0 
                            AND `invoice`.`is_delete` = 0";
              
              
    if (isset($_POST['from'])) {
        $from = $_POST['from'] ?? null;
        $sql .= " AND `invoice`.`date_time` >= '$from 00:00:00'";
    }
    
    if (isset($_POST['to'])) {
        $to = $_POST['to'] ?? null;
        $sql .= " AND `invoice`.`date_time` <= '$to 23:59:59'";
    }
    
    if (isset($_POST['user'])) {
        if ($_POST['user'] != '' && $_POST['user'] != null) {
            $sql .= " AND `invoice`.`user_id` = '" . $_POST['user'] . "' ";
        }
    }
    
    $sql .= " GROUP BY invoice.id";
            
    $order_rs = Database::search($sql. " ORDER BY invoice.order_id DESC");
    

?>


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
<?php

} else {
    echo "reload";
}
