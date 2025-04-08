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
                <div class="row px-2 px-md-4 ">
                    <div class="col-12">

                        <h1 class="mb-5 text-white">Add New Product</h1>

                        <div class="row">
                            <div class="col-12 col-md-11 col-lg-12 col-xl-10 col-xxl-10 mx-auto p-2 p-md-3 rounded rounded-5 bg-white box-shadow mb-5">
                                <div class="card-border p-3 p-md-5 rounded rounded-5">

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Title</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="title" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Category</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <select id="category" onchange="loadSubCategories(this);" class="form-control">
                                                <option value="0">Select Category</option>
                                                <?php
                                                $category_rs = Database::search("SELECT * FROM `category` ORDER BY `name` ASC");

                                                while ($category = $category_rs->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                                <?php
                                                }

                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row d-none" id="subCategoryContent">
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Model No</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="model" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Minimum qty for warning alert</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="mqty" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Retail Price</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" id="rprice" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Credit Price</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" id="creditPrice" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Cash Price</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" id="cashPrice" class="form-control" placeholder="Enter Here . . .">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Qty</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <input type="text" id="qty" onkeydown="keyBlocker(event,'price');" class="form-control" placeholder="Enter Here . . .">
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

                                    <div class="row my-3 pt-5 pt-lg-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Cover Image</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <img class="d-none" id="remove0" width="25" height="25" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />

                                            <label for="imgchooser0" style="cursor: pointer;">
                                                <img class="mx-1 p-2" id="image0" src="assets/images/icons/add_product.png" style="min-width: 100px; max-width: 180px; height: auto; border-radius: 5px;" onmouseover="this.style.backgroundColor='#FFE9D7';" onmouseout="this.style.backgroundColor='white';" />
                                            </label>
                                            <input type="file" accept="image/png, image/jpeg" onclick="addImage('0');" class="form-control post-ad-form d-none" id="imgchooser0" name="ad_cover_image">
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col-12 col-lg-3 my-auto">
                                            <span class="form-text-1">Other Images</span>
                                        </div>
                                        <div class="col-12 col-lg-9">
                                            <label for="imgchooser1" class="me-1 mb-1" style="cursor: pointer;">
                                                <img onclick="removeImage('1');" id="remove1" style="position: absolute; z-index: 1; cursor: pointer; display:none " width="30" height="30" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />
                                                <img id="image1" class="mx-1 p-2" src="assets/images/icons/add_product.png" onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.21)';" onmouseout="this.style.backgroundColor='white';" style="min-width: 70px; max-width: 150px; height: auto; border-radius: 5px;" />
                                            </label>
                                            <input type="file" onclick="addImage('1');" accept="image/png, image/jpeg" class="form-control post-ad-form d-none" id="imgchooser1" name="ad_cover_image">

                                            <label for="imgchooser2" class="mb-1" style="cursor: pointer;">
                                                <img onclick="removeImage('2');" id="remove2" style="position: absolute; z-index: 1; cursor: pointer; display:none " width="30" height="30" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />
                                                <img id="image2" class="mx-1 p-2" src="assets/images/icons/add_product.png" onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.21)';" onmouseout="this.style.backgroundColor='white';" style="min-width: 70px; max-width: 150px; height: auto; border-radius: 5px;" />
                                            </label>
                                            <input type="file" onclick="addImage('2');" accept="image/png, image/jpeg" class="form-control post-ad-form d-none" id="imgchooser2" name="ad_cover_image">
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