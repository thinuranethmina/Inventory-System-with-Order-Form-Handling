<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['shop'])) {
        echo "Please select shop";
    } else if (empty(trim($_POST['shop'])) || $_POST['shop'] == '0') {
        echo "Please select shop";
    } else if (!isset($_POST['priceType'])) {
        echo "Please select price type";
    } else if (empty(trim($_POST['priceType'])) || $_POST['priceType'] == '0') {
        echo "Please select price type";
    } elseif (!isset($_POST['rows']) && !isset($_POST['return_table_rows'])) {
        echo "You should add minimum one product";
    } else if ((empty(trim($_POST['rows'])) || !is_numeric($_POST['rows']) || empty(trim($_POST['return_table_rows'])) || !is_numeric($_POST['return_table_rows'])) && ($_POST['rows'] > 1 || $_POST['return_table_rows'] > 1)) {
        echo "You should add minimum one product" . $_POST['return_table_rows'];
    } elseif (!isset($_POST['discount'])) {
        echo "Please enter discount value";
    } else if (($_POST['discount'] < 0 && $_POST['discount'] > 100)) {
        echo "Please enter valid discount value";
    } else if (!isset($_POST['paymentType'])) {
        echo "Please select payment type";
    } else if (empty(trim($_POST['paymentType'])) || $_POST['paymentType'] == '0') {
        echo "Please select payment type";
    } else {

        $isReady = false;

        if ($_POST['paymentType'] == '3') {
            $isReady = true;
        } else {
            if (!isset($_POST['paidAmount'])) {
                echo "Please enter paid amount";
            } else if (empty(trim($_POST['paidAmount'])) || $_POST['paidAmount'] <= 0 || !is_numeric($_POST['paidAmount'])) {
                echo "Please enter valid paid amount";
            } else {
                $isReady = true;
            }
        }

        if ($isReady) {
            $isReady = false;
            $total = 0;
            $returnTotal = 0;
            $subTotal = 0;
            $discount = 0;
            $balance = 0;

            if ((is_numeric($_POST['rows']) && $_POST['rows'] > 1) || (is_numeric($_POST['return_table_rows']) && $_POST['return_table_rows'] > 1)) {
                $shop_rs =  Database::search("SELECT * FROM `shop` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['shop']]);

                if ($shop_rs->num_rows == 1) {

                    // -------------Billing Items----------------
                    for ($i = 1; $i < $_POST['rows']; $i++) {
                        $isReady = false;
                        if (isset($_POST['p' . $i]) && isset($_POST['fqty' . $i]) && isset($_POST['sprice' . $i]) && isset($_POST['qty' . $i])) {
                            $product_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                            $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                            if ($product_rs->num_rows == 1) {
                                $product = $product_rs->fetch_assoc();
                                $price = $price_rs->fetch_assoc();


                                if ($_POST['priceType'] == '1') {
                                    if ($_POST['sprice' . $i] != null && !empty($_POST['sprice' . $i]) && $_POST['sprice' . $i] != '' && is_numeric($_POST['sprice' . $i]) && $_POST['sprice' . $i] > 0 && $_POST['sprice' . $i] != $price['cash_price']) {
                                        $subTotal += $_POST['sprice' . $i] * $_POST['qty' . $i];
                                    } else {
                                        $subTotal += $price['cash_price'] * $_POST['qty' . $i];
                                    }
                                } else if ($_POST['priceType'] == '2') {
                                    if ($_POST['sprice' . $i] != null && !empty($_POST['sprice' . $i]) && $_POST['sprice' . $i] != '' && is_numeric($_POST['sprice' . $i]) && $_POST['sprice' . $i] > 0 && $_POST['sprice' . $i] != $price['credit_price']) {
                                        $subTotal += $_POST['sprice' . $i] * $_POST['qty' . $i];
                                    } else {
                                        $subTotal += $price['credit_price'] * $_POST['qty' . $i];
                                    }
                                }

                                if ($i == $_POST['rows'] - 1) {
                                    $isReady = true;
                                }
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    // -------------Billing Items----------------


                    // -------------Return Items----------------
                    for ($i = 1; $i < $_POST['return_table_rows']; $i++) {
                        $isReady = false;
                        if (isset($_POST['rp' . $i]) && isset($_POST['rqty' . $i]) && isset($_POST['rprice' . $i])) {
                            $rproduct_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? ", "s", [$_POST['rp' . $i]]);

                            if ($rproduct_rs->num_rows == 1) {
                                $returnTotal += $_POST['rprice' . $i] * $_POST['rqty' . $i];


                                if ($i == $_POST['return_table_rows'] - 1) {
                                    $isReady = true;
                                }
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    // -------------Return Items----------------
                } else {
                    echo "Invalid shop";
                }
            } else {
                echo "Unexpected Error";
            }


            if (isset($_POST['discount'])) {
                if (is_numeric($_POST['discount'])) {
                    if ($_POST['discount'] >= 0 && $_POST['discount'] <= 100) {
                        if ($_POST['discount'] != '') {
                            $discount = $_POST['discount'];
                        }
                    } else {
                        $isReady = false;
                    }
                }
            }

            $total = ($subTotal * ((100 - $discount) / 100)) - $returnTotal;
            $balance = $total;


            if ($isReady) {

                if ($_POST['paymentType'] != '3') {
                    if (isset($_POST['paidAmount'])) {
                        if (is_numeric($_POST['paidAmount'])) {
                            if ($_POST['paidAmount'] >= 0) {
                                if ($_POST['paidAmount'] != '') {
                                    if ($total >= 0) {
                                        $balance = $total - $_POST['paidAmount'];
                                    } else {
                                        $balance = $total + $_POST['paidAmount'];
                                    }
                                    $paidAmount = $_POST['paidAmount'];
                                }
                            } else {
                                $isReady = false;
                                echo "Please enter valid paid amount";
                            }
                        } else {
                            $isReady = false;
                            echo "Please enter paid amount";
                        }
                    } else {
                        $isReady = false;
                        echo "Please enter paid amount";
                    }
                }

                if ($isReady && ($_POST['rows'] > 1 || $_POST['return_table_rows'] > 1)) {

                    $ref = time() . uniqid();

                    $note = "";

                    if (isset($_POST['note'])) {
                        $note = $_POST['note'];
                    }

                    $order_id = Database::search("SELECT * FROM `next_value` WHERE `name` = 'order_id' ")->fetch_assoc()['value'];
                    $nxt_order_id = $order_id + 1;
                    Database::iud("UPDATE `next_value` SET `value`='" . $nxt_order_id . "' ");

                    $date_time = date("Y-m-d H:i:s");

                    Database::iud("INSERT INTO `invoice`(`ref`,`order_id`,`discount`,`sub_total`,`total`,`date_time`,`shop_id`,`note`,`is_delivered`,`is_completed`,`is_credit`,`user_id`,`return_total`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?) ", "sssssssssssss", [$ref, $order_id, $discount, $subTotal, $total, $date_time, $_POST['shop'], $note, '0', $balance <= 0 ? "1" : "0", $_POST['priceType'] == 2 ? "1" : "0", $_SESSION['user']['id'], $returnTotal]);

                    $invoice_id = Database::search("SELECT `id` FROM `invoice` WHERE `ref` = '" . $ref . "' ")->fetch_assoc()['id'];

                    for ($i = 1; $i < $_POST['rows']; $i++) {
                        $product_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                        $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                        if ($product_rs->num_rows == 1) {
                            $product = $product_rs->fetch_assoc();
                            $price = $price_rs->fetch_assoc();
                            $is_special_price = "0";
                            $price_per_item = 0;

                            if ($_POST['priceType'] == '1') {
                                if ($_POST['sprice' . $i] != null && !empty($_POST['sprice' . $i]) && $_POST['sprice' . $i] != '' && is_numeric($_POST['sprice' . $i]) && $_POST['sprice' . $i] > 0 && $_POST['sprice' . $i] != $price['cash_price']) {
                                    $is_special_price = "1";
                                    $price_per_item = $_POST['sprice' . $i];
                                } else {
                                    $is_special_price = "0";
                                    $price_per_item = $price['cash_price'];
                                }
                            } else if ($_POST['priceType'] == '2') {
                                if ($_POST['sprice' . $i] != null && !empty($_POST['sprice' . $i]) && $_POST['sprice' . $i] != '' && is_numeric($_POST['sprice' . $i]) && $_POST['sprice' . $i] > 0 && $_POST['sprice' . $i] != $price['credit_price']) {
                                    $is_special_price = "1";
                                    $price_per_item = $_POST['sprice' . $i];
                                } else {
                                    $is_special_price = "0";
                                    $price_per_item = $price['credit_price'];
                                }
                            }

                            Database::iud("INSERT INTO `invoice_item`(`sold_price_per_item`,`invoice_id`,`qty`,`free_qty`,`product_id`,`is_special_price`) VALUES(?,?,?,?,?,?) ", "ssssss", [$price_per_item, $invoice_id, $_POST['qty' . $i], $_POST['fqty' . $i], $_POST['p' . $i], $is_special_price]);

                            // Dashboard Update
                            $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
                            FROM `invoice` 
                            INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
                            INNER JOIN `invoice_item` ON `invoice_item`.`invoice_id`=`invoice`.`id`  
                            WHERE  `invoice_item`.`product_id`=? AND `invoice`.`is_delivered` = '0' AND `invoice`.`is_delete` = '0' ";
                            $stock_rs = Database::search($sql, "s", [$_POST['p' . $i]]);
                            $deliver_pending_qty = 0;
                            while ($stock = $stock_rs->fetch_assoc()) {
                                $deliver_pending_qty += $stock['qty'] + $stock['free_qty'];
                            }
                            Database::iud("UPDATE `stock` SET `deliver_pending_qty`=?  WHERE `product_id`=? ", "ss", [$deliver_pending_qty, $_POST['p' . $i]]);



                            // if ($i == $_POST['rows'] - 1) {
                            // }


                        } else {
                            break;
                        }
                    }

                    for ($i = 1; $i < $_POST['return_table_rows']; $i++) {
                        $rproduct_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? ", "s", [$_POST['rp' . $i]]);
                        if ($rproduct_rs->num_rows == 1) {

                            Database::iud("INSERT INTO `return_item`(`price`,`qty`,`product_id`,`invoice_id`) VALUES(?,?,?,?) ", "ssss", [$_POST['rprice' . $i], $_POST['rqty' . $i], $_POST['rp' . $i], $invoice_id]);


                            // if ($i == $_POST['return_table_rows'] - 1) {
                            // }


                        } else {
                            break;
                        }
                    }

                    if ($_POST['paymentType'] != '3') {

                        Database::iud("INSERT INTO `invoice_payment`(`paid_amount`,`balance`,`date_time`,`invoice_id`,`payment_type_id`,`is_additional_amount`) VALUES(?,?,?,?,?,'0') ", "sssss", [$paidAmount, $balance, $date_time, $invoice_id, $_POST['paymentType']]);
                    }

                    require "stockUpdateController.php";

                    require "../util/activity.php";

                    Activity::newActivity("added new order(OrderID: " . $order_id . ").", 1, "Please add href");

                    echo "success";
                }
            } else {
                echo "Unexpected Error";
            }
        }
    }
} else {
    echo "reload";
}
