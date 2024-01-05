<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['id'])) {

        $invoice_rs = Database::search("SELECT * FROM `invoice` WHERE `id` = ? ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {

            $invoice = $invoice_rs->fetch_assoc();

            if ($invoice['is_delivered'] != 1) {

                $isReady = true;
                $msg = "";

                $invoice_item_check_rs = Database::search("SELECT *,`invoice_item`.`id` AS `id` ,`invoice_item`.`qty` AS `qty` ,`stock`.`qty` AS `pstock`,`product`.`id` AS `product` FROM `invoice_item` INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id` INNER JOIN `stock` ON `stock`.`product_id`=`product`.`id` WHERE `invoice_item`.`invoice_id` = ? ", "s", [$_POST['id']]);

                while ($invoice_item = $invoice_item_check_rs->fetch_assoc()) {

                    if (isset($_POST['pqty' . $invoice_item['id']]) && isset($_POST['oqty' . $invoice_item['id']])) {

                        if (empty($_POST['pqty' . $invoice_item['id']])) {
                            $_POST['pqty' . $invoice_item['id']] = 0;
                        }
                        if (empty($_POST['oqty' . $invoice_item['id']])) {
                            $_POST['oqty' . $invoice_item['id']] = 0;
                        }

                        $isOkayDoubleCheck = true;

                        if (!is_numeric($_POST['pqty' . $invoice_item['id']]) || $_POST['pqty' . $invoice_item['id']] > $invoice_item['pstock']) {
                            if ($isReady) {
                                $msg .= $invoice_item['model_no'] . '(primary)';
                            } else {
                                $msg .= ", " . $invoice_item['model_no'] . '(primary)';
                            }
                            $isReady = false;
                        } else if ($invoice_item['qty'] + $invoice_item['free_qty'] > $invoice_item['pstock'] + $invoice_item['ongoing_qty']) {
                            $isOkayDoubleCheck = false;
                            if ($isReady) {
                                $msg .= $invoice_item['model_no'] . '(primary)';
                            } else {
                                $msg .= ", " . $invoice_item['model_no'] . '(primary)';
                            }
                            $isReady = false;
                        }

                        if (!is_numeric($_POST['oqty' . $invoice_item['id']]) || $_POST['oqty' . $invoice_item['id']] > $invoice_item['ongoing_qty']) {
                            if ($isReady) {
                                $msg .= $invoice_item['model_no'] . '(ongoing)';
                            } else {
                                $msg .= ", " . $invoice_item['model_no'] . '(ongoing)';
                            }
                            $isReady = false;
                        } else if ($invoice_item['qty'] + $invoice_item['free_qty'] > $invoice_item['pstock'] + $invoice_item['ongoing_qty']) {
                            if ($isOkayDoubleCheck) {
                                if ($isReady) {
                                    $msg .= $invoice_item['model_no'] . '(ongoing)';
                                } else {
                                    $msg .= ", " . $invoice_item['model_no'] . '(ongoing)';
                                }
                                $isReady = false;
                            }
                        }
                    } else {
                        if ($isReady) {
                            $msg .= $invoice_item['model_no'];
                        } else {
                            $msg .= ", " . $invoice_item['model_no'];
                        }
                        $isReady = false;
                    }
                }


                if ($isReady) {

                    $invoice_item_check_rs = Database::search("SELECT *,`invoice_item`.`id` AS `id` ,`invoice_item`.`qty` AS `qty` ,`stock`.`qty` AS `pstock`,`product`.`id` AS `product` FROM `invoice_item` INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id` INNER JOIN `stock` ON `stock`.`product_id`=`product`.`id` WHERE `invoice_item`.`invoice_id` = ? ", "s", [$_POST['id']]);

                    $msg2 = "";

                    while ($invoice_item = $invoice_item_check_rs->fetch_assoc()) {
                        if ($invoice_item['qty'] + $invoice_item['free_qty'] != $_POST['pqty' . $invoice_item['id']] + $_POST['oqty' . $invoice_item['id']]) {
                            if ($isReady) {
                                $msg2 .= $invoice_item['model_no'];
                            } else {
                                $msg2 .= ", " . $invoice_item['model_no'];
                            }
                            $isReady = false;
                        }
                    }

                    if ($isReady) {
                        require "../util/activity.php";
                        Activity::newActivity('marked as delivered OrderID:' . $invoice['order_id'] . ': order.', 1, "view-order.php?id=" . $_POST['id']);


                        $invoice_item_rs = Database::search("SELECT *,`invoice_item`.`id` AS `id`,`invoice_item`.`qty` AS `qty` ,`stock`.`qty` AS `pstock`,`product`.`id` AS `product` FROM `invoice_item` INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id` INNER JOIN `stock` ON `stock`.`product_id`=`product`.`id` WHERE `invoice_item`.`invoice_id` = ? ", "s", [$_POST['id']]);

                        while ($invoice_item = $invoice_item_rs->fetch_assoc()) {

                            $date_time = date("Y-m-d H:i:s");

                            $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
                                    FROM `invoice` 
                                    INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
                                    INNER JOIN `invoice_item` ON `invoice_item`.`invoice_id`=`invoice`.`id`  
                                    WHERE  `invoice_item`.`product_id`=? AND `invoice`.`is_delivered` = '0' AND `invoice`.`id` != ?  AND `invoice`.`is_delete` = '0' ";
                            $stock_rs = Database::search($sql, "ss", [$invoice_item['product'], $_POST['id']]);
                            $deliver_pending_qty = 0;
                            while ($stock = $stock_rs->fetch_assoc()) {
                                $deliver_pending_qty += $stock['qty'] + $stock['free_qty'];
                            }

                            if ($_POST['pqty' . $invoice_item['id']] > 0) {
                                Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`note`,`stock_type_id`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['pqty' . $invoice_item['id']], $invoice_item['pstock'], $invoice_item['pstock'] - $_POST['pqty' . $invoice_item['id']], $date_time, $invoice_item['product'], $_SESSION['user']['id'], '3', 'Deduct for OrderID:' . $invoice['order_id'] . ' order.', '1']);
                                Database::iud("UPDATE `stock` SET `qty`=?,`deliver_pending_qty`=? WHERE `product_id`=? ", "sss", [$invoice_item['pstock'] - $_POST['pqty' . $invoice_item['id']], $deliver_pending_qty, $invoice_item['product']]);

                                Activity::newActivity('deduct primary stock ' . $invoice_item['model_no'] . ' for OrderID:' . $invoice['order_id'] . ' .', 1, "Please add href");
                            }

                            if ($_POST['oqty' . $invoice_item['id']] > 0) {
                                Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`note`,`stock_type_id`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['oqty' . $invoice_item['id']], $invoice_item['ongoing_qty'], $invoice_item['ongoing_qty'] - $_POST['oqty' . $invoice_item['id']], $date_time, $invoice_item['product'], $_SESSION['user']['id'], '3', 'Deduct for OrderID:' . $invoice['order_id'] . ' order.', '2']);
                                Database::iud("UPDATE `stock` SET `ongoing_qty`=?,`deliver_pending_qty`=? WHERE `product_id`=? ", "sss", [$invoice_item['ongoing_qty'] - $_POST['oqty' . $invoice_item['id']], $deliver_pending_qty,  $invoice_item['product']]);

                                Activity::newActivity('deduct ongoing stock ' . $invoice_item['model_no'] . ' for OrderID:' . $invoice['order_id'] . ' .', 1, "Please add href");
                            }
                        }

                        Database::iud("UPDATE `invoice` SET `is_delivered`= '1' WHERE `id` = ? ", "s", [$_POST['id']]);

                        require "stockUpdateController.php";

                        echo "success";
                    } else {
                        echo "Not maching stock qty for " . $msg2 . " product(s).";
                    }
                } else {
                    echo "Not enough stock for " . $msg . " product(s).";
                }
            } else {
                echo "This invoice already marked as delivered";
            }
        } else {
            echo "Unexpected error";
        }
    }
} else {
    echo "reload";
}
