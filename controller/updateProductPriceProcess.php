<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['id'])) {
        echo "Unexpected error";
    } else if (!isset($_POST['rprice'])) {
        echo "Please enter retail price";
    } else if (empty(trim($_POST['rprice']))) {
        echo "Please enter retail price";
    } else if (!is_numeric(trim($_POST['rprice']))) {
        echo "Please enter retail price";
    } else if ($_POST['rprice'] < 0) {
        echo "Invalid retail price";
    } else if (!isset($_POST['creditPrice'])) {
        echo "Please enter credit price";
    } else if (empty(trim($_POST['creditPrice']))) {
        echo "Please enter credit price";
    } else if (!is_numeric(trim($_POST['creditPrice']))) {
        echo "Please enter credit price";
    } else if ($_POST['creditPrice'] < 0) {
        echo "Invalid credit price";
    } else if (!isset($_POST['cashPrice'])) {
        echo "Please enter cash price";
    } else if (empty(trim($_POST['cashPrice']))) {
        echo "Please enter cash price";
    } else if (!is_numeric(trim($_POST['cashPrice']))) {
        echo "Please enter cash price";
    } else if ($_POST['cashPrice'] < 0) {
        echo "Invalid cash price";
    } else if ($_POST['cashPrice'] >= $_POST['rprice'] || $_POST['creditPrice'] >= $_POST['rprice']) {
        echo "Invalid prices";
    } else {

        $product_rs = Database::search("SELECT * FROM `product` WHERE `id` = ? ", "s", [$_POST['id']]);

        if ($product_rs->num_rows == 1) {

            $product = $product_rs->fetch_assoc();

            $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['id']]);

            $price = $price_rs->fetch_assoc();

            if ($price['retail_price'] == $_POST['rprice'] && $price['credit_price'] == $_POST['creditPrice'] && $price['cash_price'] == $_POST['cashPrice']) {
                echo "No need to update price.";
            } else {
                $date_time = date("Y-m-d H:i:s");

                Database::iud("INSERT INTO `price`(`retail_price`,`credit_price`,`cash_price`,`date_time`,`product_id`) VALUES (?,?,?,?,?)", "sssss", [$_POST['rprice'], $_POST['creditPrice'], $_POST['cashPrice'], $date_time, $_POST['id']]);

                require "../util/activity.php";

                Activity::newActivity("updated product(" . $product['model_no'] . ") price.", 1);

                echo "success";
            }
        } else {
            echo "Unexpected error";
        }
    }
} else {
    echo "reload";
}
