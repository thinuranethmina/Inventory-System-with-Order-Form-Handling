<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN])) {
    if (!isset($_POST['id'])) {
        echo "reload";
    } else {

        $result = Database::search("SELECT * FROM `user` WHERE `id` = '" . $_POST['id'] . "'");

        if ($result->num_rows == 1) {
            $resultset = $result->fetch_assoc();
            if ($resultset['status_id'] == '1') {
                $status = 0;
                echo "deactive";
            } else {
                $status = 1;
                echo "active";
            }
            Database::search("UPDATE `user` SET `status_id`='$status' WHERE `id` = '" . $_POST['id'] . "'");
        } else {
            echo "reload";
        }
    }
} else {
    echo "reload";
}
