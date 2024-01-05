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

                        <h1 class="mb-5 text-white">Add New User</h1>

                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-10 col-xxl-10 mx-auto p-2 p-md-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border p-3 p-md-5 rounded rounded-5">

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Full name</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="name" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">NIC</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="nic" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Mobile</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="mobile" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Password</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="password" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Address</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="address" class="form-control" placeholder="Enter Here . . .">
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
                                            <select name="" id="district" onchange="loadCities();" class="form-control">
                                                <option value="0">Select District</option>

                                            </select>

                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">City</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <select name="" id="city" class="form-control">
                                                <option value="0">Select City</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">User Type</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <select id="userType" onchange="loadSubCategories(this);" class="form-control">
                                                <option value="0">Select Type</option>
                                                <?php
                                                $category_rs = Database::search("SELECT * FROM `user_type` WHERE `id`!='1' ORDER BY `name` ASC");

                                                while ($category = $category_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row my-3 pt-5 pt-lg-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Profile Image</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <img class="d-none" id="remove0" width="25" height="25" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />

                                            <label for="imgchooser0" style="cursor: pointer;">
                                                <img class="mx-1 p-2" id="image0" src="assets/images/icons/user.png" style="min-width: 100px; max-width: 160px; height: auto; border-radius: 5px;" onmouseover="this.style.backgroundColor='#FFE9D7';" onmouseout="this.style.backgroundColor='white';" />
                                            </label>
                                            <input type="file" accept="image/png, image/jpeg" onclick="addImage('0');" class="form-control post-ad-form d-none" id="imgchooser0" name="ad_cover_image">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">NIC Images</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <label for="imgchooser1" class="me-1 mb-1" style="cursor: pointer;">
                                                <img onclick="removeImage('1');" id="remove1" style="position: absolute; z-index: 1; cursor: pointer; display:none " width="30" height="30" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />
                                                <img id="image1" class="mx-1 p-2" src="assets/images/icons/nic_front.png" onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.21)';" onmouseout="this.style.backgroundColor='white';" style="min-width: 70px; max-width: 150px; height: auto; border-radius: 5px;" />
                                            </label>
                                            <input type="file" onclick="addImage('1');" accept="image/png, image/jpeg" class="form-control post-ad-form d-none" id="imgchooser1" name="ad_cover_image">

                                            <label for="imgchooser2" class="mb-1" style="cursor: pointer;">
                                                <img onclick="removeImage('2');" id="remove2" style="position: absolute; z-index: 1; cursor: pointer; display:none " width="30" height="30" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />
                                                <img id="image2" class="mx-1 p-2" src="assets/images/icons/nic_back.png" onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.21)';" onmouseout="this.style.backgroundColor='white';" style="min-width: 70px; max-width: 150px; height: auto; border-radius: 5px;" />
                                            </label>
                                            <input type="file" onclick="addImage('2');" accept="image/png, image/jpeg" class="form-control post-ad-form d-none" id="imgchooser2" name="ad_cover_image">
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <button class="btn submit-btn w-100" onclick="addUser();">Add New User</button>
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