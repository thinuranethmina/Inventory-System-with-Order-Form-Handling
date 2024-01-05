<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['user']) && isset($_POST['product'])) {

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


        $all_stock_rs = Database::search("SELECT * FROM `stock_history` ");

        $sql = "SELECT *,`stock_history`.`id`,`stock_history`.`date_time` FROM `stock_history` INNER JOIN `product` ON `product`.`id` = `stock_history`.`product_id` WHERE `stock_history`.`id` !='0' ";

        if ($_POST['user'] != 0 && $_POST['user'] != '' && $_POST['user'] != null) {
            $sql .= " AND `stock_history`.`user_id` = '" . $_POST['user'] . "' ";
        }

        if ($_POST['product'] != 0 && $_POST['product'] != '' && $_POST['product'] != null) {
            $sql .= " AND `stock_history`.`product_id` = '" . $_POST['product'] . "' ";
        }


        if ($_POST['operation'] != 0 && $_POST['operation'] != '' && $_POST['operation'] != null) {
            $sql .= " AND `stock_history`.`operation_type_id` = '" . $_POST['operation'] . "' ";
        }

        if ($_POST['stockType'] != 0 && $_POST['stockType'] != '' && $_POST['stockType'] != null) {
            $sql .= " AND `stock_history`.`stock_type_id` = '" . $_POST['stockType'] . "' ";
        }

        $stock_rs = Database::search($sql . " ORDER BY  `stock_history`.`date_time` DESC, `stock_history`.`id` DESC ");

        ?>

        <div class="row">
            <div class="col-12 d-none d-md-block">
                <span class="text-white f-w-300">Showing <?= $stock_rs->num_rows ?> of <?= $all_stock_rs->num_rows ?>
                    entries</span>
            </div>
        </div>

        <table class="" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Stock</th>
                    <th class="d-none d-xl-table-cell text-center">Old qty</th>
                    <th class="fs-4 text-center">±</th>
                    <th class="d-none d-xl-table-cell text-center">Total qty</th>
                    <th class="d-none d-lg-table-cell text-center">Time</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if ($stock_rs->num_rows <= 0) {
                    ?>
                    <tr>
                        <td class="text-center rounded-3" colspan="9">
                            <h4 class="m-auto">No Result</h4>
                        </td>
                    </tr>
                    <?php
                }


                $x = 1;
                $date = null;
                while ($stock = $stock_rs->fetch_assoc()) {

                    if ($date == date('Y-m-d', strtotime($stock['date_time']))) {
                        ?>

                        <tr>
                            <td>
                                <?= $x ?>
                            </td>
                            <td>
                                <img src="<?= $stock['cover_image'] ?>" class="table-main-image">
                            </td>
                            <td>
                                <?= $stock['model_no'] ?>
                            </td>
                            <td>
                                <?php
                                if ($stock['stock_type_id'] == '1') {
                                    echo "Primary";
                                } else if ($stock['stock_type_id'] == '2') {
                                    echo "Ongoing";
                                }
                                ?>
                            </td>
                            <td class="d-none d-xl-table-cell text-center">
                                <?= $stock['old_qty'] ?>
                            </td>
                            <td class="text-center">
                                <?php
                                if ($stock['operation_type_id'] == '1') {
                                    ?>
                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '2') {
                                    ?>
                                        <img class="mr-1 action-icon" src="assets/images/icons/down-red.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '3') {
                                    ?>
                                            <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '4') {
                                    ?>
                                                <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                        <?php
                                        if ($stock['stock_type_id'] == '1') {
                                            ?>
                                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                        <?php
                                        } else if ($stock['stock_type_id'] == '2') {
                                            ?>
                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                        <?php
                                        }
                                } else if ($stock['operation_type_id'] == '5') {
                                    ?>
                                                    <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                        <?php
                                        if ($stock['stock_type_id'] == '1') {
                                            ?>
                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                        <?php
                                        } else if ($stock['stock_type_id'] == '2') {
                                            ?>
                                                            <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                        <?php
                                        }
                                }
                                ?>
                                <?= $stock['changed_qty'] ?>
                            </td>
                            <td class="d-none d-xl-table-cell text-center">
                                <?= $stock['total_qty'] ?>
                            </td>
                            <td class="d-none d-lg-table-cell text-center"><?= getRelativeTime($stock['date_time']) ?></td>
                            <td class="text-center">
                                <img class="me-2 action-icon" onclick="viewModal('<?= $stock['id'] ?>','stock');"
                                    src="assets/images/icons/view.png">
                            </td>
                        </tr>

                        <?php

                    } else {
                        $date = date('Y-m-d', strtotime($stock['date_time']));
                        ?>
                        <tr class="shadow-none" style="height: 8px !important;">
                            <td colspan="9" class="text-center date-row"><?= date("Y-m-d") == $date ? "Today" : $date ?>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <?= $x ?>
                            </td>
                            <td>
                                <img src="<?= $stock['cover_image'] ?>" class="table-main-image">
                            </td>
                            <td>
                                <?= $stock['model_no'] ?>
                            </td>
                            <td>
                                <?php
                                if ($stock['stock_type_id'] == '1') {
                                    echo "Primary";
                                } else if ($stock['stock_type_id'] == '2') {
                                    echo "Ongoing";
                                }
                                ?>
                            </td>
                            <td class="d-none d-xl-table-cell text-center">
                                <?= $stock['old_qty'] ?>
                            </td>
                            <td class="text-center">
                                <?php
                                if ($stock['operation_type_id'] == '1') {
                                    ?>
                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '2') {
                                    ?>
                                        <img class="mr-1 action-icon" src="assets/images/icons/down-red.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '3') {
                                    ?>
                                            <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                    <?php
                                } else if ($stock['operation_type_id'] == '4') {
                                    ?>
                                                <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                        <?php
                                        if ($stock['stock_type_id'] == '1') {
                                            ?>
                                                    <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                        <?php
                                        } else if ($stock['stock_type_id'] == '2') {
                                            ?>
                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                        <?php
                                        }
                                } else if ($stock['operation_type_id'] == '5') {
                                    ?>
                                                    <img class="mr-1 action-icon" src="assets/images/icons/moved.png">
                                        <?php
                                        if ($stock['stock_type_id'] == '1') {
                                            ?>
                                                        <img class="mr-1 action-icon" src="assets/images/icons/down-orange.png">
                                        <?php
                                        } else if ($stock['stock_type_id'] == '2') {
                                            ?>
                                                            <img class="mr-1 action-icon" src="assets/images/icons/up-green.png">
                                        <?php
                                        }
                                }
                                ?>
                                <?= $stock['changed_qty'] ?>
                            </td>
                            <td class="d-none d-xl-table-cell text-center">
                                <?= $stock['total_qty'] ?>
                            </td>
                            <td class="d-none d-lg-table-cell text-center"><?= getRelativeTime($stock['date_time']) ?></td>
                            <td class="text-center">
                                <img class="me-2 action-icon" onclick="viewModal('<?= $stock['id'] ?>','stock');"
                                    src="assets/images/icons/view.png">
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
    }
} else {
    echo "reload";
}
