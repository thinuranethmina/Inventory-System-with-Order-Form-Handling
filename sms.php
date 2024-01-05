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



            /* Style the tab */
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
                background-color: #f1f1f1;
            }

            /* Style the buttons inside the tab */
            .tab button {
                background-color: inherit;
                float: left;
                border: none;
                /* border-left: 1px solid black; */
                outline: none;
                cursor: pointer;
                padding: 14px 16px;
                transition: 0.3s;
                font-size: 17px;
            }

            /* Change background color of buttons on hover */
            .tab button:hover {
                background-color: #ddd;
            }

            /* Create an active/current tablink class */
            .tab button.active {
                background-color: #ccc;
            }

            /* Style the tab content */
            .tabcontent {
                display: none;
                padding: 6px 12px;
                border: none;
                border-top: none;
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
                <div class="row px-0 px-md-4">
                    <div class="col-12">

                        <h1 class="mb-5 text-white">Send SMS</h1>

                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-11 col-xxl-10 mx-auto p-2 p-md-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border py-5 p-3 p-lg-5 rounded rounded-3">

                                    <div class="row">
                                        <div class="col-12 col-md-10 col-lg-6 mx-auto">
                                            <div class="tab rounded rounded-5">
                                                <div class="row">
                                                    <div class="col-6 p-0">
                                                        <button class="tablinks w-100" onclick="openCity(event, 'shopTab')">Shop</button>
                                                    </div>
                                                    <div class="col-6 p-0">
                                                        <button class="tablinks w-100" onclick="openCity(event, 'phoneTab')">Mobile</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Shop SMS Content Start -->
                                    <div id="shopTab" class="tabcontent">
                                    </div>
                                    <!-- Shop SMS Content End -->



                                    <!-- Shop SMS Content Start -->
                                    <div id="phoneTab" class="tabcontent">
                                    </div>
                                    <!-- Shop SMS Content End -->

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
            function openCity(evt, name) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {

                    if (tabcontent[i].id == name) {
                        let form = new FormData();
                        form.append("id", i);

                        fetch("controller/smsContent.php", {
                                method: "POST",
                                body: form,
                            }).then(response => response.text())
                            .then(text => {
                                if (text == 'reload') {
                                    window.location.reload();
                                } else {
                                    document.getElementById(name).innerHTML = text
                                    document.getElementById(name).style.display = "block";
                                }
                            }).catch(error => {
                                console.error('Fetch error:', error);
                            })
                    } else {
                        tabcontent[i].innerHTML = "";
                        tabcontent[i].style.display = "none";
                    }
                }

                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(name).style.display = "block";
                evt.currentTarget.className += " active";
            }
        </script>


    </body>

    </html>

<?php
}

?>