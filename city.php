<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Nsonic</title>

        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="DashboardKit is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
        <meta name="keywords" content="DashboardKit, Dashboard Kit, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Free Bootstrap Admin Template">
        <meta name="author" content="DashboardKit ">

        <!-- Favicon icon -->
        <link rel="icon" href="assets/images/favi/favi.png" type="image/x-icon">

        <!-- font css -->
        <link rel="stylesheet" href="assets/fonts/feather.css">
        <link rel="stylesheet" href="assets/fonts/fontawesome.css">
        <link rel="stylesheet" href="assets/fonts/material.css">

        <!-- vendor css -->
        <link rel="stylesheet" href="assets/css/templateStyle.css" id="main-style-link" />
        <link rel="stylesheet" href="assets/css/style.css" />

        <!-- quill css -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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

                <!-- [ Main Content ] start -->
                <div class="row px-2 px-md-4 ">
                    <div class="col-12">

                        <h1 class="mb-5 text-white">Add New City</h1>

                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-10 col-xxl-10 mx-auto p-2 p-md-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border p-3 p-md-5 rounded rounded-5">

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">City name</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="name" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Province</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <select name="" id="province" onchange="loadDistricts();" class="form-control">
                                                <option value="0">Select Province</option>
                                                <?php
                                                $province_rs = Database::search("SELECT * FROM `province` ORDER BY `name` ASC");

                                                while ($province = $province_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $province['id'] ?>"><?= $province['name'] ?></option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">District</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <select name="" id="district" class="form-control">
                                                <option value="0">Select District</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <button class="btn submit-btn w-100" onclick="addCity();">Add New City</button>
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