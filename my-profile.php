<?php


require "util/userStatus.php";

if (User::is_allow()) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Settings | Nsonic</title>

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

        <style>
            .pc-container {
                background-image: url('assets/images/back-ground/blue-half.png');
                background-repeat: no-repeat;
                background-position: top;
                background-size: cover;
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


                <div class="row px-2 px-lg-4">
                    <div class="col-12 mb-3 px-2">
                        <h1 class="text-white">Privacy Settings</h1>
                    </div>

                    <?php

                    $user_rs = Database::search("SELECT * FROM `user` WHERE `id` = ? ", "s", [$_SESSION['user']['id']]);

                    if ($user_rs->num_rows == 1) {
                        $user = $user_rs->fetch_assoc();
                    ?>

                        <div class="col-12 text-center">
                            <img class="rounded rounded-circle border border-3 settings-profile-img bg-white" src="<?= $user['profile_image'] ?>" alt="Profile Image">
                        </div>

                        <div class="col-12 col-md-11 col-lg-12 col-xl-10 col-xxl-10 mx-auto p-2 p-md-3 rounded rounded-5 bg-white box-shadow mb-5 mt-3 mt-lg-5">
                            <div class="card-border p-3 p-md-5 rounded rounded-5">

                                <div class="row my-3">
                                    <div class="col-12 col-lg-3 my-auto">
                                        <span class="form-text-1">Full name:</span>
                                    </div>
                                    <div class="col-12 col-lg-9">
                                        <span><?= $user['name'] ?></span>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-12 col-lg-3 my-auto">
                                        <span class="form-text-1">NIC:</span>
                                    </div>
                                    <div class="col-12 col-lg-9">
                                        <span><?= $user['nic'] ?></span>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-12 col-lg-3 my-auto">
                                        <span class="form-text-1">Mobile:</span>
                                    </div>
                                    <div class="col-12 col-lg-9">
                                        <span><?= $user['mobile'] ?></span>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-12 col-lg-3 my-auto">
                                        <span class="form-text-1">Added Date:</span>
                                    </div>
                                    <div class="col-12 col-lg-9">
                                        <span><?= date('Y-m-d', strtotime($user['date_time'])) ?></span>
                                    </div>
                                </div>

                            </div>
                        </div>


                    <?php
                    } else {
                        header("location:signout.php");
                    }

                    ?>



                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
        <!-- [ Main Content ] end -->


        <?php require 'include/footer.php'; ?>
    </body>

    </html>
<?php
}
?>