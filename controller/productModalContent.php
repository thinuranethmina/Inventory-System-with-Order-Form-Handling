<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['id'])) {

        $product_rs = Database::search("SELECT *,`product`.`id` AS `id` FROM `product` INNER JOIN `stock` ON `stock`.`product_id`=`product`.`id` WHERE `product`.`id`=? ", "s", [$_POST['id']]);

        if ($product_rs->num_rows == 1) {
            $product = $product_rs->fetch_assoc();
            $price = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$product['id']])->fetch_assoc();

            $images_rs = Database::search("SELECT * FROM `product_image` WHERE `product_id` = ? ", "s", [$product['id']]);

?>
            <div id="content-modal-1" class="col-11 col-md-10 col-lg-6 mx-auto modal--content show-modal">
                <div class="row">
                    <div class="modal--header">
                        <div class="d-flex ">
                            <h2 class="my-auto ">Product</h2>
                            <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                        </div>
                        <hr class="text-dark my-2">
                    </div>
                    <div class="modal--body p-3">
                        <div class="row my-3">
                            <div class="col-12 text-center">
                                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                    <!-- Carousel indicators -->
                                    <ol class="carousel-indicators">
                                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                        <?php

                                        for ($i = 1; $i <= $images_rs->num_rows; $i++) {
                                        ?>
                                            <li data-target="#myCarousel" data-slide-to="<?= $i ?>"></li>
                                        <?php
                                        }
                                        ?>
                                    </ol>
                                    <!-- Wrapper for carousel items -->
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <a data-fancybox="gallery" href="<?= $product['cover_image'] ?>">
                                                <img style="height: 200px;" src="<?= $product['cover_image'] ?>" alt="">
                                            </a>
                                        </div>
                                        <?php

                                        while ($image = $images_rs->fetch_assoc()) {
                                        ?>
                                            <div class="carousel-item">
                                                <a data-fancybox="gallery" href="<?= $image['path'] ?>">
                                                    <img style="height: 200px;" src="<?= $image['path'] ?>" alt="">
                                                </a>
                                            </div>
                                        <?php
                                        }

                                        ?>
                                    </div>
                                    <!-- Carousel controls -->
                                    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                                        <i class="fa fa-angle-left bg-transparent"></i>
                                    </a>
                                    <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                                        <i class="fa fa-angle-right bg-transparent"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Model No:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $product['model_no'] ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Credit Price:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span>Rs.<?= $price['credit_price'] ?>/=</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Cash Price:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span>Rs.<?= $price['cash_price'] ?>/=</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Retail Price:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span>Rs.<?= $price['retail_price'] ?>/=</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Status:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $product['status_id'] == 1 ? "Active" : "Deactive" ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3">
                                <h6 class="form-text-1">Description:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $product['description'] ?></span>
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
        } else {

        ?>

            <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">
                <div class="row">
                    <div class="modal--header">
                        <div class="d-flex ">
                            <h2 class="my-auto ">Activity</h2>
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
                        <h2 class="my-auto ">Product</h2>
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
