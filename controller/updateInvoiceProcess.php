<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['priceType'])) {
        echo "Please select price type";
    } else if (empty(trim($_POST['priceType'])) || $_POST['priceType'] == '0') {
        echo "Please select price type";
    } elseif (!isset($_POST['rows']) && !isset($_POST['return_table_rows'])) {
        echo "You should add minimum one product";
    } else if ((empty(trim($_POST['rows'])) || !is_numeric($_POST['rows']) || empty(trim($_POST['return_table_rows'])) || !is_numeric($_POST['return_table_rows'])) && ($_POST['rows'] > 1 || $_POST['return_table_rows'] > 1)) {
        echo "You should add minimum one product" . $_POST['return_table_rows'];
    } else {


        $invoice_rs = Database::search("SELECT *,`invoice`.`id` AS `id`,`shop`.`id` AS `shopID`,`shop`.`name` AS `shop`, `invoice`.`date_time` AS `date_time`, `invoice`.`note` AS `invoiceNote` , `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province`  FROM `invoice` INNER JOIN `shop`  ON `shop`.`id` = `invoice`.`shop_id`  INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id`  WHERE `invoice`.`id` = ? AND `is_delete` = '0' AND `shop`.`id`!= '0' ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {
            $invoice = $invoice_rs->fetch_assoc();
            $isReady = false;
            $total = 0;
            $returnTotal = 0;
            $subTotal = 0;
            $discount = 0;
            $balance = 0;

            if ($_SESSION['user']['user_type'] == 3 || $_SESSION['user']['user_type'] == 4) {
                $$currentDateTime = new DateTime();
                $targetDateTime = new DateTime($invoice['date_time']);

                $interval = $currentDateTime->diff($targetDateTime);

                if ($interval->days >= 1) {
                    echo "Your time has been expired. Please contact system adminstratorfor any change of this order form.";
                } else {
                    $isReady = true;
                }
            } else {
                $isReady = true;
            }

            if ($isReady) {
                $isReady = false;

                if ((is_numeric($_POST['rows']) && $_POST['rows'] > 1) || (is_numeric($_POST['return_table_rows']) && $_POST['return_table_rows'] > 1)) {

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
                    echo "Unexpected Error";
                }
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

                if ($_POST['rows'] > 1 || $_POST['return_table_rows'] > 1) {

                    $note = "";

                    if (isset($_POST['note'])) {
                        $note = $_POST['note'];
                    }

                    // $order_id = Database::search("SELECT * FROM `next_value` WHERE `name` = 'order_id' ")->fetch_assoc()['value'];
                    // $nxt_order_id = $order_id + 1;
                    // Database::iud("UPDATE `next_value` SET `value`='" . $nxt_order_id . "' ");

                    // $date_time = date("Y-m-d H:i:s");

                    $invoice_id = $invoice['id'];

                    $payments_rs = Database::search("SELECT * FROM `invoice_payment` WHERE `invoice_id` = ? ORDER BY `date_time` ASC ", "s", [$invoice_id]);

                    while ($payments = $payments_rs->fetch_assoc()) {
                        if ($payments['is_additional_amount'] == 1) {
                            $balance = $balance + $payments['paid_amount'];
                        } else {
                            $balance = $balance - $payments['paid_amount'];
                        }
                        Database::iud("UPDATE `invoice_payment` SET `balance` = ? WHERE `id` = ? ", "ss", [$balance, $payments['id']]);
                    }

                    Database::iud("UPDATE `invoice` SET `discount` = ? ,`sub_total`=?,`total`=?,`note`=?,`is_credit`=?,`return_total`=?,`is_completed`=? WHERE `id` = ? ", "ssssssss", [$discount, $subTotal, $total, $note, $_POST['priceType'] == 2 ? "1" : "0", $returnTotal, $balance <= 0 ? "1" : "0",  $invoice_id]);

                    Database::iud("DELETE FROM `invoice_item` WHERE `invoice_id` = ?  ", "s", [$invoice_id]);

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


                    Database::iud("DELETE FROM `return_item` WHERE `invoice_id` = ?  ", "s", [$invoice_id]);

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

                    // if ($_POST['paymentType'] != '3') {

                    //     Database::iud("INSERT INTO `invoice_payment`(`paid_amount`,`balance`,`date_time`,`invoice_id`,`payment_type_id`,`is_additional_amount`) VALUES(?,?,?,?,?,'0') ", "sssss", [$paidAmount, $balance, $date_time, $invoice_id, $_POST['paymentType']]);
                    // }

                    require "stockUpdateController.php";

                    require "../util/activity.php";

                    Activity::newActivity("make some change on OrderID: " . $invoice['order_id'] . ".", 1, "Please add href");

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
