<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['stockType'])) {
        echo "Please select stock";
    } else if (trim($_POST['stockType']) == 0 || empty(trim($_POST['stockType']))) {
        echo "Please select stock";
    } else if (!isset($_POST['product'])) {
        echo "Please select product";
    } else if (empty(trim($_POST['product']))) {
        echo "Please select product";
    } else if (trim($_POST['product']) == 0) {
        echo "Please select product";
    } else if (!isset($_POST['operation'])) {
        echo "Please select operation";
    } else if (empty(trim($_POST['operation']))) {
        echo "Please select operation";
    } else if (trim($_POST['operation']) == 0) {
        echo "Please select operation";
    } else if (!isset($_POST['cqty'])) {
        echo "Please enter changing  qty";
    } else if (empty(trim($_POST['cqty']))) {
        echo "Please enter changing  qty";
    } else if (!is_numeric($_POST['cqty'])) {
        echo "Invalid changing qty";
    } else if ($_POST['cqty'] <= 0) {
        echo "Invalid changing qty";
    } else {
        $isReady = false;

        if ($_POST['operation'] == 2) {
            if (!isset($_POST['note'])) {
                echo "Please enter the reason in the note";
            } else if (empty(trim($_POST['note']))) {
                echo "Please enter the reason in the note";
            } else if ($_POST['note'] == '<p><br></p>' || empty(trim($_POST['note'], "<p> </p>"))) {
                echo "Please enter the reason in the note";
            } else {
                $isReady = true;
            }
        } else {
            $isReady = true;
        }


        if ($isReady) {
            $isReady = false;

            $product_rs = Database::search("SELECT * FROM `stock` INNER JOIN `product` ON `product`.`id` = `stock`.`product_id` WHERE `product_id` = ? ", "s", [$_POST['product']]);

            if ($product_rs->num_rows == 1) {
                $product = $product_rs->fetch_assoc();

                if ($_POST['stockType'] == '1') {
                    $available_qty = $product['qty'];
                } else {
                    $available_qty = $product['ongoing_qty'];
                }



                $total_qty = $available_qty;
                $msg = "updated " . $product['model_no'] . " primary stock.";

                if ($_POST['operation'] == '1' && $_POST['stockType'] == '1') {
                    $total_qty = $available_qty + $_POST['cqty'];
                    $msg = "updated " . $product['model_no'] . " primary stock (Added " . $_POST['cqty'] . " items).";
                    $isReady = true;
                } else if ($_POST['operation'] == '2' && $_POST['stockType'] == '1') {
                    $total_qty = $available_qty - $_POST['cqty'];
                    $msg = "updated " . $product['model_no'] . " primary stock (Removed " . $_POST['cqty'] . " items).";
                    $isReady = true;
                } else if ($_POST['operation'] == '5' && $_POST['stockType'] == '1') {
                    $total_qty = $available_qty - $_POST['cqty'];
                    $msg = "updated " . $product['model_no'] . " primary stock (move to ongoing stock " . $_POST['cqty'] . " items).";
                    $isReady = true;
                } else if ($_POST['operation'] == '1' && $_POST['stockType'] == '2') {
                    $total_qty = $available_qty + $_POST['cqty'];
                    $msg = "updated " . $product['model_no'] . " ongoing stock (Added " . $_POST['cqty'] . " items).";
                    $isReady = true;
                } else if ($_POST['operation'] == '2' && $_POST['stockType'] == '2') {
                    $total_qty = $available_qty - $_POST['cqty'];
                    $msg = "updated " . $product['model_no'] . " ongoing stock (Removed " . $_POST['cqty'] . " items).";
                    $isReady = true;
                } else if ($_POST['operation'] == '4' && $_POST['stockType'] == '2') {
                    $total_qty = $available_qty - $_POST['cqty'];
                    $msg = "updated " . $product['model_no'] . " ongoing stock (move to primary stock " . $_POST['cqty'] . " items).";
                    $isReady = true;
                } else {
                    echo "Unexpected error";
                }

                if ($isReady) {
                    if ($total_qty >= 0) {
                        $date_time = date("Y-m-d H:i:s");

                        Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`stock_type_id`,`note`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['cqty'], $available_qty, $total_qty, $date_time, $_POST['product'], $_SESSION['user']['id'], $_POST['operation'], $_POST['stockType'], $_POST['note']]);

                        if ($_POST['stockType'] == '1') {
                            Database::iud("UPDATE `stock` SET `qty`=? WHERE `product_id`=? ", "ss", [$total_qty, $_POST['product']]);
                        } else {
                            Database::iud("UPDATE `stock` SET `ongoing_qty`=? WHERE `product_id`=? ", "ss", [$total_qty, $_POST['product']]);
                        }

                        if ($_POST['operation'] == '4' && $_POST['stockType'] == '2') {
                            $note = "Updated " . $product['model_no'] . " primary stock (addded from ongoing stock " . $_POST['cqty'] . " items).";
                            Database::iud("UPDATE `stock` SET `qty`=? WHERE `product_id`=? ", "ss", [$product['qty'] + $_POST['cqty'], $_POST['product']]);

                            Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`stock_type_id`,`note`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['cqty'], $product['qty'], $product['qty'] + $_POST['cqty'], $date_time, $_POST['product'], $_SESSION['user']['id'], '4', '1', $note]);
                        }

                        if ($_POST['operation'] == '5' && $_POST['stockType'] == '1') {
                            $note = "Updated " . $product['model_no'] . " ongoing stock (addded from primary stock " . $_POST['cqty'] . " items).";
                            Database::iud("UPDATE `stock` SET `ongoing_qty`=? WHERE `product_id`=? ", "ss", [$product['ongoing_qty'] + $_POST['cqty'], $_POST['product']]);

                            Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`stock_type_id`,`note`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['cqty'], $product['qty'], $product['qty'] + $_POST['cqty'], $date_time, $_POST['product'], $_SESSION['user']['id'], '5', '2', $note]);
                        }

                        require "../util/activity.php";

                        Activity::newActivity($msg, 1, "Please add href");

                        echo "success";
                    } else {
                        echo "Stock can't be less than zero";
                    }
                }
            } else {
                echo "Unexpected error";
            }
        }
    }
} else {
    echo "reload";
}
