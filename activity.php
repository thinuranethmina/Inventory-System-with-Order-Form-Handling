<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN])) {

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
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Nsonic</title>

        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="author" content="Nsonic">

        <!-- Favicon icon -->
        <link rel="icon" href="assets/images/favi/favi.png" type="image/x-icon">

        <!-- font css -->
        <link rel="stylesheet" href="assets/fonts/feather.css">
        <link rel="stylesheet" href="assets/fonts/fontawesome.css">
        <link rel="stylesheet" href="assets/fonts/material.css">

        <!-- vendor css -->
        <link rel="stylesheet" href="assets/css/templateStyle.css" id="main-style-link" />
        <link rel="stylesheet" href="assets/css/style.css" />

        <!-- Include Chosen CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

        <style>
            .pc-container {
                background-image: url('assets/images/back-ground/blue-half.png');
                background-repeat: no-repeat;
                background-position: top;
                /* background-size: cover; */
            }
        </style>

    </head>

    <body class="">

        <!-- [ Header ] start -->
        <?php include "include/header.php"; ?>
        <!-- [ Header ] end -->

        <!-- [ Main Content ] start -->
        <div class="pc-container">
            <div class="pcoded-content">

                <div id="modal-1" class="modal-1 show-modal">
                    <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content">
                        <div class="row">
                            <div class="modal--header">
                                <div class="d-flex ">
                                    <h2 class="my-auto ">Activity</h2>
                                    <span class="close ml-auto my-auto" onclick="closeModel();">&times;</span>
                                </div>
                                <hr class="text-dark my-2">
                            </div>
                            <div class="modal--body">
                                <div class="row p-3 text-center">
                                    <div class="col-12">
                                        <span class="my-auto">You added new product(NSCKE).</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal--footer d-sm-flex text-secondary">
                                <span class="text-start ms-0 my-auto"> Date & Time:<span class="fst-italic text-secondary">2023-10-06 06:13:54</span>
                                </span>
                                <div class="ml-auto float-right p-2">
                                    <button class="btn btn-primary">close</button>
                                    <button class="btn btn-primary">View</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- [ breadcrumb ] start -->
                <!-- // add after .pcoded-content div
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Dashboard sale</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item">Dashboard sale</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> -->
                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->
                <div class="row px-4">
                    <div class="col-12 mb-3 px-2">
                        <h1 class="text-white">Activities</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-4 offset-md-4 col-xl-3 offset-xl-6 mb-2 mb-md-0">
                                <select id="user" class="form-control" onchange="changeResult('activity');">
                                    <option value="0">All Users</option>
                                    <?php
                                    $user_rs = Database::search("SELECT * FROM `user`");

                                    while ($users = $user_rs->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $users['id'] ?>"><?= $users['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-xl-3 mb-2 mb-md-0">
                                <select id="priority" class="form-select select-chosen chosen-select" data-placeholder="Choose Priority" onchange="changeResult('activity');" multiple>
                                    <option value="1">High</option>
                                    <option value="2">Medium</option>
                                    <option value="3">Low</option>
                                </select>
                            </div>


                        </div>
                    </div>

                    <?php
                    $activity_rs = Database::search("SELECT *,`activity`.`id` AS `id`,`activity`.`date_time`, `user`.`nic` FROM `activity_has_viewer` INNER JOIN `activity` ON `activity`.`id` = `activity_has_viewer`.`activity_id` INNER JOIN `user` ON `user`.`id` = `activity`.`user_id`  WHERE `activity_has_viewer`.`viewer_id` = ? ORDER BY  `activity`.`date_time` DESC ", "s", [$_SESSION['user']['id']]);
                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        <div class="row">
                            <div class="col-12 d-none d-md-block">
                                <span class="text-white f-w-300">Showing <?= $activity_rs->num_rows ?> of <?= $activity_rs->num_rows ?> entries</span>
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
                    </div>

                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
        <!-- [ Main Content ] end -->



        <?php

        Database::iud("UPDATE `activity_has_viewer` SET `is_read` = '1' WHERE `viewer_id` = ? ", "s", [$_SESSION['user']['id']]);

        ?>

        <?php require_once('include/footer.php'); ?>
    </body>

    </html>
<?php
}
?>