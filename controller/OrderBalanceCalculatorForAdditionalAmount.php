<?php

require "util/userStatus.php";

if (User::is_allow()) {
    $responce = new stdClass();
    if (isset($_POST['id'])) {

        $invoice_rs = Database::search("SELECT * FROM `invoice` WHERE `invoice`.`id` = ? AND `is_completed`='0' ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {
            $invoice = $invoice_rs->fetch_assoc();
            $balance = $invoice['total'];

            $invoice_payment_rs = Database::search("SELECT * FROM `invoice_payment` INNER JOIN `payment_type` ON `payment_type`.`id` = `invoice_payment`.`payment_type_id` WHERE `invoice_id` = ? ORDER BY `date_time` DESC", "s", [$_POST['id']]);

            if ($invoice_payment_rs->num_rows > 0) {
                $balance = $invoice_payment_rs->fetch_assoc()['balance'];
            }

            $responce->status = "success";
            $paid_amount = 0;

            if (isset($_POST['additionalAmount'])) {
                if ($_POST['additionalAmount'] != null && $_POST['additionalAmount'] != '' && !empty($_POST['additionalAmount']) && is_numeric($_POST['additionalAmount']) && $_POST['additionalAmount'] >= 0) {
                    $paid_amount = $_POST['additionalAmount'];
                }
            }

            $responce->balance = "Balance: Rs." . number_format($balance + $paid_amount, 2) . "/=";
        } else {
            $responce->status = "Unexcepted error";
        }
    } else {
        $responce->status = "Unexcepted error";
    }
    echo json_encode($responce);
} else {
    echo "reload";
}
