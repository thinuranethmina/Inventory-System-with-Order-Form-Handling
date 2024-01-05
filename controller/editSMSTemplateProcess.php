<?php

require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {

    if (!isset($_POST['id'])) {
        echo "Unexcepted error";
    } else if (!isset($_POST['name'])) {
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

        $resultset1 = Database::search("SELECT * FROM `message_template` WHERE `name` = ? AND `id` != ?", "ss", [$_POST['name'], $_POST['id']]);

        if ($resultset1->num_rows > 0) {
            echo "Already exist this category name";
        } else {

            Database::iud("UPDATE `message_template` SET `name`= ?, `text` = ?  WHERE `id` = ? ", "ssi", [$_POST['name'], $_POST['template'], $_POST['id']]);

            require "../util/activity.php";

            Activity::newActivity("updated template name into " . $_POST['name'] . ".", 2);

            echo "success";
        }
    }
} else {
    echo "reload";
}
