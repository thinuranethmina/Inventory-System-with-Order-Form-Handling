<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['product']) && isset($_POST['stockType'])) {
        if (!empty(trim($_POST['product'])) && $_POST['product'] != 0 && !empty(trim($_POST['stockType'])) && $_POST['stockType'] != 0) {

            $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `stock` ON `product`.`id`= `stock`.`product_id` WHERE `product`.`id` = ? AND `status_id`='1' ", "s", [$_POST['product']]);

            if ($product_rs->num_rows == 1) {
                $product = $product_rs->fetch_assoc();
?>
                <div class="col-12 d-lg-flex">
                    <div>
                        <img class="rounded rounded-5 border border-3 update-stock-product-img" src="<?= $product['cover_image'] ?>" alt="Profile Image">
                    </div>
                    <div class="my-auto px-xl-5 flex-fill">
                        <h4><?= $product['title'] ?></h4>
                        <span>Available Qty: <?= $_POST['stockType'] == '1' ? $product['qty'] : $product['ongoing_qty'] ?></span>
                    </div>
                </div>
<?php
            }
        }
    }
} else {
    echo "reload";
}
