<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['product'])) {
        if (!empty(trim($_POST['product'])) || $_POST['product'] != 0) {

            $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `stock` ON `product`.`id`= `stock`.`product_id` WHERE `product`.`id` = ? AND `status_id`='1' ", "s", [$_POST['product']]);

            $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['product']]);

            if ($product_rs->num_rows == 1) {
                $product = $product_rs->fetch_assoc();
                $price = $price_rs->fetch_assoc();
?>
                <div class="col-12">
                    <div>
                        <img class="rounded rounded-5 border border-3 update-stock-product-img" src="<?= $product['cover_image'] ?>" alt="Profile Image">
                    </div>
                    <div class="my-auto px-xl-5 flex-fill">
                        <h4><?= $product['title'] ?></h4>
                        <span>Retail Price: Rs.<?= number_format($price['retail_price']) ?>/=</span>
                        <br>
                        <span>Primary Stock Qty: <?= $product['qty'] ?></span>
                        <br>
                        <span>Ongoing Stock Qty: <?= $product['ongoing_qty'] ?></span>
                    </div>
                </div>
<?php
            }
        }
    }
} else {
    echo "reload";
}
