<?php


require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {

    if (isset($_GET['pid'])) {
        $product_rs = Database::search("SELECT * FROM `product` WHERE `id` = ? ", "s", [$_GET['pid']]);

        if ($product_rs->num_rows == 1) {

            $product = $product_rs->fetch_assoc();

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
                                                    <span class="form-text-1">Title</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <input type="text" value="<?= $product['title'] ?>" id="title" class="form-control" placeholder="Enter Here . . .">
                                                </div>
                                            </div>

                                            <div class="row my-3">
                                                <div class="col-12 col-lg-3 my-auto">
                                                    <span class="form-text-1">Category</span>
                                                </div>
                                                <div class="col-12 col-lg-9">
                                                    <select onchange="loadSubCategories(this);" id="category" class="form-control">
                                                        <option value="0">Select Category</option>
                                                        <?php
                                                        $category_rs = Database::search("SELECT * FROM `category` ORDER BY `name` ASC");

                                                        while ($category = $category_rs->fetch_assoc()) {
                                                        ?>
                                                            <option value="<?= $category['id'] ?>" <?php if ($category['id'] == $product['category_id']) {
                                                                                                        echo "selected";
                                                                                                    } ?>><?= $category['name'] ?></option>
                                                        <?php
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php
                                            if ($product['sub_category_id'] != 0) {
                                                $sub_category_rs = Database::search("SELECT * FROM `sub_category` WHERE `category_id` = ? ORDER BY `name` ASC ", "s", [$product['category_id']]);

                                            ?>
                                                <div class="row" id="subCategoryContent">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-12 col-lg-3 my-auto">
                                                                <span class="form-text-1">Sub Category</span>
                                                            </div>
                                                            <div class="col-12 col-lg-9">
                                                                <select id="subCategory" class="form-control">

                                                                    <option value="0">Select Sub Category</option>
                                                                    <?php
                                                                    while ($sub_category = $sub_category_rs->fetch_assoc()) {
                                                                    ?>
                                                                        <option value="<?= $sub_category['id'] ?>" <?php if ($sub_category['id'] == $product['sub_category_id']) {
                                                                                                                        echo "selected";
                                                                                                                    } ?>><?= $sub_category['name'] ?></option>
                                                                    <?php
                                                                    }

                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                            } else {
                                                ?>
                                                    <div class="row d-none" id="subCategoryContent">
                                                    <?php
                                                }
                                                    ?>
                                                    </div>

                                                    <div class="row my-3">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Model No</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9">
                                                            <input type="text" value="<?= $product['model_no'] ?>" id="model" class="form-control" placeholder="Enter Here . . .">
                                                        </div>
                                                    </div>

                                                    <div class="row my-3">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Minimum qty for warning alert</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9">
                                                            <input type="text" value="<?= $product['warning_no'] ?>" id="mqty" class="form-control" placeholder="Enter Here . . .">
                                                        </div>
                                                    </div>

                                                    <div class="row my-3 mb-5 pb-5">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Description</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9 mb-5 mb-sm-0">
                                                            <div id="editor">
                                                                <?= $product['description'] ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row my-3 pt-5 pt-lg-3">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Cover Image</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9">
                                                            <img class="d-none" id="remove0" width="30" height="30" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />

                                                            <input type="hidden" id="removeStauts0" value="0" />
                                                            <label for="imgchooser0" style="cursor: pointer;">
                                                                <img class="p-2" id="image0" src="<?= $product['cover_image'] ?>" style="min-width: 100px; max-width: 180px; height: auto; border-radius: 5px;" onmouseover="this.style.backgroundColor='#FFE9D7';" onmouseout="this.style.backgroundColor='white';" />
                                                            </label>
                                                            <input type="file" accept="image/png, image/jpeg" onclick="addImage('0');" class="form-control post-ad-form d-none" id="imgchooser0" name="ad_cover_image">

                                                        </div>
                                                    </div>

                                                    <div class="row my-3">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Other Images</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9">

                                                            <?php

                                                            $image_rs = Database::search("SELECT * FROM `product_image` WHERE `product_id` = ? ", "s", [$product['id']]);
                                                            $x = 0;
                                                            while ($image = $image_rs->fetch_assoc()) {
                                                                $x++;
                                                            ?>

                                                                <label for="imgchooser<?= $x ?>" class="me-1 mb-1" style="cursor: pointer;">
                                                                    <input type="hidden" id="removeStauts<?= $x ?>" value="0" />
                                                                    <img onclick="removeImage(<?= $x ?>);" id="remove<?= $x ?>" style="position: absolute; z-index: 1; cursor: pointer;" width="30" height="30" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />
                                                                    <img id="image<?= $x ?>" class="mx-1 p-2" src="<?= $image['path'] ?>" onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.21)';" onmouseout="this.style.backgroundColor='white';" style="min-width: 70px; max-width: 180px; height: auto; border-radius: 5px;" />
                                                                </label>
                                                                <input type="file" onclick="addImage(<?= $x ?>);" accept="image/png, image/jpeg" class="form-control post-ad-form d-none" id="imgchooser<?= $x ?>">

                                                            <?php
                                                            }

                                                            while ($x <= 1) {
                                                                $x++;
                                                            ?>

                                                                <label for="imgchooser<?= $x ?>" class="me-1 mb-1" style="cursor: pointer;">
                                                                    <input type="hidden" id="removeStauts<?= $x ?>" value="0" />
                                                                    <img onclick="removeImage(<?= $x ?>);" id="remove<?= $x ?>" style="position: absolute; z-index: 1; cursor: pointer; display: none;" width="30" height="30" src="https://img.icons8.com/color/48/cancel--v1.png" alt="X" />
                                                                    <img id="image<?= $x ?>" class="mx-1 p-2" src="assets/images/icons/add_product.png" onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.21)';" onmouseout="this.style.backgroundColor='white';" style="min-width: 70px; max-width: 180px; height: auto; border-radius: 5px;" />
                                                                </label>
                                                                <input type="file" onclick="addImage(<?= $x ?>);" accept="image/png, image/jpeg" class="form-control post-ad-form d-none" id="imgchooser<?= $x ?>">

                                                            <?php
                                                            }

                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-12">
                                                            <button class="btn submit-btn w-100" onclick="updateProduct('<?= $product['id'] ?>');">Update Now</button>
                                                        </div>
                                                    </div>

                                                    <hr class="my-5">

                                                    <?php
                                                    $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$product['id']]);

                                                    $price = $price_rs->fetch_assoc();

                                                    ?>

                                                    <div class="row my-3">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Retail Price (Rs.)</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9">
                                                            <input type="text" id="rprice" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" class="form-control" value="<?= number_format($price['retail_price'], 2) ?>" placeholder="Enter Here . . .">
                                                        </div>
                                                    </div>

                                                    <div class="row my-3">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Credit Price (Rs.)</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9">
                                                            <input type="text" id="creditPrice" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" class="form-control" value="<?= number_format($price['credit_price'], 2) ?>" placeholder="Enter Here . . .">
                                                        </div>
                                                    </div>

                                                    <div class="row my-3">
                                                        <div class="col-12 col-lg-3 my-auto">
                                                            <span class="form-text-1">Cash Price (Rs.)</span>
                                                        </div>
                                                        <div class="col-12 col-lg-9">
                                                            <input type="text" id="cashPrice" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" class="form-control" value="<?= number_format($price['cash_price'], 2) ?>" placeholder="Enter Here . . .">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn submit-btn w-100" onclick="updateProductPrice('<?= $product['id'] ?>');">Update Price</button>
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

        } else {
            header("Location: ./");
        }
    } else {
        header("Location: ./");
    }
}

?>