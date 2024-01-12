<?php

require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {

    if (!isset($_POST['name'])) {
        echo "Please enter city name";
    } else if (empty(trim($_POST['name']))) {
        echo "Please enter city name";
    } else if (strlen($_POST['name']) >= 51) {
        echo "City name should have maximum 50 characters";
    } else if (!isset($_POST['district'])) {
        echo "Please select district";
    } else if (empty(trim($_POST['district'])) || $_POST['district'] == '0') {
        echo "Please select district";
    } else {

        $isReady = false;

        $resultset1 = Database::search("SELECT * FROM `city` WHERE `name` = ? AND `district_id` = ? ", "ss", [$_POST['name'], $_POST['district']]);

        if ($resultset1->num_rows == 0) {

            Database::iud("INSERT INTO `city`(`name`,`district_id`) VALUES(?,?) ", "ss", [$_POST['name'], $_POST['district']]);

            require "../util/activity.php";

            Activity::newActivity("added new city(" . $_POST['name'] . ").", 2);

            echo "success";
        } else {
            echo "Already exist this city";
        }
    }
} else {
    echo "reload";
}
