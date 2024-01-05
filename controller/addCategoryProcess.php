<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['name'])) {
        echo "Please enter category name";
    } else if (empty(trim($_POST['name']))) {
        echo "Please enter category name";
    } else if (strlen($_POST['name']) > 100) {
        echo "Category Name should have maximum 100 characters";
    } else {

        $resultset1 = Database::search("SELECT * FROM `category` WHERE `name` = ?", "s", [$_POST['name']]);

        if ($resultset1->num_rows == 0) {
            $date_time = date("Y-m-d H:i:s");
            Database::iud("INSERT INTO `category`(`name`,`date_time`) VALUES(?,?)", "ss", [$_POST['name'], $date_time]);

            require "../util/activity.php";

            Activity::newActivity("added category(" . $_POST['name'] . ").", 2);
            echo "success";
        } else {
            echo "Already exist this category name";
        }
    }
} else {
    echo "reload";
}
