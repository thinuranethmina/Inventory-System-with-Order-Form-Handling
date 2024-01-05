<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (!isset($_POST['product'])) {
        echo "msg1";
    } else if (empty(trim($_POST['product'])) || $_POST['product'] == 0) {
        echo "msg1";
    } else if (!isset($_POST['nextRow'])) {
        echo "error";
    } else if (empty(trim($_POST['nextRow']))) {
        echo "error";
    } else if (!isset($_POST['qty'])) {
        echo "msg2";
    } else if (($_POST['qty'] <= 0 || empty(trim($_POST['qty']))) && ($_POST['fqty'] <= 0 || empty(trim($_POST['fqty'])))) {
        echo "msg2";
    } else if (!isset($_POST['pqty']) || !isset($_POST['oqty'])) {
        echo "msg2";
    } else {

        if (empty($_POST['pqty']) || !is_numeric($_POST['pqty'])) {
            $_POST['pqty'] = 0;
        }

        if (empty($_POST['oqty']) || !is_numeric($_POST['oqty'])) {
            $_POST['oqty'] = 0;
        }

        $isReady = false;

        $fqty = 0;
        if (isset($_POST['fqty'])) {
            if (is_numeric($_POST['fqty']) && $_POST['fqty'] != '0') {
                $fqty = ltrim($_POST['fqty'], 0);
            }
        }

        if (empty(trim($_POST['qty']))) {
            $_POST['qty'] = 0;
        }

        if (($_POST['pqty'] + $_POST['oqty']) == $_POST['qty'] + $fqty) {
            $isReady = true;
        } else {
            echo "msg3";
        }

        if ($isReady) {

            $product_rs = Database::search("SELECT *,`product`.`id` AS `pid` FROM `product` INNER JOIN `stock` ON `product`.`id`= `stock`.`product_id` WHERE `product`.`id` = ? AND `status_id`='1' ", "s", [$_POST['product']]);

            $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['product']]);

            if ($product_rs->num_rows == 1) {
                $product = $product_rs->fetch_assoc();
                $price = $price_rs->fetch_assoc();


                if ($_POST['pqty'] <= $product['qty']) {
                    if ($_POST['oqty'] <= $product['ongoing_qty']) {
?>
                        <td id="<?= $product['pid'] ?>" data-ongoingStock="<?= $_POST['oqty'] ?>" data-primaryStock="<?= $_POST['pqty'] ?>" onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                            <span class="icon-on-hover<?= $product['pid'] ?>"><?= $_POST['nextRow'] ?></span>
                            <img onclick="removeRetailProduct(<?= $product['pid'] ?>);" class="icon-on-hover<?= $product['pid'] ?> d-none mb-1 action-icon" src="assets/images/icons/remove.png"> <br>
                            <img onclick="editRetailProductModalOpen(<?= $product['pid'] ?>,<?= $_POST['nextRow'] ?>);" class="icon-on-hover<?= $product['pid'] ?> d-none action-icon" src="assets/images/icons/edit.png">
                        </td>
                        <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                            <img src=" <?= $product['cover_image'] ?>" class="table-main-image">
                        </td>
                        <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= $product['model_no'] ?></td>
                        <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                            <?= $fqty ?>
                        </td>
                        <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= $_POST['qty'] == 0 ? "0" : ltrim($_POST['qty'], 0) ?></td>


                        <!-- Special price -->
                        <?php

                        $isOkayPrice = false;

                        if (isset($_POST['sprice'])) {
                            if ($_POST['sprice'] != null && !empty($_POST['sprice']) && $_POST['sprice'] != '' && is_numeric($_POST['sprice']) && $_POST['sprice'] > 0 && $_POST['sprice'] != $price['retail_price']) {
                        ?>
                                <td class="text-danger f-w-500" onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($_POST['sprice'], 2) ?></td>
                                <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($_POST['sprice'] * $_POST['qty'], 2) ?></td>
                            <?php
                            } else {
                                $isOkayPrice = true;
                            }
                        } else {
                            $isOkayPrice = true;
                        }

                        if ($isOkayPrice) {
                            ?>
                            <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($price['retail_price'], 2) ?></td>
                            <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($price['retail_price'] * ($_POST['qty']), 2) ?></td>
                        <?php
                        }

                        ?>
                        <!-- Special price -->

<?php
                    } else {
                        echo "NotEnoughOngoingstock";
                    }
                } else {
                    echo "NotEnoughPrimarystock";
                }
            }
        }
    }
} else {
    echo "reload";
}
