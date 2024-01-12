<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {

    if (isset($_GET['id'])) {
        $shop_rs = Database::search("SELECT *,`shop`.`id` AS `id`,`shop`.`name` AS `shop`  FROM `shop` INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id`  WHERE `shop`.`id` = ? ", "s", [$_GET['id']]);

        if ($shop_rs->num_rows == 1) {

            $shop = $shop_rs->fetch_assoc();

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
                        <div class="row px-1 px-md-4 ">
                            <div class="col-12">

                                <h1 class="mb-5 text-white">Update Product</h1>

                                <div class="row">
                                    <div class="col-12 col-md-11 col-lg-12 col-xl-10 col-xxl-10 mx-auto p-0 p-md-3 rounded rounded-5 bg-white box-shadow mb-5">
                                        <div class="card-border p-2 p-md-5 rounded rounded-5">

                                            <div class="row my-3">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Name</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <input type="text" id="name" class="form-control" value="<?= $shop['shop'] ?>" placeholder="Enter Name Here">
                                                </div>
                                            </div>

                                            <div class="row my-3">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Primary Mobile</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <input type="text" id="mobile" class="form-control" value="<?= $shop['mobile'] ?>" placeholder="Enter Primary Mobile Here">
                                                </div>
                                            </div>

                                            <div class="row my-3">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Other Mobile</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <input type="text" id="omobile" class="form-control" value="<?= $shop['other_mobile'] ?>" placeholder="Enter Other Mobile Here">
                                                </div>
                                            </div>

                                            <div class="row my-3">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Address</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <input type="text" id="address" class="form-control" value="<?= $shop['address'] ?>" placeholder="Enter Address Here">
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
                                                                                                    if ($shop['province_id'] == $province['id']) {
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
                                                        $district_rs = Database::search("SELECT * FROM `district` WHERE `province_id`= '" . $shop['province_id'] . "' ORDER BY `name` ASC");

                                                        while ($district = $district_rs->fetch_assoc()) {
                                                        ?>
                                                            <option value="<?= $district['id'] ?>" <?php
                                                                                                    if ($shop['district_id'] == $district['id']) {
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
                                                        $city_rs = Database::search("SELECT * FROM `city` WHERE `district_id`= '" . $shop['district_id'] . "' ORDER BY `name` ASC");

                                                        while ($city = $city_rs->fetch_assoc()) {
                                                        ?>
                                                            <option value="<?= $city['id'] ?>" <?php
                                                                                                if ($shop['city_id'] == $city['id']) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>><?= $city['name'] ?></option>
                                                        <?php
                                                        }

                                                        ?>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="row my-3 mb-5 pb-5">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Description</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <div id="editor" class="">
                                                        <?= $shop['description'] ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row my-3 pt-5 pt-lg-3">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Shop Image</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <div class="col-12 col-lg-9">
                                                        <img class="d-none" id="remove0" width="25" height="25" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />

                                                        <label for="imgchooser0" style="cursor: pointer;">
                                                            <img class="mx-1 p-2" id="image0" src="<?= $shop['image'] ?>" style="min-width: 100px; max-width: 180px; height: auto; border-radius: 5px;" onmouseover="this.style.backgroundColor='#FFE9D7';" onmouseout="this.style.backgroundColor='white';" />
                                                        </label>
                                                        <input type="file" accept="image/png, image/jpeg" onclick="addImage('0');" class="form-control post-ad-form d-none" id="imgchooser0">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row my-3">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Location</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <input type="text" id="location" placeholder="latitude, longitude" value="<?= $shop['latitude'] . ', ' . $shop['longitude'] ?>" class="form-control" onkeyup="updateLocation();">
                                                </div>
                                                <div class="col-12">
                                                    <div id="gmap" style="width: 100%; height: 400px;" class="mt-3">
                                                        <iframe src="https://maps.google.com/maps?q=<?= $shop['latitude'] ?>,<?= $shop['longitude'] ?>&amp;z=15&amp;output=embed" style="width: 100%; height: 400px; border: 1px solid rgb(178, 178, 178); z-index: 0;" class="rounded rounded-3"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-auto ml-auto mr-0">
                                                    <button class="btn btn-secondary" onclick="resetGMap();">Set Live Location</button>
                                                </div>
                                            </div>


                                            <div class="row mt-5">
                                                <div class="col-12">
                                                    <button class="btn submit-btn w-100" onclick="updateShop('<?= $shop['id'] ?>');">Save Changes</button>
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
                <script>
                    function updateLocation() {
                        var location = document.getElementById('location').value.split(',');


                        updateGoogleMap(location[0], location[1]);
                    }

                    function updateGoogleMap(lat, lng) {
                        var gmapDiv = document.getElementById('gmap');
                        gmapDiv.innerHTML = '<iframe src="https://maps.google.com/maps?q=' + lat + ',' + lng + '&amp;z=15&amp;output=embed" style="width: 100%; height: 400px; border: 1px solid rgb(178, 178, 178); z-index: 0;" class="rounded rounded-3"></iframe>';
                    }

                    function resetGMap() {

                        // Get user's location
                        if ('geolocation' in navigator) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var userLat = position.coords.latitude;
                                var userLng = position.coords.longitude;

                                document.getElementById('location').value = userLat + "," + userLng;

                                updateGoogleMap(userLat, userLng);
                            }, function(error) {
                                console.error('Error getting user location: ' + error.message);

                                Swal.fire(
                                    'Support',
                                    'Please allow location in your device & press "Set Live Location" button again.',
                                    'question'
                                )
                            });
                        } else {
                            console.error('Geolocation is not supported by this browser.');

                            Swal.fire(
                                'Not Support',
                                'Geolocation is not supported by your browser.',
                                'info'
                            )
                        }
                    }
                </script>

                <?php require 'include/footer.php'; ?>

                <!-- Initialize Quill editor -->
                <script>
                    var toolbarOptions = [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link', 'image'], // toggled buttons
                        ['blockquote', 'code-block'],

                        [{
                            'header': 1
                        }, {
                            'header': 2
                        }], // custom button values
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'script': 'sub'
                        }, {
                            'script': 'super'
                        }], // superscript/subscript
                        [{
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }], // outdent/indent
                        [{
                            'direction': 'rtl'
                        }], // text direction

                        [{
                            'size': ['small', false, 'large', 'huge']
                        }], // custom dropdown
                        [{
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],

                        [{
                            'color': []
                        }, {
                            'background': []
                        }], // dropdown with defaults from theme
                        [{
                            'font': []
                        }],
                        [{
                            'align': []
                        }],

                        ['clean'] // remove formatting button
                    ];

                    var quill = new Quill('#editor', {
                        modules: {
                            toolbar: toolbarOptions,

                        },
                        theme: 'snow'
                    });
                </script>
            </body>

            </html>

<?php

        } else {
            header("Location: ./");
        }
    } else {
        header("Location: ./");
    }
}

?>