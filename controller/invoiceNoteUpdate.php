<?php

require "util/userStatus.php";

if (User::allowOnly([User::ADMIN, User::SUPER_ADMIN])) {
    if (!isset($_POST['id'])) {
        echo "Unexcepted error";
    } else if (!isset($_POST['note'])) {
        echo "Please enter note";
    } else {

        $invoice_rs = Database::search("SELECT *,`invoice`.`id` AS `id`,`shop`.`id` AS `shopID`,`shop`.`name` AS `shop`, `invoice`.`date_time` AS `date_time`, `invoice`.`note` AS `invoiceNote` , `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province`  FROM `invoice` INNER JOIN `shop`  ON `shop`.`id` = `invoice`.`shop_id`  INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id`  WHERE `invoice`.`id` = ? AND `is_delete` = '0' AND `shop`.`id`!= '0' ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {
            $invoice = $invoice_rs->fetch_assoc();

            Database::iud("UPDATE `invoice` SET `note` = ?  WHERE `id` = ? ", "ss", [$_POST['note'], $_POST['id']]);
            echo "success";

            require "../util/activity.php";

            Activity::newActivity("make note change on OrderID: " . $invoice['order_id'] . ".", 1, "Please add href");
        }
    }
} else {
    echo "reload";
}
