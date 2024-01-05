<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['id'])) {
        echo "Unexcepted error";
    } else if (!isset($_POST['name'])) {
        echo "Please enter category name";
    } else if (empty(trim($_POST['name']))) {
        echo "Please enter category name";
    } else if (strlen($_POST['name']) > 100) {
        echo "Category Name should have maximum 100 characters";
    } else {

        $resultset1 = Database::search("SELECT * FROM `category` WHERE `name` = ? AND `id` != ?", "ss", [$_POST['name'], $_POST['id']]);

        if ($resultset1->num_rows > 0) {
            echo "Already exist this category name";
        } else {

            Database::iud("UPDATE `category` SET `name`= ?  WHERE `id` = ? ", "si", [$_POST['name'], $_POST['id']]);

            require "../util/activity.php";

            Activity::newActivity("updated category name into " . $_POST['name'] . ".", 2);

            echo "success";
        }
    }
} else {
    echo "reload";
}
