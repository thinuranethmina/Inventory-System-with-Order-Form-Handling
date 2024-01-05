<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['id'])) {
        echo "Un excepted error";
    } else if (!isset($_POST['name'])) {
        echo "Please enter full name";
    } else if (empty(trim($_POST['name']))) {
        echo "Please enter full name";
    } else if (strlen($_POST['name']) > 100) {
        echo "Full name should have maximum 100 characters";
    } else if (!isset($_POST['mobile'])) {
        echo "Please enter mobile";
    } else if (!preg_match("/[0][7][1|2|4|5|6|7|8][0-9]{7}$/", $_POST['mobile'])) {
        echo "Invalid Mobile Number";
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
    } else {


        $resultset1 = Database::search("SELECT * FROM `user` WHERE `id` = ?", "s", [$_POST['id']]);

        if ($resultset1->num_rows == 1) {
            $user = $resultset1->fetch_assoc();

            $resultset2 = Database::search("SELECT * FROM `user` WHERE `mobile` = ? AND `id` != ? ", "ss", [$_POST['mobile'], $_POST['id']]);

            if ($resultset2->num_rows == 0) {

                $allowed_image_extentions = array("image/jpg", "image/jpeg", "image/png", "image/webp");

                $isReady = true;

                if (isset($_FILES['image'])) {
                    $file_extention = $_FILES["image"]["type"];
                    if (!in_array($file_extention, $allowed_image_extentions)) {
                        $isReady = false;
                        echo "Invalid file type for profile image (Valid only PNG, JPG, JPEG, WEBP)";
                    }
                    if ($isReady) {
                        if ($_FILES['image']["size"] > 2000000) {
                            $isReady = false;
                            echo "Profile image should be 2MB or less";
                        }
                    }
                }

                if ($isReady) {
                    if (isset($_FILES['nicf'])) {
                        $file_extention = $_FILES["nicf"]["type"];
                        if (!in_array($file_extention, $allowed_image_extentions)) {
                            $isReady = false;
                            echo "Invalid file type for NIC front image (Valid only PNG, JPG, JPEG, WEBP)";
                        }
                        if ($isReady) {
                            if ($_FILES['nicf']["size"] > 2000000) {
                                $isReady = false;
                                echo "NIC front image should be 2MB or less";
                            }
                        }
                    }
                }

                if ($isReady) {
                    if (isset($_FILES['nicb'])) {
                        $file_extention = $_FILES["nicb"]["type"];
                        if (!in_array($file_extention, $allowed_image_extentions)) {
                            $isReady = false;
                            echo "Invalid file type for NIC back image (Valid only PNG, JPG, JPEG, WEBP)";
                        }
                        if ($isReady) {
                            if ($_FILES['nicb']["size"] > 2000000) {
                                $isReady = false;
                                echo "NIC back image should be 2MB or less";
                            }
                        }
                    }
                }


                if ($isReady) {
                    if (isset($_FILES['image'])) {
                        $file_extention = $_FILES["image"]["type"];

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


                        $file_name = "uploads/users/" . uniqid($prefix = "profileImg_" . preg_replace('/[^a-zA-Z0-9]+/', '', $user['nic']) . "_") . $new_img_extention;
                        move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $file_name);

                        unlink("../" . $user['profile_image']);

                        Database::iud("UPDATE `user` SET `profile_image` = ? WHERE `id` = ? ", "ss", [$file_name, $_POST['id']]);
                    }

                    if (isset($_FILES['nicf'])) {
                        $file_extention = $_FILES["nicf"]["type"];

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


                        $file_name = "uploads/users/" . uniqid($prefix = "NIC_FRONT_Img_" . preg_replace('/[^a-zA-Z0-9]+/', '', $user['nic']) . "_") . $new_img_extention;
                        move_uploaded_file($_FILES["nicf"]["tmp_name"], "../" . $file_name);

                        unlink("../" . $user['nic_front']);

                        Database::iud("UPDATE `user` SET `nic_front` = ? WHERE `id` = ? ", "ss", [$file_name, $_POST['id']]);
                    }

                    if (isset($_FILES['nicb'])) {
                        $file_extention = $_FILES["nicb"]["type"];

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


                        $file_name = "uploads/users/" . uniqid($prefix = "NIC_BACK_Img_" . preg_replace('/[^a-zA-Z0-9]+/', '', $user['nic']) . "_") . $new_img_extention;
                        move_uploaded_file($_FILES["nicb"]["tmp_name"], "../" . $file_name);

                        unlink("../" . $user['nic_back']);

                        Database::iud("UPDATE `user` SET `nic_back` = ? WHERE `id` = ? ", "ss", [$file_name, $_POST['id']]);
                    }

                    Database::iud("UPDATE `user` SET `name` = ?, `mobile` = ?, `address` = ? , `user_type_id`= ?  , `city_id`= ?  WHERE `id` = ? ", "ssssss", [$_POST['name'], $_POST['mobile'], $_POST['address'], $_POST['userType'], $_POST['city'], $_POST['id']]);

                    require "../util/activity.php";

                    Activity::newActivity("updated user(" . $user['nic'] . ").", 1, "Please add href");

                    echo "success";
                }
            } else {
                echo "Already exist this mobile number";
            }
        } else {
            echo "Un excepted error";
        }
    }
} else {
    echo "reload";
}
