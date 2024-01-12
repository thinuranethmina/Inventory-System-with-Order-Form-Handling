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

        <!-- Include Chosen CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

        <!-- Carousel -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
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
                <div class="row px-2 px-md-4">
                    <div class="col-12 mb-3 px-2 d-flex justify-content-between">
                        <h1 class="text-white my-auto">SMS Templates</h1>
                        <button class="btn btn-light width-fit-content my-auto" onclick="addSMSTemplateModalOpen();">Add New Template</button>
                    </div>

                    <div class=" col-12">
                        <div class="row">

                            <div class="col-12 mb-2 mb-md-0 text-md-right">
                            </div>

                        </div>
                    </div>

                    <?php
                    $message_template_rs = Database::search("SELECT * FROM `message_template` ORDER BY  `message_template`.`name` ASC ");
                    ?>

                    <div class="col-12">
                        <span class="text-white f-w-300">Showing <?= $message_template_rs->num_rows ?> entries</span>
                    </div>
                    <div class="col-12 col-md-8 col-lg-7 mx-auto px-2" id="resultContent">

                        <table class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th class=" text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $x = 1;
                                $date = null;
                                while ($message_template = $message_template_rs->fetch_assoc()) {
                                ?>

                                    <tr>
                                        <td>
                                            <?= $x ?>
                                        </td>
                                        <td><?= $message_template['name'] ?></td>
                                        <td class=" text-center">
                                            <img class="action-icon" onclick="editSMSTemplateModalOpen(<?= $message_template['id'] ?>);" src="assets/images/icons/edit.png">
                                        </td>
                                    </tr>

                                <?php

                                    $x++;
                                }


                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
        <!-- [ Main Content ] end -->


        <?php require_once('include/footer.php'); ?>
    </body>

    </html>
<?php
}
?>