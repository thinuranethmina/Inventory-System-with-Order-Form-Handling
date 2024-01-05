<?php

require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {

    if (!isset($_POST['name'])) {
        echo "Please enter template name";
    } else if (empty(trim($_POST['name']))) {
        echo "Please enter template name";
    } else if (strlen($_POST['name']) > 100) {
        echo "Template Name should have maximum 100 characters";
    } else if (!isset($_POST['template'])) {
        echo "Please enter SMS template";
    } else if (empty(trim($_POST['template']))) {
        echo "Please enter SMS template";
    } else {

        $resultset1 = Database::search("SELECT * FROM `message_template` WHERE `name` = ?", "s", [$_POST['name']]);

        if ($resultset1->num_rows == 0) {

            Database::iud("INSERT INTO `message_template`(`name`,`text`) VALUES(?,?)", "ss", [$_POST['name'], $_POST['template']]);

            require "../util/activity.php";

            Activity::newActivity("added SMS Template(" . $_POST['name'] . ").", 2);
            echo "success";
        } else {
            echo "Already exist this template name";
        }
    }
} else {
    echo "reload";
}
