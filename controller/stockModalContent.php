<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['id'])) {

        $stock_rs = Database::search("SELECT *,`user`.`name` AS `user`,`operation_type`.`name` AS `operation`,`stock_history`.`date_time` FROM `stock_history` INNER JOIN `product` ON `product`.`id`=`stock_history`.`product_id`  INNER JOIN `operation_type` ON `operation_type`.`id`= `stock_history`.`operation_type_id`  INNER JOIN `user` ON `user`.`id`= `stock_history`.`user_id`  WHERE `stock_history`.`id` = ? ", "s", [$_POST['id']]);

        if ($stock_rs->num_rows == 1) {
            $stock = $stock_rs->fetch_assoc();

            ?>

            <div id="content-modal-1" class="col-11 col-md-10 col-lg-6 mx-auto modal--content show-modal">
                <div class="row">
                    <div class="modal--header">
                        <div class="d-flex ">
                            <h2 class="my-auto ">Stock Changes</h2>
                            <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                        </div>
                        <hr class="text-dark my-2">
                    </div>
                    <div class="modal--body p-3">

                        <div class="row my-3">
                            <div class="col-12 my-auto text-center">
                                <div>
                                    <img class="rounded rounded-5 border border-3 update-stock-product-img"
                                        src="<?= $stock['cover_image'] ?>" alt="Product Image">
                                </div>
                                <div class="my-auto px-xl-5 flex-fill">
                                    <h4><?= $stock['title'] ?></h4>
                                    <span>Model No: <?= $stock['model_no'] ?></span>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">User:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $stock['user'] ?></span>
                            </div>
                        </div>
                        <hr>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Previous qty:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $stock['old_qty'] ?></span>
                            </div>
                        </div>
                        <hr>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Stock Type:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span>
                                    <?php
                                    if ($stock['stock_type_id'] == '1') {
                                        echo "Primary";
                                    } else if ($stock['stock_type_id'] == '2') {
                                        echo "Ongoing";
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <hr>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Operation:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $stock['operation'] ?></span>
                                <?php
                                if ($stock['operation_type_id'] == '1') {
                                    ?>
                                    <img class="mr-1" style="width: 30px;" src="assets/images/icons/up-green.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '2') {
                                    ?>
                                        <img class="mr-1" style="width: 30px;" src="assets/images/icons/down-red.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '3') {
                                    ?>
                                            <img class="mr-1" style="width: 30px;" src="assets/images/icons/down-orange.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '4') {
                                    ?>
                                                <img class="mx-1" style="width: 30px;" src="assets/images/icons/moved.png">
                                        <?php
                                        if ($stock['stock_type_id'] == '1') {
                                            ?>
                                                    <img class="mr-1" style="width: 30px;" src="assets/images/icons/up-green.png">
                                        <?php
                                        } else if ($stock['stock_type_id'] == '2') {
                                            ?>
                                                        <img class="mr-1" style="width: 30px;" src="assets/images/icons/down-orange.png">
                                        <?php
                                        }
                                } else if ($stock['operation_type_id'] == '5') {
                                    ?>
                                                    <img class="mx-1" style="width: 30px;" src="assets/images/icons/moved.png">
                                        <?php
                                        if ($stock['stock_type_id'] == '1') {
                                            ?>
                                                        <img class="mr-1" style="width: 30px;" src="assets/images/icons/down-orange.png">
                                        <?php
                                        } else if ($stock['stock_type_id'] == '2') {
                                            ?>
                                                            <img class="mr-1" style="width: 30px;" src="assets/images/icons/up-green.png">
                                        <?php
                                        }
                                }
                                ?>

                            </div>
                        </div>
                        <hr>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Changed qty:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $stock['changed_qty'] ?></span>
                            </div>
                        </div>
                        <hr>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Total qty:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $stock['total_qty'] ?></span>
                            </div>
                        </div>
                        <hr>

                        <div class="row my-3">
                            <div class="col-12">
                                <span><?= $stock['note'] ?></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal--footer d-sm-flex text-secondary">
                        <span class="text-start ms-0 my-auto"> Date & Time:<span
                                class="fst-italic text-secondary"><?= date('Y-m-d h:i:s A', strtotime($stock['date_time'])) ?></span>
                        </span>
                        <div class="ml-auto float-right p-2">
                            <button class="btn close-btn" onclick="closeModal1();">close</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php
        } else {

            ?>

            <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">
                <div class="row">
                    <div class="modal--header">
                        <div class="d-flex ">
                            <h2 class="my-auto ">Stock Changes</h2>
                            <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                        </div>
                        <hr class="text-dark my-2">
                    </div>
                    <div class="modal--body">
                        <div class="row p-3 text-center">
                            <div class="col-12">
                                <span class="my-auto">Unexpected Error.</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal--footer d-sm-flex text-secondary">
                        <div class="ml-auto float-right p-2">
                            <button class="btn  close-btn" onclick="closeModal1();">close</button>
                        </div>

                    </div>
                </div>
            </div>
            <?php
        }
    } else {

        ?>

        <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">
            <div class="row">
                <div class="modal--header">
                    <div class="d-flex ">
                        <h2 class="my-auto ">Stock Changes</h2>
                        <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                    </div>
                    <hr class="text-dark my-2">
                </div>
                <div class="modal--body">
                    <div class="row p-3 text-center">
                        <div class="col-12">
                            <span class="my-auto">Unexpected Error.</span>
                        </div>
                    </div>
                </div>
                <div class="modal--footer d-sm-flex text-secondary">
                    <div class="ml-auto float-right p-2">
                        <button class="btn close-btn" onclick="closeModal1();">close</button>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "reload";
}
