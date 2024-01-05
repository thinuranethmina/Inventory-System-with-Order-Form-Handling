<?php

session_set_cookie_params(60 * 60 * 8);
session_start();

require "../config/connection.php";

if (!isset($_SESSION['user'])) {
    if (!isset($_POST['username'])) {
        echo "Please enter username";
    } else if (empty($_POST['username'])) {
        echo "Please enter username";
    } else if (!isset($_POST['password'])) {
        echo "Please enter password";
    } else if (empty($_POST['password'])) {
        echo "Please enter password";
    } else {

        $user_resultset = Database::search("SELECT * FROM `user` WHERE `nic` = ? ", "s", [$_POST['username']]);

        if ($user_resultset->num_rows == 1) {
            require "../util/encryption.php";

            $user_data = $user_resultset->fetch_assoc();

            if ($user_data['status_id'] == 1) {
                if (Encryption::is_verify($_POST['password'], $user_data['password'])) {

                    date_default_timezone_set('Asia/Kolkata');

                    require "../../util/activity.php";

                    $date_time = date("Y-m-d H:i:s");

                    $_SESSION['user'] = array("id" => $user_data['id'], "nic" => $user_data['nic'], "user_type" => $user_data['user_type_id']);

                    Activity::newActivity("logged into the system.", "3");

                    echo "success";
                } else {
                    echo "Invalid username or password";
                }
            } else {
                echo "Your account has been suspended";
            }
        } else {
            echo "Invalid username or password";
        }
    }
} else {
}
