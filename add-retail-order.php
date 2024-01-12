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
                    <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">

                    </div>
                </div>

                <!-- [ Main Content ] start -->
                <div class="row px-0 px-md-1 px-lg-4 ">
                    <div class="col-12">

                        <h1 class="mb-5 text-white">Add Retail Order</h1>

                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-11 col-xxl-10 mx-auto p-0 p-sm-2 p-lg-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border py-5 p-2 p-lg-5 rounded rounded-5">


                                    <div class="row mt-3 mb-3 mb-lg-5">
                                        <div class="col-12">
                                            <table id="invoiceTable" style="width:100%; font-size: 10px !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width: 40px !important;">#</th>
                                                        <th>Image</th>
                                                        <th>Item(s)</th>
                                                        <th>free qty</th>
                                                        <th>qty</th>
                                                        <th>Rate (Rs.)</th>
                                                        <th>Price (Rs.)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class=" p-0 m-0" style="height: 8px !important;">
                                                        <td class="text-center p-0 m-0" colspan="7">
                                                            <button class="btn btn-secondary w-100" onclick="viewAddProductModalInAddRetailOrder();">Add Product</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-5 mr-0 ml-auto mt-3" id="orderSummeryContent">

                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-2">
                                            <span class="form-text-1">Note</span>
                                        </div>
                                        <div class="col-12 col-lg-10">
                                            <textarea id="note" class="w-100 form-control" rows="5" placeholder="Enter Customer Name & Address"></textarea>
                                        </div>
                                    </div>



                                    <div class="row mt-3">
                                        <div class="col-12 pt-5 pt-lg-0">
                                            <button class="btn submit-btn w-100" onclick="AddRetailOrderForm();">Add to System</button>
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
            document.getElementById('msg').addEventListener('keydown', function(e) {
                if (e.key == 'Tab') {
                    e.preventDefault();
                    var start = this.selectionStart;
                    var end = this.selectionEnd;

                    // set textarea value to: text before caret + tab + text after caret
                    this.value = this.value.substring(0, start) +
                        "\t" + this.value.substring(end);

                    // put caret at right position again
                    this.selectionStart =
                        this.selectionEnd = start + 1;
                }
            });
        </script>

    </body>

    </html>

<?php
}

?>