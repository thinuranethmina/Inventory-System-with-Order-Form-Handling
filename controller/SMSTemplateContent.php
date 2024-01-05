<?php

require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {
    if (isset($_POST['id'])) {

        if ($_POST['id'] != '0') {
            $template_rs = Database::search("SELECT * FROM `message_template` WHERE `message_template`.`id` = ? ", "s", [$_POST['id']]);

            if ($template_rs->num_rows == 1) {
                $template = $template_rs->fetch_assoc();

                echo $template['text'];
            }
        }
    }
} else {
    echo "reload";
}
