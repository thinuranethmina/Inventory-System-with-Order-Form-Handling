<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['rows'])) {
        if (is_numeric($_POST['rows']) && $_POST['rows'] > 1) {

            $responce = new stdClass();

            $total = 0;
            $subTotal = 0;
            $discount = 0;
            $balance = 0;
            $paidAmount = 0;
            $isReady = false;


            for ($i = 1; $i < $_POST['rows']; $i++) {
                if (isset($_POST['p' . $i]) && isset($_POST['qty' . $i]) && isset($_POST['price' . $i])) {
                    $product_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                    $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                    if ($product_rs->num_rows == 1) {
                        $product = $product_rs->fetch_assoc();
                        $price = $price_rs->fetch_assoc();

                        $subTotal += $_POST['price' . $i] * $_POST['qty' . $i];

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

            if (isset($_POST['paidAmount'])) {
                if (is_numeric($_POST['paidAmount'])) {
                    if ($_POST['paidAmount'] >= 0) {
                        if ($_POST['paidAmount'] != '') {
                            $balance = $total - $_POST['paidAmount'];
                            $paidAmount = $_POST['paidAmount'];
                        }
                    } else {
                        $isReady = false;
                    }
                }
            }

            if ($isReady) {
                $responce->status = "success";


                $responce->total = "Rs." . number_format($total, 2) . "/=";
                $responce->balance = "Rs." . number_format($balance, 2) . "/=";
                $responce->discount = $discount;
                $responce->paidAmount = $paidAmount;
            } else {
                $responce->status = "error";
            }


            echo json_encode($responce);
        }
    }
} else {
    echo "reload";
}
