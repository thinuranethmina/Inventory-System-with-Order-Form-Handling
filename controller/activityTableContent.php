<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['user']) && isset($_POST['priority'])) {

        function getRelativeTime($publishDate)
        {
            $diff = time() - strtotime($publishDate);
            $intervals = array(
                31536000 => 'year',
                2592000 => 'month',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
            );

            foreach ($intervals as $secs => $label) {
                $div = $diff / $secs;

                if (date('Y-m-d', strtotime($publishDate)) != date("Y-m-d")) {
                    return date('h:i A', strtotime($publishDate));
                } else {
                    if ($div >= 1) {

                        $timeAgo = round($div);
                        return $timeAgo . ' ' . $label . ($timeAgo > 1 ? 's' : '') . ' ago';
                    }
                }
            }

            return 'just now';
        }


        $all_activity_rs = Database::search("SELECT *,`activity`.`id` AS `id`,`activity`.`date_time`, `user`.`nic` FROM `activity_has_viewer` INNER JOIN `activity` ON `activity`.`id` = `activity_has_viewer`.`activity_id` INNER JOIN `user` ON `user`.`id` = `activity`.`user_id`  WHERE `activity_has_viewer`.`viewer_id` = ? ORDER BY  `activity`.`date_time` DESC ", "s", [$_SESSION['user']['id']]);

        $sql = "SELECT *,`activity`.`id` AS `id`,`activity`.`date_time`, `user`.`nic` FROM `activity_has_viewer` INNER JOIN `activity` ON `activity`.`id` = `activity_has_viewer`.`activity_id` INNER JOIN `user` ON `user`.`id` = `activity`.`user_id`  WHERE `activity_has_viewer`.`viewer_id` = '" . $_SESSION['user']['id'] . "' ";

        if ($_POST['user'] != 0 && $_POST['user'] != '' && $_POST['user'] != null) {
            $sql .= " AND `user_id` = '" . $_POST['user'] . "' ";
        }

        if ($_POST['priority'] != 0 && $_POST['priority'] != '' && $_POST['priority'] != null) {

            $str_arr = preg_split("/,/", $_POST['priority']);
            $sql .= " AND ( ";

            $isFirst = true;

            foreach ($str_arr as &$element) {
                if (!$isFirst) {
                    $sql .= " OR ";
                }
                $sql .= " `priority_id` = '" . $element . "' ";
                $isFirst = false;
            }
            $sql .= " )";
        }

        $activity_rs = Database::search($sql . " ORDER BY  `activity`.`date_time` DESC ");

?>

        <div class="row">
            <div class="col-12 d-none d-md-block">
                <span class="text-white f-w-300">Showing <?= $activity_rs->num_rows ?> of <?= $all_activity_rs->num_rows ?> entries</span>
            </div>
        </div>

        <table class="" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Activity</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $x = 1;
                $date = null;
                while ($activity = $activity_rs->fetch_assoc()) {

                    $user;

                    if ($activity['nic'] == $_SESSION['user']['nic']) {
                        $user = "<b>You</b>";
                    } else {
                        $user = "<b>" . $activity['name'] . "</b>";
                    }


                    if ($date == date('Y-m-d', strtotime($activity['date_time']))) {
                ?>

                        <tr>
                            <td><?= $x ?>
                            </td>
                            <td>
                                <?php
                                if ($activity['is_read'] == '0') {
                                ?>
                                    <span class="new-notification-icon">NEW</span>
                                <?php
                                }
                                ?>
                                <?= ($activity['nic'] == $_SESSION['user']['nic'] ? '<b>You</b>' : '<b>' . $activity['name'] . '</b>') . " " . $activity['message'] ?>
                            </td>
                            <td><?= getRelativeTime($activity['date_time']) ?></td>
                            <td>
                                <img class="me-2 action-icon" onclick="viewModal('<?= $activity['id'] ?>','activity');" src="assets/images/icons/view.png">
                            </td>
                        </tr>

                    <?php

                    } else {
                        $date = date('Y-m-d', strtotime($activity['date_time']));
                    ?>
                        <tr class="shadow-none" style="height: 8px !important;">
                            <td colspan="4" class="text-center date-row"><?= date("Y-m-d") == $date ? "Today" : $date ?>
                            </td>
                        </tr>


                        <tr>
                            <td><?= $x ?>
                            </td>
                            <td>
                                <?php
                                if ($activity['is_read'] == '0') {
                                ?>
                                    <span class="new-notification-icon">NEW</span>
                                <?php
                                }
                                ?>
                                <?= ($activity['nic'] == $_SESSION['user']['nic'] ? '<b>You</b>' : '<b>' . $activity['name'] . '</b>') . " " . $activity['message'] ?>
                            </td>
                            <td><?= getRelativeTime($activity['date_time']) ?></td>
                            <td>
                                <img class="me-2 action-icon" onclick="viewModal('<?= $activity['id'] ?>','activity');" src="assets/images/icons/view.png">
                            </td>
                        </tr>
                <?php
                    }
                    $x++;
                }


                ?>
            </tbody>
        </table>

<?php
    }
} else {
    echo "reload";
}
