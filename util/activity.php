<?php


class Activity
{

    public static function newActivity($msg, $priority, $href = null)
    {
        $refId = time();

        $date_time = date("Y-m-d H:i:s");

        Database::iud("INSERT INTO `activity`(`ref`,`message`,`date_time`,`priority_id`,`user_id`) VALUES(?,?,?,?,?) ", "sssss", [$refId, $msg, $date_time, $priority, $_SESSION['user']['id']]);

        $activity_id = Database::search("SELECT * FROM `activity` WHERE `ref` =? ORDER BY `id` DESC ", "s", [$refId])->fetch_assoc()['id'];

        return Database::iud("INSERT INTO `activity_has_viewer`(`activity_id`,`viewer_id`,`href`,`is_read`) VALUES(?,?,?,?) ", "ssss", [$activity_id, '1', $href, '0']);
    }
}
