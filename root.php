<?php


require "util/userStatus.php";

if (User::is_allow()) {
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
                <div class="row px-4 ">
                    <div class="col-12">

                        <h1 class="mb-5 text-white">Add New Root</h1>

                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-10 col-xxl-10 mx-auto p-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border p-5 rounded rounded-5">

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Name</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="name" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <?php


                                    ?>

                                    <div class="row my-3 mt-5">
                                        <div class="col-12 col-lg-4">
                                            <select name="" id="district" class="form-control" onchange="loadCities();">
                                                <option value="0">Select District</option>
                                                <?php
                                                $district_rs = Database::search("SELECT * FROM `district` ORDER BY `name` ASC");
                                                while ($district = $district_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $district['id'] ?>"><?= $district['name'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <select name="" id="city" class="form-control">
                                                <option value="0">Select City</option>
                                            </select>

                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <button class="btn submit-btn w-100" onclick="addRootLocation();">Add New Location</button>
                                        </div>

                                        <div class="col-12 py-3">

                                            <ol class="width-fit-content mx-auto" id="citiesList">

                                            </ol>

                                        </div>
                                    </div>

                                    <div class="row my-3 mb-5 pb-5">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Description</span>
                                        </div>
                                        <div class="col-12 col-lg-9 mb-5 mb-sm-0">
                                            <div id="editor">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-12">
                                            <button class="btn submit-btn w-100" onclick="addProduct();">Submit</button>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5sortable/0.13.3/html5sortable.min.js" integrity="sha512-3btxfhQfasdVcv1dKYZph5P7jFeeLRcF1gDVzFA+k9AiwwhB1MNI7O58zCK0uVItuMHNDR5pMoF2nqlCGzUwZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            sortable('#citiesList', {
                forcePlaceholderSize: true,
                placeholderClass: 'ph-class',
                hoverClass: 'bg-maroon yellow',
            });
        </script>

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
}

?>