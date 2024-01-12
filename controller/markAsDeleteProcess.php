<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['id'])) {

        $invoice_rs = Database::search("SELECT * FROM `invoice` WHERE `id` = ? ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {

            $invoice = $invoice_rs->fetch_assoc();

            $invoice_rs = Database::iud("UPDATE `invoice` SET `is_delete`='1' WHERE `id` = ? ", "s", [$_POST['id']]);

            echo "success";

            require "../util/activity.php";
            Activity::newActivity('deleted Order. OrderID:' . $invoice['order_id'] . '', 1, "view-order.php?id=" . $_POST['id']);
        } else {
            echo "Unexpected error";
        }



        // $product_rs = Database::search("SELECT * FROM `product` ");

        // while ($product = $product_rs->fetch_assoc()) {

        //     // Dashboard Update
        //     $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
        //                     FROM `invoice` 
        //                     INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
        //                     INNER JOIN `invoice_item` ON `invoice_item`.`invoice_id`=`invoice`.`id`  
        //                     WHERE  `invoice_item`.`product_id`=? AND `invoice`.`is_delivered` = '0' AND `invoice`.`is_delete` = '0' ";
        //     $stock_rs = Database::search($sql, "s", [$product['id']]);
        //     $deliver_pending_qty = 0;
        //     while ($stock = $stock_rs->fetch_assoc()) {
        //         $deliver_pending_qty += $stock['qty'] + $stock['free_qty'];
        //     }
        //     Database::iud("UPDATE `stock` SET `deliver_pending_qty`=?  WHERE `product_id`=? ", "ss", [$deliver_pending_qty, $product['id']]);
        // }

        require "stockUpdateController.php";
    }
} else {
    echo "reload";
}
