<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['id'])) {
        echo "Unexpected error";
    } else if (!isset($_POST['title'])) {
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
    } else if (!isset($_POST['description'])) {
        echo "Please enter description";
    } else if (empty(trim($_POST['description']))) {
        echo "Please enter description";
    } else {

        $isReady = false;

        $data_rs = Database::search("SELECT * FROM `product` WHERE `id` = ? ", "s", [$_POST['id']]);

        if ($data_rs->num_rows == 1) {

            $resultset1 = Database::search("SELECT * FROM `product` WHERE `model_no` = ? AND `id` != ? ", "ss", [$_POST['model'], $_POST['id']]);

            if ($resultset1->num_rows == 0) {

                $allowed_image_extentions = array("image/jpg", "image/jpeg", "image/png", "image/webp");

                $old_img_array = array();

                $x = 1;
                $n = 0;
                while ($x <= 3) {


                    if (isset($_POST["removeStatus" . $x])) {
                        if ($_POST['removeStatus' . $x] == 'remove') {
                            $old_img_rs = Database::search("SELECT * FROM `product_image` WHERE `product_id` = ? LIMIT 1 OFFSET ? ", "ss", [$_POST['id'], $x - 2]);

                            if ($old_img_rs->num_rows == 1) {
                                $old_img = $old_img_rs->fetch_assoc();
                                array_push($old_img_array, $old_img['id']);
                                $n++;
                            }
                        }
                    }

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

                    $date_time = date("Y-m-d H:i:s");

                    $sub_category = null;

                    if (isset($_POST['subCategory'])) {
                        if (!empty(trim($_POST['subCategory']))) {
                            $sub_category = $_POST['subCategory'];
                        }
                    }

                    Database::iud("UPDATE `product` SET `title`=?,`model_no`=?,`warning_no`=?,`category_id`=?,`description`=?, `sub_category_id` = ? WHERE `id` = ? ", "sssssss", [$_POST['title'], trim($_POST['model']), $_POST['mqty'], $_POST['category'], $_POST['description'], $sub_category, $_POST['id']]);


                    require "../util/activity.php";

                    Activity::newActivity("has made several changes in product(" . $_POST['model'] . ").", 1, "Please add href");


                    $x = 1;
                    $n = 0;
                    while ($x <= 3) {
                        if ($x == 1) {
                            if (isset($_FILES["image" . $x])) {

                                $old_img = Database::search("SELECT * FROM `product` WHERE `id` = ? ", "s", [$_POST['id']])->fetch_assoc();

                                unlink("../" . $old_img['cover_image']);

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

                                Database::iud("UPDATE `product` SET `cover_image` = ? WHERE `id`=? ", "ss", [$file_name, $_POST['id']]);
                            }
                        } else {
                            if (isset($_POST["removeStatus" . $x])) {
                                if ($_POST['removeStatus' . $x] == 'remove') {
                                    echo "--" . count($old_img_array) - $n . "--";
                                    if ((count($old_img_array) - $n) > 0) {

                                        $old_image_rs = Database::search("SELECT * FROM `product_image` WHERE `id` = ? ", "s", [$old_img_array[$n]]);

                                        if ($old_image_rs->num_rows == 1) {
                                            $old_image = $old_image_rs->fetch_assoc();
                                            Database::search("DELETE FROM `product_image` WHERE `id`=? AND `product_id`=? ", "ss", [$old_img_array[$n], $_POST['id']]);
                                            $n++;
                                            unlink("../" . $old_image['path']);
                                        }
                                    }
                                }


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

                                    Database::iud("INSERT INTO `product_image`(`product_id`,`path`) VALUES(?,?)", "ss", [$_POST['id'], $file_name]);
                                }
                            }
                        }

                        $x++;
                    }

                    echo "success";
                }
            } else {
                echo "Already exist this model number";
            }
        } else {
            echo "Unexpected error";
        }
    }
} else {
    echo "reload";
}
