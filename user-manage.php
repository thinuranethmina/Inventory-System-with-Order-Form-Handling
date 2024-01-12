<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN])) {

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

        <!-- Carousel -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <style>
            .col-center {
                margin: 0 auto;
                float: none !important;
            }

            .carousel {
                padding: 0 70px;
            }

            .carousel .carousel-item {
                color: #999;
                font-size: 14px;
                text-align: center;
                overflow: hidden;
                min-height: 290px;
            }

            .carousel .carousel-item .img-box {
                width: 135px;
                height: 135px;
                margin: 0 auto;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 50%;
            }

            .carousel .img-box img {
                width: 100%;
                height: 100%;
                display: block;
                border-radius: 50%;
            }

            .carousel .testimonial {
                padding: 30px 0 10px;
            }

            .carousel .overview {
                font-style: italic;
            }

            .carousel .overview b {
                text-transform: uppercase;
                color: #7AA641;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 40px;
                height: 40px;
                margin-top: -20px;
                top: 50%;
                background: none;
            }

            .carousel-control-prev i,
            .carousel-control-next i {
                font-size: 40px;
                line-height: 42px;
                position: absolute;
                display: inline-block;
                color: rgba(0, 0, 0, 0.8);
                text-shadow: 0 3px 3px #e6e6e6, 0 0 0 #000;
            }

            .carousel-indicators {
                bottom: 0px;
            }

            .carousel-indicators li,
            .carousel-indicators li.active {
                width: 12px;
                height: 12px;
                margin: 1px 3px;
                border-radius: 50%;
                border: none;
            }

            .carousel-indicators li {
                background: #999;
                border-color: transparent;
                box-shadow: inset 0 2px 1px rgba(0, 0, 0, 0.2);
            }

            .carousel-indicators li.active {
                background: #555;
                box-shadow: inset 0 2px 1px rgba(0, 0, 0, 0.2);
            }

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
                <div class="row px-0 px-md-4">
                    <div class="col-12 mb-3 px-2">
                        <h1 class="text-white">User Manage</h1>
                    </div>

                    <div class="col-12">
                        <div class="row">

                            <div class="col-12 col-md-4 offset-md-8 col-xl-3 offset-xl-9 mb-2 mb-md-0">
                                <input type="text" class="form-control" onkeyup="changeResult('user');" id="search" placeholder="Search" />
                            </div>


                        </div>
                    </div>

                    <?php
                    $user_rs = Database::search("SELECT *,`user`.`id` AS `id` FROM `user` WHERE `id` != '1' ORDER BY  `user`.`date_time` DESC ");
                    ?>

                    <div class="col-12 px-2" id="resultContent">
                        <div class="row">
                            <div class="col-12 d-none d-md-block">
                                <span class="text-white f-w-300">Showing <?= $user_rs->num_rows ?> of <?= $user_rs->num_rows ?> entries</span>
                            </div>
                        </div>

                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none d-md-table-cell">Profile Image</th>
                                    <th>NIC</th>
                                    <th>Name</th>
                                    <th>User Type</th>
                                    <th class="d-none d-md-table-cell text-center">Status</th>
                                    <th class=" text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $x = 1;
                                $date = null;
                                while ($user = $user_rs->fetch_assoc()) {

                                ?>

                                    <tr>
                                        <td>
                                            <?= $x ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <img src="<?= $user['profile_image'] ?>" class="table-main-image">
                                        </td>
                                        <td> <?= $user['nic'] ?></td>
                                        <td> <?= $user['name'] ?></td>
                                        <td> <?php
                                                if ($user['user_type_id'] == '2') {
                                                    echo "ADMIN";
                                                } else if ($user['user_type_id'] == '3') {
                                                    echo "SALES MANAGER";
                                                } else if ($user['user_type_id'] == '4') {
                                                    echo "SELLER";
                                                }  ?></td>
                                        <td class="d-none d-md-table-cell text-center" id="status<?= $user['id'] ?>">
                                            <?= $user['status_id'] == 1 ? '<div class="status status-active mx-auto">Active</div>' : '<div class="status status-deactive mx-auto">Deactive</div>' ?>
                                        </td>
                                        <td class=" text-center" style="min-width: 106px;">
                                            <div class="check-box2 p-2 d-inline-block mr-1 z-0">
                                                <input class="z-0" id="toggleStatus" onchange="changeStatus('User',<?= $user['id'] ?>);" type="checkbox" <?= $user['status_id'] == 1 ? "checked" : "" ?>>
                                            </div>
                                            <img class="mr-1 action-icon" onclick="viewModal('<?= $user['id'] ?>','user');" src="assets/images/icons/view.png">
                                            <a href="update-user.php?id=<?= $user['id'] ?>" target="_blank"><img class="action-icon" src="assets/images/icons/edit.png"></a>
                                        </td>
                                    </tr>

                                <?php

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


        <?php require_once('include/footer.php'); ?>
    </body>

    </html>
<?php
}
?>