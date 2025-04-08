<?php

require "util/userStatus.php";

if (User::is_allow()) {

    function getRelativeTime($publishDate)
    {
        $diff = time() - strtotime($publishDate);
        $intervals = array(
            31536000 => 'year',
            2592000 => 'month',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
        );

        foreach ($intervals as $secs => $label) {
            $div = $diff / $secs;

            if (date('Y-m-d', strtotime($publishDate)) != date("Y-m-d")) {
                return date('h:i A', strtotime($publishDate));
            } else {
                if ($div >= 1) {
                    $timeAgo = round($div);
                    return $timeAgo . ' ' . $label . ($timeAgo > 1 ? 's' : '') . ' ago';
                }
            }
        }

        return 'just now';
    }


    $all_order_rs = Database::search("SELECT * FROM `invoice`");


    $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
    FROM `invoice` 
    INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
    INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` 
    INNER JOIN `district` ON `district`.`id` = `city`.`district_id` 
    INNER JOIN `province` ON `province`.`id` = `district`.`province_id` 
    WHERE  `invoice`.`id`!='0' ";

    $sql2 = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
    FROM `invoice` 
    INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
    INNER JOIN `invoice_item` ON `invoice_item`.`invoice_id`=`invoice`.`id` 
    INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` 
    INNER JOIN `district` ON `district`.`id` = `city`.`district_id` 
    INNER JOIN `province` ON `province`.`id` = `district`.`province_id` 
    WHERE  `invoice`.`id`!='0' ";

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

    if ($_SESSION['user']['user_type'] == '4') {
        // $sql .= " AND `invoice`.`user_id` = '" . $_SESSION['user']['id'] . "' ";
    } else {
        if (isset($_POST['user'])) {
            if ($_POST['user'] != '' && $_POST['user'] != null) {
                $sql .= " AND `invoice`.`user_id` = '" . $_POST['user'] . "' ";
            }
        }
    }


    if (isset($_POST['shop'])) {
        if ($_POST['shop'] != '' && $_POST['shop'] != null) {
            $sql .= " AND `shop`.`id` = '" . $_POST['shop'] . "' ";
        }
    }

    $isTextEmpty = false;

    if (isset($_POST['text'])) {
        if (trim($_POST['text']) != '' && $_POST['text'] != null) {
            $sql .= " AND (`shop`.`address` LIKE ? OR `city`.`name` LIKE ?  OR `district`.`name` LIKE ? OR `province`.`name` LIKE ?  OR `shop`.`name` LIKE ?  OR `invoice`.`order_id` LIKE ? ) ";
            $sql2 .= " AND (`shop`.`address` LIKE ? OR `city`.`name` LIKE ?  OR `district`.`name` LIKE ? OR `province`.`name` LIKE ?  OR `shop`.`name` LIKE ?  OR `invoice`.`order_id` LIKE ? ) ";
        } else {
            $isTextEmpty = true;
        }
    } else {
        $isTextEmpty = true;
    }

    if (isset($_POST['product'])) {
        if ($_POST['product'] != 0 && $_POST['product'] != '' && $_POST['product'] != null) {
            $sql2 .= " AND `invoice_item`.`product_id` = '" . $_POST['product'] . "' ";
        }
    }

    if (isset($_POST['payment'])) {
        if ($_POST['payment'] == '1' || $_POST['payment'] == '0') {
            $sql .= " AND `invoice`.`is_completed` = '" . $_POST['payment'] . "' ";
            $sql2 .= " AND `invoice`.`is_completed` = '" . $_POST['payment'] . "' ";
        }
    }

    if (isset($_POST['deliver'])) {
        if ($_POST['deliver'] == '1' || $_POST['deliver'] == '0') {
            $sql .= " AND `invoice`.`is_delivered` = '" . $_POST['deliver'] . "' ";
            $sql2 .= " AND `invoice`.`is_delivered` = '" . $_POST['deliver'] . "' ";
        }
    }
    
    if (isset($_POST['from'])) {
        $from = $_POST['from'] ?? null;
        $sql .= " AND `invoice`.`date_time` >= '$from 00:00:00'";
        $sql2 .= " AND `invoice`.`date_time` >= '$from 00:00:00'";
    }
    
    if (isset($_POST['to'])) {
        $to = $_POST['to'] ?? null;
        $sql .= " AND `invoice`.`date_time` <= '$to 23:59:59'";
        $sql2 .= " AND `invoice`.`date_time` <= '$to 23:59:59'";
    }

    if ($_SESSION['user']['user_type'] == '1') {
        if (isset($_POST['isDelete'])) {
            if ($_POST['isDelete'] == '1' || $_POST['isDelete'] == '0') {
                $sql .= " AND `invoice`.`is_delete` = '" . $_POST['isDelete'] . "' ";
                $sql2 .= " AND `invoice`.`is_delete` = '" . $_POST['isDelete'] . "' ";
            }
        }
    } else {
        $sql .= " AND `invoice`.`is_delete` = '0' ";
        $sql2 .= " AND `invoice`.`is_delete` = '0' ";
    }

    if (isset($_POST['product'])) {
        if ($_POST['product'] != 0 && $_POST['product'] != '' && $_POST['product'] != null) {
            if ($isTextEmpty) {
                $order_rs = Database::search($sql2 . " ORDER BY `invoice`.`date_time` DESC");
            } else {
                $order_rs = Database::search($sql2 . " ORDER BY `invoice`.`date_time` DESC", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
            }
        } else {
            if ($isTextEmpty) {
                $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC");
            } else {
                $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
            }
        }
    } else {
        if ($isTextEmpty) {
            $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC");
        } else {
            $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
        }
    }

?>

    <div class="row">
        <div class="col-12 d-flex justify-content-between">
            <span class="text-white f-w-300 d-none d-md-inline-block mb-0 mt-auto">Showing <?= $order_rs->num_rows ?> of <?= $all_order_rs->num_rows ?> entries</span>
            <?php
            if (isset($_POST['product'])) {
                if ($_POST['product'] != 0 && $_POST['product'] != '' && $_POST['product'] != null) {
            ?>
                    <span class="bg-white rounded rounded-3 mt-2 mr-1 px-3 py-2">
                        Total Qty:
                        <?php

                        if ($isTextEmpty) {
                            $stock_rs = Database::search($sql2);
                        } else {
                            $stock_rs = Database::search($sql2 . " ORDER BY `invoice`.`date_time` DESC", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
                        }

                        $qty = 0;

                        while ($stock = $stock_rs->fetch_assoc()) {
                            $qty += $stock['qty'] + $stock['free_qty'];
                        }

                        echo $qty;
                        ?>
                    </span>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <table class="" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Order Id</th>
                <th>Shop</th>
                <th class="d-none d-lg-table-cell text-center" colspan="2">Status</th>
                <th class="text-center">Time</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if ($order_rs->num_rows <= 0) {
            ?>
                <tr>
                    <td class="text-center rounded-3" colspan="7">
                        <h4 class="m-auto">No Result</h4>
                    </td>
                </tr>
                <?php
            }

            $x = 1;
            $date = null;
            while ($order = $order_rs->fetch_assoc()) {

                if ($date == date('Y-m-d', strtotime($order['date_time']))) {
                ?>

                    <tr <?= $order['is_delete'] == 1 ? 'style="opacity: 0.7;"' : '' ?>>
                        <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $x ?>
                        </td>
                        <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $order['order_id'] ?>
                        </td>
                        <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <span class="text-truncate-1">
                                <?= $order['shop'] ?>
                            </span>
                        </td>
                        <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $order['is_delivered'] == 1 ? '<div class="d-inline-block status status-active m-auto">Delivered</div>' : '<div class="d-inline-block status status-deactive mx-auto">Not Delivered</div>' ?>
                        </td>
                        <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $order['is_completed'] == 1 ? '<div class="d-inline-block status status-active m-auto text-center">Paid</div>' : '<div class="d-inline-block status status-deactive text-center mx-auto">Not Paid</div>' ?>
                        </td>
                        <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>><?= getRelativeTime($order['date_time']) ?></td>
                        <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <a href="view-order.php?id=<?= $order['id'] ?>" target="_blank">
                                <img class="me-2 action-icon" src="assets/images/icons/view.png">
                            </a>
                        </td>
                    </tr>

                <?php

                } else {
                    $date = date('Y-m-d', strtotime($order['date_time']));
                ?>
                    <tr class="shadow-none" style="height: 8px !important;">
                        <td colspan="8" class="text-center date-row"><?= date("Y-m-d") == $date ? "Today" : $date ?>
                        </td>
                    </tr>

                    <tr <?= $order['is_delete'] == 1 ? 'style="opacity: 0.7;"' : '' ?>>
                        <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $x ?>
                        </td>
                        <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $order['order_id'] ?>
                        </td>
                        <td <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <span class="text-truncate-1">
                                <?= $order['shop'] ?>
                            </span>
                        </td>
                        <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $order['is_delivered'] == 1 ? '<div class="d-inline-block status status-active m-auto">Delivered</div>' : '<div class="d-inline-block status status-deactive mx-auto">Not Delivered</div>' ?>
                        </td>
                        <td class="d-none d-lg-table-cell text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <?= $order['is_completed'] == 1 ? '<div class="d-inline-block status status-active m-auto text-center">Paid</div>' : '<div class="d-inline-block status status-deactive text-center mx-auto">Not Paid</div>' ?>
                        </td>
                        <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>><?= getRelativeTime($order['date_time']) ?></td>
                        <td class="text-center" <?= $order['is_delete'] == 1 ? 'style="background-color: #c2c2c2;"' : '' ?>>
                            <a href="view-order.php?id=<?= $order['id'] ?>" target="_blank">
                                <img class="me-2 action-icon" src="assets/images/icons/view.png">
                            </a>
                        </td>
                    </tr>
            <?php
                }
                $x++;
            }


            ?>
        </tbody>
    </table>

<?php

} else {
    echo "reload";
}
