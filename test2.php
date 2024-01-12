<?php

require "config/connection.php";
sleep(0);

$product_rs = Database::search("SELECT * FROM `product` ");

while ($product = $product_rs->fetch_assoc()) {

    // Dashboard Update
    $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
                            FROM `invoice` 
                            INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
                            INNER JOIN `invoice_item` ON `invoice_item`.`invoice_id`=`invoice`.`id`  
                            WHERE  `invoice_item`.`product_id`=? AND `invoice`.`is_delivered` = '0' AND `invoice`.`is_delete` = '0' ";
    $stock_rs = Database::search($sql, "s", [$product['id']]);
    $deliver_pending_qty = 0;
    $detail = "";
    while ($stock = $stock_rs->fetch_assoc()) {
        $deliver_pending_qty += $stock['qty'] + $stock['free_qty'];
        $detail .= $stock['shop'] . "(" . $deliver_pending_qty . ")<br/>";
    }
    Database::iud("UPDATE `stock` SET `deliver_pending_qty`=?, `details` = ?  WHERE `product_id`=? ", "sss", [$deliver_pending_qty, $detail, $product['id']]);
}
