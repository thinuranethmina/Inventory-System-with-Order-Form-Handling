<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['rows'])) {
        echo "You should add minimum one product";
    } else if (empty(trim($_POST['rows'])) || $_POST['rows'] <= '1' || !is_numeric($_POST['rows'])) {
        echo "You should add minimum one product";
    } elseif (!isset($_POST['discount'])) {
        echo "Please enter discount value";
    } else if (($_POST['discount'] < 0 && $_POST['discount'] > 100)) {
        echo "Please enter valid discount value";
    } else {

        $isReady = false;

        if (!isset($_POST['paidAmount'])) {
            echo "Please enter paid amount";
        } else if (empty(trim($_POST['paidAmount'])) || $_POST['paidAmount'] <= 0 || !is_numeric($_POST['paidAmount'])) {
            echo "Please enter valid paid amount";
        } else {
            $isReady = true;
        }


        if ($isReady) {
            $isReady = false;
            $total = 0;
            $subTotal = 0;
            $discount = 0;
            $balance = 0;

            if (is_numeric($_POST['rows']) && $_POST['rows'] > 1) {

                for ($i = 1; $i < $_POST['rows']; $i++) {
                    if (isset($_POST['p' . $i]) && isset($_POST['fqty' . $i]) && isset($_POST['qty' . $i]) && isset($_POST['pqty' . $i]) && isset($_POST['sprice' . $i]) && isset($_POST['oqty' . $i])) {
                        $product_rs =  Database::search("SELECT * FROM `product` INNER JOIN `stock` ON `stock`.`product_id`=`product`.`id` WHERE `product`.`id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                        $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                        if ($product_rs->num_rows == 1) {
                            $product = $product_rs->fetch_assoc();
                            $price = $price_rs->fetch_assoc();

                            if ($_POST['sprice' . $i] != null && !empty($_POST['sprice' . $i]) && $_POST['sprice' . $i] != '' && is_numeric($_POST['sprice' . $i]) && $_POST['sprice' . $i] > 0 && $_POST['sprice' . $i] != $price['retail_price']) {
                                $subTotal += $_POST['sprice' . $i] * $_POST['qty' . $i];
                            } else {
                                $subTotal += $price['retail_price'] * $_POST['qty' . $i];
                            }

                            if ($_POST['pqty' . $i] > $product['qty']) {
                                echo "Not enough primary stock";
                                break;
                            }

                            if ($_POST['oqty' . $i] > $product['ongoing_qty']) {
                                echo "Not enough ongoing stock";
                                break;
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

            $total = $subTotal * ((100 - $discount) / 100);
            $balance = $total;


            if ($isReady) {

                if (isset($_POST['paidAmount'])) {
                    if (is_numeric($_POST['paidAmount'])) {
                        if ($_POST['paidAmount'] >= 0) {
                            if ($_POST['paidAmount'] != '') {
                                $balance = $total - $_POST['paidAmount'];
                                $paidAmount = $_POST['paidAmount'];

                                if ($balance > 0) {
                                    $isReady = false;
                                    echo "Not paid full amount";
                                }
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


                if ($isReady && $_POST['rows'] > 1) {

                    $ref = time() . uniqid();

                    $note = "";

                    if (isset($_POST['note'])) {
                        $note = $_POST['note'];
                    }

                    require "../util/activity.php";

                    $order_id = Database::search("SELECT * FROM `next_value` WHERE `name` = 'order_id' ")->fetch_assoc()['value'];
                    $nxt_order_id = $order_id + 1;
                    Database::iud("UPDATE `next_value` SET `value`='" . $nxt_order_id . "' ");

                    $date_time = date("Y-m-d H:i:s");

                    Database::iud("INSERT INTO `invoice`(`ref`,`order_id`,`discount`,`sub_total`,`total`,`date_time`,`shop_id`,`note`,`is_delivered`,`is_credit`,`is_completed`,`user_id`,`return_total`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?) ", "sssssssssssss", [$ref, $order_id, $discount, $subTotal, $total, $date_time, '0', $note, 1, 0,  "1", $_SESSION['user']['id'], '0']);

                    $invoice_id = Database::search("SELECT `id` FROM `invoice` WHERE `ref` = '" . $ref . "' ")->fetch_assoc()['id'];

                    for ($i = 1; $i < $_POST['rows']; $i++) {
                        $product_rs =  Database::search("SELECT *, product.id AS id FROM `product` INNER JOIN `stock` ON `stock`.`product_id`=`product`.`id`  WHERE `product`.`id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                        $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                        if ($product_rs->num_rows == 1) {
                            $product = $product_rs->fetch_assoc();
                            $price = $price_rs->fetch_assoc();
                            $is_special_price = "0";
                            $price_per_item = 0;

                            if ($_POST['sprice' . $i] != null && !empty($_POST['sprice' . $i]) && $_POST['sprice' . $i] != '' && is_numeric($_POST['sprice' . $i]) && $_POST['sprice' . $i] > 0 && $_POST['sprice' . $i] != $price['retail_price']) {
                                $is_special_price = "1";
                                $price_per_item = $_POST['sprice' . $i];
                            } else {
                                $is_special_price = "0";
                                $price_per_item = $price['retail_price'];
                            }

                            Database::iud("INSERT INTO `invoice_item`(`sold_price_per_item`,`invoice_id`,`qty`,`free_qty`,`product_id`,`is_special_price`) VALUES(?,?,?,?,?,?) ", "ssssss", [$price_per_item, $invoice_id, $_POST['qty' . $i], $_POST['fqty' . $i], $_POST['p' . $i], $is_special_price]);

                            if ($_POST['pqty' . $i] > 0) {
                                Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`note`,`stock_type_id`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['pqty' . $i], $product['qty'], $product['qty'] - $_POST['pqty' . $i], $date_time, $product['id'], $_SESSION['user']['id'], '3', 'Deduct for OrderID:' . $order_id . ' order.', '1']);
                                Database::iud("UPDATE `stock` SET `qty`=? WHERE `product_id`=? ", "ss", [$product['qty'] - $_POST['pqty' . $i], $product['id']]);

                                Activity::newActivity('deduct primary stock ' . $product['model_no'] . ' for OrderID:' . $order_id . ' .', 1, "Please add href");
                            }

                            if ($_POST['oqty' . $i] > 0) {
                                Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`note`,`stock_type_id`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['oqty' . $i], $product['ongoing_qty'], $product['ongoing_qty'] - $_POST['oqty' . $i], $date_time, $product['id'], $_SESSION['user']['id'], '3', 'Deduct for OrderID:' . $order_id . ' order.', '2']);
                                Database::iud("UPDATE `stock` SET `ongoing_qty`=? WHERE `product_id`=? ", "ss", [$product['ongoing_qty'] - $_POST['oqty' . $i], $product['id']]);

                                Activity::newActivity('deduct ongoing stock ' . $product['model_no'] . ' for OrderID:' . $order_id . ' .', 1, "Please add href");
                            }

                            if ($i == $_POST['rows'] - 1) {

                                Database::iud("INSERT INTO `invoice_payment`(`paid_amount`,`balance`,`date_time`,`invoice_id`,`payment_type_id`,`is_additional_amount`) VALUES(?,?,?,?,?,?) ", "ssssss", [$paidAmount, $balance, $date_time, $invoice_id, '1', 0]);

                                Activity::newActivity("added new retail order(OrderID:" . $order_id . ").", 1, "Please add href");

                                echo "success";
                            }
                        } else {
                            break;
                        }
                    }
                }
            } else {
                echo "Unexpected Error";
            }
        }
    }
} else {
    echo "reload";
}
