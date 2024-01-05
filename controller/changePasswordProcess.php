<?php


require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['opassword'])) {
        echo "Please enter old password";
    } else if (empty(trim($_POST['opassword']))) {
        echo "Please enter old password";
    } else if (!isset($_POST['npassword'])) {
        echo "Please enter new password";
    } else if (empty(trim($_POST['npassword']))) {
        echo "Please enter new password";
    } else if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W)[A-Za-z\d\W]{8,}/", $_POST['npassword'])) {
        echo "Invalid password (Password must be one simple letter, one capital letter, one symbol, one number and at least 8 characters)";
    } else if (!isset($_POST['cpassword'])) {
        echo "Please enter confirm password";
    } else if (empty(trim($_POST['cpassword']))) {
        echo "Please enter confirm password";
    } else if ($_POST['cpassword'] != $_POST['npassword']) {
        echo "Not maching your new password";
    } else {

        require "..//login/util/encryption.php";

        $resultset1 = Database::search("SELECT * FROM `user` WHERE `id`=? ", "s", [$_SESSION['user']['id']]);

        $dataset1 = $resultset1->fetch_assoc();

        if (Encryption::is_verify($_POST['opassword'], $dataset1['password'])) {
            Database::iud("UPDATE `user` SET `password`=?  WHERE `id`=? ", "ss", [Encryption::encrypt($_POST['npassword']), $_SESSION['user']['id']]);

            echo "success";
        } else {
            echo "Invalid old password";
        }
    }
} else {
    echo "reload";
}
