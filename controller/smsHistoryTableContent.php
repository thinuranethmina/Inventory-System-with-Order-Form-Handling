<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['shop']) && isset($_POST['message_send_id'])) {

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


        $all_sms_rs = Database::search("SELECT * FROM `message` ");

        $sql = "SELECT *,`shop`.`id` AS `shopId`,`shop`.`name` AS `shop`,`message`.`id` AS `id`,`message_send_id`.`name` AS `send_id`, `message`.`date_time` AS `date_time` FROM `message` INNER JOIN `message_send_id` ON `message_send_id`.`id` = `message`.`message_send_id_id` INNER JOIN `shop` ON `shop`.`id`=`message`.`shop_id` WHERE `message`.`id` != '0' ";

        if ($_POST['shop'] != 0 && $_POST['shop'] != '' && $_POST['shop'] != null) {
            $sql .= " AND `shop`.`id` = '" . $_POST['shop'] . "' ";
        }

        if ($_POST['message_send_id'] != 0 && $_POST['message_send_id'] != '' && $_POST['message_send_id'] != null) {
            $sql .= " AND `message_send_id`.`id` = '" . $_POST['message_send_id'] . "' ";
        }

        $isTextEmpty = false;

        if (isset($_POST['text'])) {
            if (trim($_POST['text']) != '' && $_POST['text'] != null) {
                $sql .= " AND (`shop`.`address` LIKE ? OR `message_send_id`.`name` LIKE ?  OR `message`.`phone` LIKE ? OR `message`.`message` LIKE ?  OR `shop`.`name` LIKE ?  OR `message`.`responce` LIKE ? ) ";
            } else {
                $isTextEmpty = true;
            }
        } else {
            $isTextEmpty = true;
        }

        if ($isTextEmpty) {
            $sms_rs = Database::search($sql . " ORDER BY `message`.`date_time` DESC ");
        } else {
            $_POST['text'] = trim($_POST['text']);
            $sms_rs = Database::search($sql . " ORDER BY `message`.`date_time` DESC ", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
        }

?>

        <div class="row">
            <div class="col-12 d-none d-md-block">
                <span class="text-white f-w-300">Showing <?= $sms_rs->num_rows ?> of <?= $all_sms_rs->num_rows ?> entries</span>
            </div>
        </div>

        <table class="" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Shop</th>
                    <th>Message</th>
                    <th class="d-none d-md-table-cell text-center">Time</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php



                if ($sms_rs->num_rows <= 0) {
                ?>
                    <tr>
                        <td class="text-center rounded-3" colspan="7">
                            <h4 class="m-auto">No Result</h4>
                        </td>
                    </tr>
                    <?php
                }


                $x = 1;
                $date = null;
                while ($msg = $sms_rs->fetch_assoc()) {

                    if ($date == date('Y-m-d', strtotime($msg['date_time']))) {
                    ?>

                        <tr>
                            <td>
                                <?= $x ?>
                            </td>
                            <td>
                                <span class="text-truncate-1">
                                    <?php
                                    if ($msg['shopId'] == '0') {
                                        echo $msg['phone'];
                                    } else {
                                        echo $msg['shop'];
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <?= $msg['send_id'] . ": " . $msg['message'] ?>
                            </td>
                            <td class="d-none d-lg-table-cell text-center"><?= getRelativeTime($msg['date_time']) ?></td>
                            <td class="text-center">
                                <img class="me-2 action-icon" onclick="viewModal('<?= $msg['id'] ?>','SMS');" src="assets/images/icons/view.png">
                            </td>
                        </tr>

                    <?php

                    } else {
                        $date = date('Y-m-d', strtotime($msg['date_time']));
                    ?>
                        <tr class="shadow-none" style="height: 8px !important;">
                            <td colspan="8" class="text-center date-row"><?= date("Y-m-d") == $date ? "Today" : $date ?>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <?= $x ?>
                            </td>

                            <td>
                                <span class="text-truncate-l">
                                    <?php
                                    if ($msg['shopId'] == '0') {
                                        echo $msg['phone'];
                                    } else {
                                        echo $msg['shop'];
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate-1"><?= $msg['send_id'] . ": " . $msg['message'] ?></span>
                            </td>
                            <td class="d-none d-lg-table-cell text-center" style="min-width: 120px;"><?= getRelativeTime($msg['date_time']) ?></td>
                            <td class="text-center">
                                <img class="me-2 action-icon" onclick="viewModal('<?= $msg['id'] ?>','SMS');" src="assets/images/icons/view.png">
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
