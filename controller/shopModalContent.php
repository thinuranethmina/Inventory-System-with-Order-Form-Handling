<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['id'])) {

        $shop_rs = Database::search("SELECT *,`shop`.`id` AS `id`,`shop`.`name` AS `shop`, `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province` FROM `shop` INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id` WHERE `shop`.`id`=? ", "s", [$_POST['id']]);

        if ($shop_rs->num_rows == 1) {
            $shop = $shop_rs->fetch_assoc();
?>
            <div id="content-modal-1" class="col-11 col-md-10 col-lg-6 mx-auto modal--content show-modal">
                <div class="row">
                    <div class="modal--header">
                        <div class="d-flex ">
                            <h2 class="my-auto ">Shop</h2>
                            <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                        </div>
                        <hr class="text-dark my-2">
                    </div>
                    <div class="modal--body">
                        <div class="row p-3">

                            <div class="row my-3">
                                <div class="col-12 text-center">
                                    <img class="rounded rounded-5 send-msg-shop-img" src="<?= $shop['image'] ?>" alt="Shop Image">
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-12 col-lg-3 my-auto">
                                    <h6 class="form-text-1">Name:</h6>
                                </div>
                                <div class="col-12 col-lg-9">
                                    <span><?= $shop['shop'] ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row my-3">
                                <div class="col-12 col-lg-3 my-auto">
                                    <h6 class="form-text-1">Mobile:</h6>
                                </div>
                                <div class="col-12 col-lg-9">
                                    <span><?= $shop['mobile'] ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row my-3">
                                <div class="col-12 col-lg-3 my-auto">
                                    <h6 class="form-text-1">Address:</h6>
                                </div>
                                <div class="col-12 col-lg-9">
                                    <span><?= $shop['address'] . ", " . $shop['city'] . ", " . $shop['district'] . " district, " . $shop['province'] . " province." ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row my-3">
                                <div class="col-12 col-lg-3 my-auto">
                                    <h6 class="form-text-1">Status:</h6>
                                </div>
                                <div class="col-12 col-lg-9">
                                    <span><?= $shop['status_id'] == 1 ? "Active" : "Deactive" ?></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal--footer d-sm-flex text-secondary">
                        <span class="text-start ms-0 my-auto">Date & Time:<span class="fst-italic text-secondary"><?= date('Y-m-d h:i:s A', strtotime($shop['date_time'])) ?></span>
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
                            <h2 class="my-auto ">Shop</h2>
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

        ?>

        <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">
            <div class="row">
                <div class="modal--header">
                    <div class="d-flex ">
                        <h2 class="my-auto ">Shop</h2>
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
