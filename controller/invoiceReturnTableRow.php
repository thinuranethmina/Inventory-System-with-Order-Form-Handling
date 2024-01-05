<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (!isset($_POST['product'])) {
        echo "msg1";
    } else if (empty(trim($_POST['product'])) || $_POST['product'] == 0) {
        echo "msg1";
    } else if (!isset($_POST['price'])) {
        echo "msg2";
    } else if (!is_numeric($_POST['price'])) {
        echo "msg2";
    } else if ($_POST['price'] <= 0) {
        echo "msg2";
    } else if (!isset($_POST['qty'])) {
        echo "msg3";
    } else if ($_POST['qty'] <= 0 || empty(trim($_POST['qty']))) {
        echo "msg3";
    } else if (!isset($_POST['nextRow'])) {
        echo "error";
    } else if (empty(trim($_POST['nextRow']))) {
        echo "error";
    } else {

        if (empty(trim($_POST['qty']))) {
            $_POST['qty'] = 0;
        }

        $product_rs = Database::search("SELECT *,`product`.`id` AS `pid` FROM `product` INNER JOIN `stock` ON `product`.`id`= `stock`.`product_id` WHERE `product`.`id` = ? ", "s", [$_POST['product']]);

        $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['product']]);

        if ($product_rs->num_rows == 1) {
            $product = $product_rs->fetch_assoc();
            $price = $price_rs->fetch_assoc();
?>

            <td id="<?= $product['pid'] ?>" onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                <span class="icon-on-hover<?= $product['pid'] ?>"><?= $_POST['nextRow'] ?></span>
                <img onclick="removeReturnProduct(<?= $product['pid'] ?>);" class="icon-on-hover<?= $product['pid'] ?> d-none mb-1 action-icon" src="assets/images/icons/remove.png"> <br>
                <img onclick="editReturnProductModalOpen(<?= $product['pid'] ?>,<?= $_POST['nextRow'] ?>);" class="icon-on-hover<?= $product['pid'] ?> d-none action-icon" src="assets/images/icons/edit.png">
            </td>
            <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                <img src=" <?= $product['cover_image'] ?>" class="table-main-image">
            </td>
            <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= $product['model_no'] ?></td>
            <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= ltrim($_POST['qty'], 0) ?></td>

            <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($_POST['price'], 2) ?></td>
            <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($_POST['price'] * $_POST['qty'], 2) ?></td>



<?php
        }
    }
} else {
    echo "reload";
}
