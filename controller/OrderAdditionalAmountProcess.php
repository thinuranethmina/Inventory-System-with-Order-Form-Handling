<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (!isset($_POST['id'])) {
        echo "Unexcepted error";
    } else if (!isset($_POST['additionalAmount'])) {
        echo "Please enter additional amount";
    } else if ($_POST['additionalAmount'] == null || $_POST['additionalAmount'] == '' || empty($_POST['additionalAmount']) || !is_numeric($_POST['additionalAmount']) || $_POST['additionalAmount'] < 0) {
        echo "Please enter valid additional amount";
    } else {

        $invoice_rs = Database::search("SELECT * FROM `invoice` WHERE `invoice`.`id` = ? AND `is_completed`='0' ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {
            $invoice = $invoice_rs->fetch_assoc();
            $balance = $invoice['total'];

            $invoice_payment_rs = Database::search("SELECT * FROM `invoice_payment` INNER JOIN `payment_type` ON `payment_type`.`id` = `invoice_payment`.`payment_type_id` WHERE `invoice_id` = ? ORDER BY `date_time` DESC", "s", [$_POST['id']]);

            if ($invoice_payment_rs->num_rows > 0) {
                $balance = $invoice_payment_rs->fetch_assoc()['balance'];
            }

            $total_balance = $balance + $_POST['additionalAmount'];

            Database::iud("UPDATE `invoice` SET `total` = ? WHERE `id` = ? ", "ss", [$invoice['total'] + $_POST['additionalAmount'], $_POST['id']]);


            $date_time = date("Y-m-d H:i:s");

            Database::iud("INSERT INTO `invoice_payment`(`paid_amount`,`balance`,`date_time`,`invoice_id`,`payment_type_id`,`is_additional_amount`) VALUES(?,?,?,?,?,?) ", "ssssss", [$_POST['additionalAmount'], $total_balance, $date_time, $_POST['id'], '3', '1']);

            require "../util/activity.php";

            Activity::newActivity("added additional amount for order(OrderID: " . $invoice['order_id'] . ").", 1, "Please add href");

            echo "success";
        } else {
            echo "Unexcepted error";
        }
    }
} else {
    echo "reload";
}
