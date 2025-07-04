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

        <!-- Chosen css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"
            integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"
            integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            
        <style>
            .pc-container {
                background-image: url('assets/images/back-ground/blue-half.png');
                background-repeat: no-repeat;
                background-position: top;
                background-size: contain; 
                min-width:fit-content;
            }

            .chosen-single {
                /* display: flex !important; */
                background-image: none !important;
                background-color: white !important;
                font-size: 15px !important;
                height: 38px !important;
                padding-top: 5px !important;
                border-color: #c9c9c9 !important;
            }

            .chosen-single div,
            .chosen-single span {
                margin: auto 10px !important;
            }

            .chosen-single div b {
                /* color: var(--button-primary-color) !important; */
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

                        <h1 class="mb-5 text-white">Add Order</h1>

                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-11 col-xxl-10 mx-auto p-0 p-sm-2 p-lg-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border py-5 p-2 p-lg-5 rounded rounded-5">

                                    <div class="row mb-5 text-center" id="shopPreview">

                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-2 my-auto">
                                            <span class="form-text-1">Shop</span>
                                        </div>
                                        <div class="col-12 col-lg-10">
                                            <select id="shop" onchange="updateSendSMSShopViewer();" class="w-100 ms-md-3 px-4 rounded rounded-5 select-chosen">
                                                <option value="0">Select Shop</option>
                                                <?php
                                                $shop_rs = Database::search("SELECT * FROM `shop` WHERE `id` != '0' AND `status_id`='1' ORDER BY `name` ASC");

                                                while ($shop = $shop_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $shop['id'] ?>"><?= $shop['name'] ?></option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-2 my-auto">
                                            <span class="form-text-1">Price Type</span>
                                        </div>
                                        <div class="col-12 col-lg-10">
                                            <select id="priceType" onchange="showInvoiceContent();" class="form-control">
                                                <option value="0">Select Price type</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Credit</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12" id="invoiceContent">

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

        <script>
            $(".select-chosen").chosen();
        </script>
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