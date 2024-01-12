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
            <div class="row px-4">
                <div class="col-12 mb-3 px-2">
                    <h1 class="text-white">Products Stock</h1>
                </div>

                <div class="col-12">
                    <div class="row">

                        <div class="col-12 col-md-4 offset-md-8 col-xl-3 offset-xl-9 ">
                            <div class="p-2 bg-white rounded rounded-2 d-flex" style="min-width: max-content;">
                                <input type="text" placeholder="Search Here..." style="outline: none; border: none; width: calc(100% - 10px);">
                                <img src="assets/images/icons/search.png" class="my-auto pl-1" />
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-12 px-2 d-none d-md-block">
                    <span class="text-white f-w-300">Showing 37 of 247 entries</span>
                </div>

                <div class="col-12 px-2">
                    <table class="" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>dhf</td>
                                <td>dhf</td>
                                <td>dhf</td>
                                <td>dhf</td>
                            </tr>
                            <tr>
                                <td>dhf</td>
                                <td>dhf</td>
                                <td>dhf</td>
                                <td>dhf</td>
                            </tr>
                            <tr>
                                <td>dhf</td>
                                <td>dhf</td>
                                <td>dhf</td>
                                <td>dhf</td>
                            </tr>
                        </tbody>
                </div>

            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->


    <?php require_once('include/footer.php'); ?>
</body>

</html>