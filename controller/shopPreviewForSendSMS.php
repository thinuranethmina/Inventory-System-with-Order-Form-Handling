<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['shop'])) {
        if (!empty(trim($_POST['shop'])) || $_POST['shop'] != 0) {

            $shop_rs = Database::search("SELECT * FROM `shop` WHERE `shop`.`id` = ? AND `status_id`='1' ", "s", [$_POST['shop']]);

            if ($shop_rs->num_rows == 1) {
                $shop = $shop_rs->fetch_assoc();
?>
                <div class="col-12 mb-3">
                    <img class="rounded rounded-5 send-msg-shop-img" src="<?= $shop['image'] ?>" alt="Shop Image">
                </div>
                <div class="col-12">
                    <div class="my-auto px-xl-5 flex-fill">
                        <h4><?= $shop['name'] ?></h4>

                        <?php

                        $address_rs = Database::search("SELECT *,`city`.`name` AS `city`, `district`.`name` AS `district`, `province`.`name` AS `province` FROM `city` INNER JOIN `district` ON `city`.`district_id`=`district`.`id` INNER JOIN `province` ON `province`.`id`= `district`.`province_id`  WHERE `city`.`id` = ? ", "s", [$shop['city_id']]);
                        $address = $address_rs->fetch_assoc();

                        ?>

                        <span><?= $shop['address'] ?>, <?= $address['city'] ?>, <?= $address['district'] ?> District, <?= $address['province'] ?> Province.</span>
                        <br>
                        <span><?= $shop['mobile'] ?></span>
                    </div>
                </div>
<?php
            }
        }
    }
} else {
    echo "reload";
}
