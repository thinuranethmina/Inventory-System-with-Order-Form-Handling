<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['name'])) {
        echo "Please enter full name";
    } else if (empty(trim($_POST['name']))) {
        echo "Please enter full name";
    } else if (strlen($_POST['name']) > 100) {
        echo "Full name should have maximum 100 characters";
    } else if (!isset($_POST['nic'])) {
        echo "Please enter NIC";
    } else if (empty(trim($_POST['nic']))) {
        echo "Please enter NIC";
    } else if (strlen($_POST['nic']) > 12) {
        echo "NIC should have maximum 12 characters";
    } else if (!isset($_POST['mobile'])) {
        echo "Please enter mobile";
    } else if (!preg_match("/[0][7][0|1|2|4|5|6|7|8][0-9]{7}$/", $_POST['mobile'])) {
        echo "Invalid Mobile Number";
    } else if (!isset($_POST['password'])) {
        echo "Please enter password";
    } else if (empty(trim($_POST['password']))) {
        echo "Invalid password Number";
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
    } else if (!isset($_POST['userType'])) {
        echo "Please select user type";
    } else if ($_POST['userType'] == '0' || $_POST['userType'] == '1' || $_POST['userType'] == '' || $_POST['userType'] == null) {
        echo "Please select user type";
    } else if (!isset($_FILES['image'])) {
        echo "Please choose profile image";
    } else if (!isset($_FILES['nicf'])) {
        echo "Please choose NIC front image";
    } else if (!isset($_FILES['nicb'])) {
        echo "Please choose NIC back image";
    } else {

        $isReady = false;

        $resultset1 = Database::search("SELECT * FROM `user` WHERE `nic` = ?", "s", [$_POST['nic']]);

        if ($resultset1->num_rows == 0) {

            $resultset2 = Database::search("SELECT * FROM `user` WHERE `mobile` = ?", "s", [$_POST['mobile']]);

            if ($resultset2->num_rows == 0) {

                $allowed_image_extentions = array("image/jpg", "image/jpeg", "image/png", "image/webp");

                $file_extention = $_FILES["image"]["type"];
                $file_extention_nicf = $_FILES["nicf"]["type"];
                $file_extention_nicb = $_FILES["nicb"]["type"];


                if (in_array($file_extention, $allowed_image_extentions) && in_array($file_extention_nicf, $allowed_image_extentions) &&  in_array($file_extention_nicb, $allowed_image_extentions)) {
                    if (
                        $_FILES['image']["size"] <= 2000000 &&
                        $_FILES['nicf']["size"] <= 2000000 &&
                        $_FILES['nicb']["size"] <= 2000000
                    ) {

                        $new_img_extention;
                        $nicf_img_extention;
                        $nicb_img_extention;

                        if ($file_extention == "image/jpg") {
                            $new_img_extention = ".jpg";
                        } else if ($file_extention == "image/jpeg") {
                            $new_img_extention = ".jpeg";
                        } else if ($file_extention == "image/png") {
                            $new_img_extention = ".png";
                        } else if ($file_extention == "image/webp") {
                            $new_img_extention = ".webp";
                        }

                        if ($file_extention_nicf == "image/jpg") {
                            $nicf_img_extention = ".jpg";
                        } else if ($file_extention_nicf == "image/jpeg") {
                            $nicf_img_extention = ".jpeg";
                        } else if ($file_extention_nicf == "image/png") {
                            $nicf_img_extention = ".png";
                        } else if ($file_extention_nicf == "image/webp") {
                            $nicf_img_extention = ".webp";
                        }

                        if ($file_extention_nicb == "image/jpg") {
                            $nicb_img_extention = ".jpg";
                        } else if ($file_extention_nicb == "image/jpeg") {
                            $nicb_img_extention = ".jpeg";
                        } else if ($file_extention_nicb == "image/png") {
                            $nicb_img_extention = ".png";
                        } else if ($file_extention_nicb == "image/webp") {
                            $nicb_img_extention = ".webp";
                        }


                        $file_name = "uploads/users/" . uniqid($prefix = "profileImg_" . preg_replace('/[^a-zA-Z0-9]+/', '', $_POST['nic']) . "_") . $new_img_extention;
                        move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $file_name);


                        $file_name_nicf = "uploads/users/" . uniqid($prefix = "NIC_FRONT_Img_" . preg_replace('/[^a-zA-Z0-9]+/', '', $_POST['nic']) . "_") . $nicf_img_extention;
                        move_uploaded_file($_FILES["nicf"]["tmp_name"], "../" . $file_name_nicf);

                        $file_name_nicb = "uploads/users/" . uniqid($prefix = "NIC_BACK_Img_" . preg_replace('/[^a-zA-Z0-9]+/', '', $_POST['nic']) . "_") . $nicb_img_extention;
                        move_uploaded_file($_FILES["nicb"]["tmp_name"], "../" . $file_name_nicb);

                        $date_time = date("Y-m-d H:i:s");
                        require "..//login/util/encryption.php";

                        Database::iud("INSERT INTO `user`(`name`,`nic`,`password`,`mobile`,`address`,`profile_image`,`nic_front`,`nic_back`,`user_type_id`,`date_time`,`status_id`,`city_id`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?) ", "ssssssssssss", [$_POST['name'], $_POST['nic'], Encryption::encrypt($_POST['password']), $_POST['mobile'], $_POST['address'], $file_name, $file_name_nicf, $file_name_nicb, $_POST['userType'], $date_time, '1', $_POST['city']]);

                        require "../util/activity.php";

                        Activity::newActivity("added new user(" . $_POST['nic'] . ").", 1, "Please add href");

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
            echo "Already exist this NIC";
        }
    }
} else {
    echo "reload";
}
