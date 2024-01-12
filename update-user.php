<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN]) && isset($_GET['id'])) {
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

                        <h1 class="mb-5 text-white">Update User</h1>

                        <?php

                        $user_rs = Database::search("SELECT *,`user`.`id` AS `id`,`user`.`name` AS `user` FROM `user`  INNER JOIN `city` ON `city`.`id` = `user`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id`  WHERE `user`.`id` = ? ", "s", [$_GET['id']]);

                        if ($user_rs->num_rows == 1) {
                            $user = $user_rs->fetch_assoc();
                        } else {
                        ?>
                            <script>
                                window.location = "index.php";
                            </script>
                        <?php
                        }

                        ?>
                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-10 col-xxl-10 mx-auto p-2 p-md-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border p-3 p-md-5 rounded rounded-5">

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Full name</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="name" class="form-control" value="<?= $user['user'] ?>" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Mobile</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="mobile" class="form-control" value="<?= $user['mobile'] ?>" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Address</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="address" class="form-control" value="<?= $user['address'] ?>" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Province</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <select name="" onchange="loadDistricts();" id="province" class="form-control">
                                                <option value="0">Select Province</option>
                                                <?php
                                                $province_rs = Database::search("SELECT * FROM `province` ORDER BY `name` ASC");

                                                while ($province = $province_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $province['id'] ?>" <?php
                                                                                            if ($user['province_id'] == $province['id']) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?= $province['name'] ?></option>
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
                                            <select onchange="loadCities();" id="district" class="form-control">
                                                <option value="0">Select District</option>
                                                <?php
                                                $district_rs = Database::search("SELECT * FROM `district` WHERE `province_id`= '" . $user['province_id'] . "' ORDER BY `name` ASC");

                                                while ($district = $district_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $district['id'] ?>" <?php
                                                                                            if ($user['district_id'] == $district['id']) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?= $district['name'] ?></option>
                                                <?php
                                                }

                                                ?>
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
                                                <?php
                                                $city_rs = Database::search("SELECT * FROM `city` WHERE `district_id`= '" . $user['district_id'] . "' ORDER BY `name` ASC");

                                                while ($city = $city_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $city['id'] ?>" <?php
                                                                                        if ($user['city_id'] == $city['id']) {
                                                                                            echo "selected";
                                                                                        }
                                                                                        ?>><?= $city['name'] ?></option>
                                                <?php
                                                }

                                                ?>
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
                                                    <option value="<?= $category['id'] ?>" <?php
                                                                                            if ($category['id'] == $user['user_type_id']) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?= $category['name'] ?></option>
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
                                                <img class="mx-1 p-2" id="image0" src="<?= $user['profile_image'] ?>" style="min-width: 100px; max-width: 160px; height: auto; border-radius: 5px;" onmouseover="this.style.backgroundColor='#FFE9D7';" onmouseout="this.style.backgroundColor='white';" />
                                            </label>
                                            <input type="file" accept="image/png, image/jpeg" onclick="addImage('0');" class="form-control post-ad-form d-none" id="imgchooser0">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">NIC Images</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <img class="d-none" id="remove1" width="25" height="25" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />

                                            <label for="imgchooser1" style="cursor: pointer;">
                                                <img class="mx-1 p-2" id="image1" src="<?= $user['nic_front'] ?>" style="min-width: 100px; max-width: 150px; height: auto; border-radius: 5px;" onmouseover="this.style.backgroundColor='#FFE9D7';" onmouseout="this.style.backgroundColor='white';" />
                                            </label>
                                            <input type="file" accept="image/png, image/jpeg" onclick="addImage('1');" class="form-control post-ad-form d-none" id="imgchooser1">

                                            <img class="d-none" id="remove2" width="25" height="25" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />

                                            <label for="imgchooser2" style="cursor: pointer;">
                                                <img class="mx-1 p-2" id="image2" src="<?= $user['nic_back'] ?>" style="min-width: 100px; max-width: 150px; height: auto; border-radius: 5px;" onmouseover="this.style.backgroundColor='#FFE9D7';" onmouseout="this.style.backgroundColor='white';" />
                                            </label>
                                            <input type="file" accept="image/png, image/jpeg" onclick="addImage('2');" class="form-control post-ad-form d-none" id="imgchooser2">
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <button class="btn submit-btn w-100" onclick="updateUser('<?= $user['id'] ?>');">Update User</button>
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