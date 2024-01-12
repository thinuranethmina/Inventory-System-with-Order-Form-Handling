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

                    <div class="col-12">
                        <div class="row pt-4 mb-5 ">
                            <div class="col-10 col-lg-8 col-xl-6 col-xxl-5 mx-auto rounded rounded-5 mb-5 p-2 p-lg-3 bg-white box-shadow">

                                <div class="card-border px-3 px-lg-5 py-4 rounded rounded-5">

                                    <div class="row my-3">
                                        <div class="col-12">
                                            <div class="form-text-2">Old Password</div>
                                            <input type="password" id="opassword" class="form-control">
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row my-3">
                                        <div class="col-12">
                                            <div>New Password</div>
                                            <input type="password" id="npassword" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row my-3">
                                        <div class="col-12">
                                            <div>Confirm Password</div>
                                            <input type="password" id="cpassword" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <button class="btn submit-btn w-100" onclick="changePassword();">Submit</button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>


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