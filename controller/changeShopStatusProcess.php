<?php


require "util/userStatus.php";

if (User::is_allow()) {
    if (!isset($_POST['id'])) {
        echo "reload";
    } else {

        $result = Database::search("SELECT * FROM `shop` WHERE `id` = '" . $_POST['id'] . "'");

        if ($result->num_rows == 1) {
            $resultset = $result->fetch_assoc();
            if ($resultset['status_id'] == '1') {
                $status = 0;
                echo "deactive";
            } else {
                $status = 1;
                echo "active";
            }
            Database::search("UPDATE `shop` SET `status_id`='$status' WHERE `id` = '" . $_POST['id'] . "'");
        } else {
            echo "reload";
        }
    }
} else {
    echo "reload";
}
