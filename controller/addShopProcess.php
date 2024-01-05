<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['name'])) {
        echo "Please enter shop name";
    } else if (empty(trim($_POST['name']))) {
        echo "Please enter shop name";
    } else if (strlen($_POST['name']) > 100) {
        echo "Shop name should have maximum 100 characters";
    } else if (!isset($_POST['mobile'])) {
        echo "Please enter primary mobile";
    } else if (!isset($_POST['omobile'])) {
        echo "Please enter other mobile";
    } else if (!isset($_POST['address'])) {
        echo "Please enter address";
    } else if (empty(trim($_POST['address']))) {
        echo "Please enter address";
    } else if (strlen($_POST['address']) > 100) {
        echo "Address should have maximum 100 characters";
    } else if (!isset($_POST['city'])) {
        echo "Please select city";
    } else if ($_POST['city'] == '0' || $_POST['city'] == '' || $_POST['city'] == null) {
        echo "Please select city";
    } else if (!isset($_POST['description'])) {
        echo "Please enter description";
    } else if (!isset($_FILES['image'])) {
        echo "Please choose cover image";
    } else if (!isset($_POST['latitude'])) {
        echo "Please enter valid location";
    } else if (!preg_match('/^-?\d+(\.\d+)?$/', $_POST['latitude'])) {
        echo "Please enter valid location latitude";
    } else if (!isset($_POST['longitude'])) {
        echo "Please enter valid location";
    } else if (!preg_match('/^-?\d+(\.\d+)?$/', trim($_POST['longitude']))) {
        echo "Please enter valid location longitude";
    } else {
        $isReady = true;

        $mobile = null;
        $omobile = null;

        if (!empty(trim($_POST['mobile'])) || !empty(trim($_POST['omobile']))) {
            if (!empty(trim($_POST['mobile']))) {
                $mobile = $_POST['mobile'];
                if (!preg_match("/[0][7][0|1|2|4|5|6|7|8][0-9]{7}$/", $_POST['mobile'])) {
                    $isReady = false;
                    echo "Invalid Primary Mobile Number";
                    if (!empty(trim($_POST['omobile']))) {
                        $omobile = $_POST['omobile'];
                        if (!preg_match("/[0][0-9]{9}$/", $_POST['omobile'])) {
                            $isReady = false;
                            echo " And Invalid Other Mobile Number";
                        }
                    }
                } else if (!empty(trim($_POST['omobile']))) {
                    $omobile = $_POST['omobile'];
                    if (!preg_match("/[0][0-9]{9}$/", $_POST['omobile'])) {
                        $isReady = false;
                        echo "Invalid Other Mobile Number";
                    }
                }
            } else if (!empty(trim($_POST['omobile']))) {
                $omobile = $_POST['omobile'];
                if (!preg_match("/[0][0-9]{9}$/", $_POST['omobile'])) {
                    $isReady = false;
                    echo "Invalid Other Mobile Number";
                }
            }
        } else {
            $isReady = true;
        }

        if ($isReady) {
            $resultset1 = Database::search("SELECT * FROM `shop` WHERE `name` = ?", "s", [$_POST['name']]);

            if ($resultset1->num_rows == 0) {
                $isReady = false;

                if (!empty(trim($_POST['omobile'])) && !empty(trim($_POST['mobile']))) {
                    $resultset2 = Database::search("SELECT * FROM `shop` WHERE `mobile` = ? OR `other_mobile` = ? OR `mobile` = ? OR `other_mobile` = ? ", "ssss", [$_POST['mobile'], $_POST['omobile'], $_POST['omobile'], $_POST['mobile']]);
                    if ($resultset2->num_rows <= 0) {
                        $isReady = true;
                    }
                } else if (!empty(trim($_POST['mobile']))) {
                    $resultset2 = Database::search("SELECT * FROM `shop` WHERE `mobile` = ? OR `other_mobile` = ? ", "ss", [$_POST['mobile'], $_POST['mobile']]);
                    if ($resultset2->num_rows <= 0) {
                        $isReady = true;
                    }
                } else if (!empty(trim($_POST['omobile']))) {
                    $resultset2 = Database::search("SELECT * FROM `shop` WHERE `other_mobile` = ? OR `other_mobile` = ? ", "ss", [$_POST['omobile'], $_POST['omobile']]);
                    if ($resultset2->num_rows <= 0) {
                        $isReady = true;
                    }
                } else {
                    $isReady = true;
                }

                if ($isReady) {

                    $allowed_image_extentions = array("image/jpg", "image/jpeg", "image/png", "image/webp");

                    $image = $_FILES["image"];
                    $file_extention = $image["type"];


                    if (in_array($file_extention, $allowed_image_extentions)) {
                        if ($_FILES['image']["size"] <= 2000000) {

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

                            $file_name = "uploads/shops/" . uniqid($prefix = "shopImg_" . preg_replace('/[^a-zA-Z0-9]+/', '', $_POST['name']) . "_") . $new_img_extention;
                            move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $file_name);

                            $date_time = date("Y-m-d H:i:s");

                            Database::iud("INSERT INTO `shop`(`name`,`address`,`latitude`,`longitude`,`image`,`date_time`,`mobile`,`other_mobile`,`city_id`,`description`) VALUES(?,?,?,?,?,?,?,?,?,?) ", "ssssssssss", [$_POST['name'], $_POST['address'], $_POST['latitude'], $_POST['longitude'], $file_name, $date_time, $mobile, $omobile, $_POST['city'], $_POST['description']]);


                            require "../util/activity.php";

                            Activity::newActivity("added new shop(" . $_POST['name'] . ").", 1, "Please add href");

                            echo "success";
                        } else {
                            echo "Image should be 2MB or less";
                        }
                    } else {
                        echo "Invalid file type for image (Valid only PNG, JPG, JPEG, WEBP)";
                    }
                } else {
                    echo "Already exist this mobile number";
                }
            } else {
                echo "Already exist this shop name";
            }
        }
    }
} else {
    echo "reload";
}
