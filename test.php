<?php


require "config/connection.php";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">


    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/favi/favi.png" type="image/x-icon">

    <!-- font css -->
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/material.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css" />

</head>

<body>
    <div class="container-fluid d-flex justify-content-center">
        <div class="row align-content-center">
            <div class="col-12">
                <div class="row">

                    <?php

                    $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `stock` ON `stock`.`product_id` = `product`.`id` WHERE `status_id`= '1' ");

                    while ($product = $product_rs->fetch_assoc()) {
                    ?>

                        <div class="my-1" style="width: 14%;">
                            <div class="px-2 card-border rounded rounded-3">
                                <!-- <img src="<?= $product['cover_image'] ?>" class="rounded rounded-3" style="height: 30px;"> -->
                                <p class="my-auto card-count"><?= $product['model_no'] ?></p>

                                <p class="my-auto card-count">Primary <?= $product['qty'] ?></p>

                                <p class="my-auto card-count">Ongoing <?= $product['ongoing_qty'] ?></p>

                                <p class="my-auto card-count">Deliver Pending <?= $product['deliver_pending_qty'] ?></p>

                                <?php $summery = $product['qty'] + $product['ongoing_qty'] - $product['deliver_pending_qty'] ?>
                                <p class="my-auto card-count <?= $summery <= 0 ? "text-danger fw-bold" : '' ?>">
                                    Stock Summary <?= $summery ?>
                                </p>

                            </div>
                        </div>

                    <?php
                    }


                    ?>

                </div>
            </div>
        </div>
    </div>

    <?php require 'include/footer.php'; ?>

</body>

</html>