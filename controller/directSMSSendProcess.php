<?php

require "util/userStatus.php";
$object1 = new stdClass();
$isOkay = true;

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {

    if (!isset($_POST['phone'])) {
        $object1->status = "Please enter phone number";
    } else if (!preg_match("/[0][0-9]{9}$/", $_POST['phone'])) {
        $object1->status = "Please enter valid phone number";
    } else if (!isset($_POST['send_id'])) {
        $object1->status = "Please select sender id";
    } else if (empty(trim($_POST['send_id'])) || $_POST['send_id'] == '' || $_POST['send_id'] == '0') {
        $object1->status = "Please select sender id";
    } else if (!isset($_POST['text'])) {
        $object1->status = "Please enter text message";
    } else if (empty(trim($_POST['text']))) {
        $object1->status = "Please enter text message";
    } else {

        $isOkay = false;

        $senderId_rs = Database::search("SELECT * FROM `message_send_id` WHERE `id` = ? ", "s", [$_POST['send_id']]);

        if ($senderId_rs->num_rows == 1) {
            $senderId = $senderId_rs->fetch_assoc();

            $mobile = "94" . ltrim(trim($_POST['phone']), '0');

            $url = 'https://app.notify.lk/api/v1/send?user_id=25924&api_key=Pqc1F2e5SrlT0HZxpxi6&message=' . urlencode($_POST['text']) . '&to=' . $mobile . '&sender_id=' . urlencode($senderId['name']);
            // $url = 'https://app.notify.lk/api/v1/send?user_id=25924&api_key=Pqc1F2e5SrlT0HZxpxi6&message=ok&to=94767632972&sender_id=Nsonic%20Info';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            echo $response;

            // $response = '{"status":"success","data":"Sent"}';
            // echo $response;

            require "../util/activity.php";

            $date_time = date("Y-m-d H:i:s");

            Database::search("INSERT INTO `message`(`phone`,`message`,`date_time`,`shop_id`,`message_send_id_id`,`responce`) VALUES(?,?,?,?,?,?) ", "ssssss", [$mobile, $_POST['text'], $date_time, '0', $senderId['id'], $response]);

            Activity::newActivity("sent a SMS to " . $mobile . ".", 1, "Please add href");

            curl_close($ch);
        } else {
            $object1->status = "Unexpected Error";
        }
    }
} else {
    $object1->status = "reload";
}

if ($isOkay) {
    echo json_encode($object1);
}
