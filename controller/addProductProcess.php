<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['title'])) {
        echo "Please enter title";
    } else if (empty(trim($_POST['title']))) {
        echo "Please enter title";
    } else if (strlen($_POST['title']) > 100) {
        echo "Title should have maximum 100 characters";
    } else if (!isset($_POST['category'])) {
        echo "Please select category";
    } else if ($_POST['category'] == '0' || $_POST['category'] == null || empty($_POST['category']) || $_POST['category'] == '') {
        echo "Please select category";
    } else if (!isset($_POST['model'])) {
        echo "Please enter model";
    } else if (empty(trim($_POST['model']))) {
        echo "Please enter model";
    } else if (strlen($_POST['model']) > 50) {
        echo "Model should have maximum 50 characters";
    } else if (!isset($_POST['mqty'])) {
        echo "Please enter minimum qty";
    } else if (empty(trim($_POST['mqty']))) {
        echo "Please enter minimum qty";
    } else if (!is_numeric($_POST['mqty'])) {
        echo "Invalid minimum qty";
    } else if ($_POST['mqty'] < 0) {
        echo "Invalid minimum qty";
    } else if (!isset($_POST['rprice'])) {
        echo "Please enter retail price";
    } else if (empty(trim($_POST['rprice']))) {
        echo "Please enter  retail price";
    } else if (!is_numeric(trim($_POST['rprice']))) {
        echo "Please enter  retail price";
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
    } else if ($_POST['creditPrice'] >= $_POST['rprice'] || $_POST['cashPrice'] >= $_POST['rprice']) {
        echo "Invalid prices";
    } else if (!isset($_POST['qty'])) {
        echo "Please enter qty";
    } else if (empty(trim($_POST['qty']))) {
        echo "Please enter qty";
    } else if ($_POST['qty'] < 0) {
        echo "Invalid qty";
    } else if (!isset($_POST['description'])) {
        echo "Please enter description";
    } else if (empty(trim($_POST['description']))) {
        echo "Please enter description";
    } else if (!isset($_FILES['image1'])) {
        echo "Please choose cover image";
    } else {

        $isReady = false;

        $resultset1 = Database::search("SELECT * FROM `product` WHERE `model_no` = ?", "s", [$_POST['model']]);

        if ($resultset1->num_rows == 0) {

            $allowed_image_extentions = array("image/jpg", "image/jpeg", "image/png", "image/webp");

            $x = 1;
            while ($x <= 3) {

                if (isset($_FILES["image" . $x])) {

                    $image = $_FILES["image" . $x];

                    $file_extention = $image["type"];


                    if (in_array($file_extention, $allowed_image_extentions)) {
                        if ($_FILES['image' . $x]["size"] > 2000000) {
                            echo "Image " . $x . " should be 2MB or less";
                            break;
                        }
                    } else {
                        echo "Invalid file type for image " . $x . " (Valid only PNG, JPG, JPEG, WEBP)";
                        break;
                    }
                }

                if ($x == 3) {
                    $isReady = true;
                }
                $x++;
            }

            if ($isReady) {


                $new_img_extention;

                $file_extention = $_FILES['image1']["type"];

                if ($file_extention == "image/jpg") {
                    $new_img_extention = ".jpg";
                } else if ($file_extention == "image/jpeg") {
                    $new_img_extention = ".jpeg";
                } else if ($file_extention == "image/png") {
                    $new_img_extention = ".png";
                } else if ($file_extention == "image/webp") {
                    $new_img_extention = ".webp";
                }

                $file_name = "uploads/products/" . uniqid($prefix = "productImg_" . preg_replace('/[^a-zA-Z0-9]+/', '', $_POST['model']) . "_") . $new_img_extention;

                $ref = time();

                $date_time = date("Y-m-d H:i:s");

                $sub_category = null;

                if (isset($_POST['subCategory'])) {
                    if (!empty(trim($_POST['subCategory']))) {
                        $sub_category = $_POST['subCategory'];
                    }
                }


                if (Database::iud("INSERT INTO `product`(`ref`,`title`,`model_no`,`warning_no`,`cover_image`,`status_id`,`description`,`date_time`,`category_id`,`sub_category_id`) VALUES (?,?,?,?,?,?,?,?,?,?) ", "ssssssssss", [$ref, $_POST['title'], trim($_POST['model']), $_POST['mqty'], $file_name, '1', $_POST['description'], $date_time, $_POST['category'], $sub_category])) {

                    require "../util/activity.php";

                    Activity::newActivity("added new product(" . $_POST['model'] . ").", 1, "Please add href");


                    move_uploaded_file($_FILES['image1']["tmp_name"], "../" . $file_name);

                    $pid = Database::search("SELECT `id` FROM `product` WHERE `ref` = ? ", "s", [$ref])->fetch_assoc()['id'];

                    if (Database::iud("INSERT INTO `price`(`retail_price`,`credit_price`,`cash_price`,`product_id`,`date_time`) VALUES(?,?,?,?,?) ", "sssss", [$_POST['rprice'], $_POST['creditPrice'], $_POST['cashPrice'], $pid, $date_time])) {
                        $x = 2;
                        while ($x <= 3) {

                            if (isset($_FILES["image" . $x])) {
                                $file_extention = $_FILES["image" . $x]["type"];

                                $new_img_extention;

                                if ($file_extention == "image/jpg") {
                                    $new_img_extention = ".jpg";
                                } else if ($file_extention == "image/jpeg") {
                                    $new_img_extention = ".jpeg";
                                } else if ($file_extention == "image/png") {
                                    $new_img_extention = ".png";
                                } else if ($file_extention == "image/webp") {
                                    $new_img_extention = ".webp";
                                }

                                $file_name = "uploads/products/" . uniqid($prefix = "productImg_" . preg_replace('/[^a-zA-Z0-9]+/', '', $_POST['model']) . "_") . $new_img_extention;
                                move_uploaded_file($_FILES["image" . $x]["tmp_name"], "../" . $file_name);
                                Database::iud("INSERT INTO `product_image`(`product_id`,`path`) VALUES(?,?)", "ss", [$pid, $file_name]);
                            }
                            $x++;
                        }

                        Database::iud("INSERT INTO `stock`(`product_id`,`qty`) VALUES(?,?)", "ss", [$pid, $_POST['qty']]);
                        Database::iud("INSERT INTO `stock_history`(`changed_qty`,`old_qty`,`total_qty`,`date_time`,`product_id`,`user_id`,`operation_type_id`,`stock_type_id`,`note`) VALUES(?,?,?,?,?,?,?,?,?) ", "sssssssss", [$_POST['qty'], 0, $_POST['qty'], $date_time, $pid, $_SESSION['user']['id'], 1, '1', "Added new product(" . $_POST['model'] . ")."]);

                        echo "success";
                    } else {
                        Database::iud("DELETE FROM `product` WHERE `ref` = ?", "s", [$ref]);
                        echo "Try again";
                    }
                } else {
                    echo "Try again later";
                }
            }
        } else {
            echo "Already exist this model number";
        }
    }
} else {
    echo "reload";
}
