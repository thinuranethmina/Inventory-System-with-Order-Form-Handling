<?php

require "util/userStatus.php";

if (User::is_allow()) {


    if ($_SESSION['user']['user_type'] > 2) {
        $all_shop_rs = Database::search("SELECT * FROM `shop` WHERE  `shop`.`status_id` = '1' AND `shop`.`id`!='0' ");
    } else {
        $all_shop_rs = Database::search("SELECT * FROM `shop` WHERE `shop`.`id`!='0' ");
    }


    $sql = "SELECT *,`shop`.`id` AS `id`,`shop`.`name` AS `shop`, `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province` FROM `shop`  INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id` WHERE `shop`.`id`!='0' ";

    $isProvinceOkay = false;
    $isDistrictOkay = false;

    if (isset($_POST['city'])) {
        if ($_POST['city'] != 0 && $_POST['city'] != '' && $_POST['city'] != null) {
            $sql .= " AND `city`.`id` = '" . $_POST['city'] . "' ";
        } else {
            $isDistrictOkay = true;
        }
    } else {
        $isDistrictOkay = true;
    }


    if ($isDistrictOkay) {
        if (isset($_POST['district'])) {
            if ($_POST['district'] != 0 && $_POST['district'] != '' && $_POST['district'] != null) {
                $sql .= " AND `district`.`id` = '" . $_POST['district'] . "' ";
            } else {
                $isProvinceOkay = true;
            }
        } else {
            $isProvinceOkay = true;
        }
    }


    if ($isProvinceOkay) {
        if (isset($_POST['province'])) {
            if ($_POST['province'] != 0 && $_POST['province'] != '' && $_POST['province'] != null) {
                $sql .= " AND `province`.`id` = '" . $_POST['province'] . "' ";
            }
        }
    }


    if ($_SESSION['user']['user_type'] > 2) {
        $sql .= " AND `shop`.`status_id` = '1' ";
    }

    if (isset($_POST['text'])) {
        if (trim($_POST['text']) != '' && $_POST['text'] != null) {
            $sql .= " AND (`shop`.`address` LIKE '%" . trim($_POST['text']) . "%' OR `shop`.`name` LIKE '%" . trim($_POST['text']) . "%') ";
        }
    }

    $shop_rs = Database::search($sql . " ORDER BY  `shop`.`date_time` DESC ");

?>

    <div class="row">
        <div class="col-12 d-none d-md-block">
            <span class="text-white f-w-300">Showing <?= $shop_rs->num_rows ?> of <?= $all_shop_rs->num_rows ?> entries</span>
        </div>
    </div>

    <table class="" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th class="d-none d-md-table-cell">Image</th>
                <th>Shop</th>
                <th class="d-none d-md-table-cell text-center">Last Invoice Date</th>
                <th class="d-none d-md-table-cell">Address</th>
                <?php
                if ($_SESSION['user']['user_type'] <= 2) {
                ?>
                    <th class="d-none d-md-table-cell text-center">Status</th>
                <?php
                }
                ?>
                <th class=" text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if ($shop_rs->num_rows <= 0) {
            ?>
                <tr>
                    <td class="text-center rounded-3" colspan="7">
                        <h4 class="m-auto">No Result</h4>
                    </td>
                </tr>
            <?php
            }

            $x = 1;
            while ($shop = $shop_rs->fetch_assoc()) {
                $order_rs = Database::search("SELECT * FROM `invoice` WHERE `shop_id` = ? ORDER BY `date_time` DESC ", "s", [$shop['id']]);

            ?>

                <tr>
                    <td>
                        <?= $x ?>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <img src="<?= $shop['image'] ?>" class="table-main-image">
                    </td>
                    <td>
                        <span class="text-truncate-1"><?= $shop['shop'] ?></span>
                    </td>
                    <td class="d-none d-md-table-cell text-center">
                        <?php
                        if ($order_rs->num_rows > 0) {
                            $invoice = $order_rs->fetch_assoc();
                        ?>
                            <span><?= date('Y-m-d', strtotime($invoice['date_time'])) . " (" . $invoice['order_id'] . ")" ?></span>
                        <?php
                        } else {
                        ?>
                            <span>No Order yet</span>
                        <?php
                        }
                        ?>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="text-truncate-1"><?= $shop['address'] . ", " . $shop['city'] . ", " . $shop['district'] . " district, " . $shop['province'] . " province." ?></span>
                    </td>
                    <?php
                    if ($_SESSION['user']['user_type'] <= 2) {
                    ?>
                        <td class="d-none d-md-table-cell text-center" id="status<?= $shop['id'] ?>">
                            <?= $shop['status_id'] == 1 ? '<div class="status status-active mx-auto">Active</div>' : '<div class="status status-deactive mx-auto">Deactive</div>' ?>
                        </td>
                    <?php
                    }
                    ?>
                    <td class=" text-center" style="min-width: 106px;">
                        <?php
                        if ($_SESSION['user']['user_type'] <= 2) {
                        ?>
                            <div class="check-box2 p-2 d-inline-block mr-1 z-0">
                                <input class="z-0" id="toggleStatus" onchange="changeStatus('Shop',<?= $shop['id'] ?>);" type="checkbox" <?= $shop['status_id'] == 1 ? "checked" : "" ?>>
                            </div>
                            <img class="mr-1 action-icon" onclick="viewModal('<?= $shop['id'] ?>','shop');" src="assets/images/icons/view.png">
                            <a href="update-shop.php?id=<?= $shop['id'] ?>" target="_blank"><img class="action-icon" src="assets/images/icons/edit.png"></a>
                        <?php
                        } else {
                        ?>
                            <img class="mr-1 action-icon" onclick="viewModal('<?= $shop['id'] ?>','shop');" src="assets/images/icons/view.png">
                        <?php
                        }
                        ?>
                    </td>
                </tr>

            <?php

                $x++;
            }


            ?>
        </tbody>
    </table>

<?php

} else {
    echo "reload";
}
