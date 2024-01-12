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
        <meta name="description"
            content="DashboardKit is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
        <meta name="keywords"
            content="DashboardKit, Dashboard Kit, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Free Bootstrap Admin Template">
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

                <!-- [ Main Content ] start -->
                <div class="row px-2 px-md-4">
                    <div class="col-12">

                        <h1 class="mb-5 text-white">Update Stock</h1>

                        <div class="row">
                            <div
                                class="col-12 col-md-11 col-lg-12 col-xl-11 col-xxl-10 mx-auto p-2 p-md-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border py-5 p-3 p-lg-5 rounded rounded-5">

                                    <div class="row mb-5 text-center" id="productPreview">

                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Stock</span>
                                        </div>
                                        <div class="col-12 col-lg-9">

                                            <select name="" id="stockType" class="form-control"
                                                onchange="loadUpdateStockContent();">
                                                <option value="0">Select Stock</option>
                                                <?php
                                                $stockType_rs = Database::search("SELECT * FROM `stock_type` ");

                                                while ($stockType = $stockType_rs->fetch_assoc()) {
                                                    ?>
                                                    <option class="text-dark" value="<?= $stockType['id'] ?>">
                                                        <?= $stockType['name'] ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12" id="stockOperatonContent">



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


            function totalPreviewInStockUpdate() {

                if (document.getElementById("product").value != 0 && document.getElementById("operation").value != 0) {

                    var operation = document.getElementById("operation").value;

                    let total = 0;
                    let aqty = parseInt(document.getElementById("aqty").innerHTML);
                    let cqty = parseInt(document.getElementById("cqty").value);

                    if (operation == "1") {
                        total = aqty + cqty;
                    } else if (operation == "2" || operation == "4" || operation == "5") {
                        total = aqty - cqty;
                    } else {
                        total = aqty;
                    }

                    if (isNaN(total)) {
                        total = document.getElementById("aqty").innerHTML;
                    }

                    document.getElementById("tqty").value = total;

                } else {
                    document.getElementById("cqty").value = "";
                }
            };
        </script>
    </body>

    </html>

    <?php
}

?>