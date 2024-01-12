<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['rows']) && isset($_POST['priceType'])) {
        if ((is_numeric($_POST['rows']) && $_POST['rows'] > 1) || (is_numeric($_POST['return_table_rows']) && $_POST['return_table_rows'] > 1)) {

            $responce = new stdClass();

            $total = 0;
            $subTotal = 0;
            $returnTotal = 0;
            $discount = 0;
            $balance = 0;
            $isReady = false;


            for ($i = 1; $i < $_POST['rows']; $i++) {
                $isReady = false;
                if (isset($_POST['p' . $i]) && isset($_POST['qty' . $i]) && isset($_POST['price' . $i])) {
                    $product_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                    $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                    if ($product_rs->num_rows == 1) {
                        $product = $product_rs->fetch_assoc();
                        $price = $price_rs->fetch_assoc();


                        if ($_POST['priceType'] == '1') {
                            if ($_POST['price' . $i] != null && !empty($_POST['price' . $i]) && $_POST['price' . $i] != '' && is_numeric($_POST['price' . $i]) && $_POST['price' . $i] > 0 && $_POST['price' . $i] != $price['cash_price']) {
                                $subTotal += $_POST['price' . $i] * $_POST['qty' . $i];
                            } else {
                                $subTotal += $price['cash_price'] * $_POST['qty' . $i];
                            }
                        } else if ($_POST['priceType'] == '2') {
                            if ($_POST['price' . $i] != null && !empty($_POST['price' . $i]) && $_POST['price' . $i] != '' && is_numeric($_POST['price' . $i]) && $_POST['price' . $i] > 0 && $_POST['price' . $i] != $price['credit_price']) {
                                $subTotal += $_POST['price' . $i] * $_POST['qty' . $i];
                            } else {
                                $subTotal += $price['credit_price'] * $_POST['qty' . $i];
                            }
                        }

                        // $subTotal += $price['wholesale_price'] * $_POST['qty' . $i];

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

            $total = ($subTotal * ((100 - $discount) / 100)) - ($returnTotal);
            $balance = $total;



            if ($isReady) {
                $responce->status = "success";


                $responce->total = "Rs." . number_format($total, 2) . "/=";
                $responce->balance = "Rs." . number_format($balance, 2) . "/=";
                $responce->return = "Rs." . number_format($returnTotal, 2) . "/=";
                $responce->discount = $discount;
            } else {
                $responce->status = "error";
            }


            echo json_encode($responce);
        }
    }
} else {
    echo "reload";
}
