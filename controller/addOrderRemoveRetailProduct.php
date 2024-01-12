<?php

require "util/userStatus.php";

if (User::is_allow()) {

    $nextValue = 1;

    for ($i = 1; $i < $_POST['rows']; $i++) {
        if (isset($_POST['p' . $i]) && isset($_POST['qty' . $i]) && isset($_POST['fqty' . $i])) {
            $product_rs =  Database::search("SELECT *,`product`.`id` AS `pid` FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

            $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

            if ($product_rs->num_rows == 1 && $_POST['p' . $i] != $_POST['rid']) {
                $product = $product_rs->fetch_assoc();
                $price = $price_rs->fetch_assoc();

                $fqty = 0;

                if (isset($_POST['fqty'])) {
                    if (is_numeric($_POST['fqty']) && $_POST['fqty'] != '0') {
                        $fqty = ltrim($_POST['fqty'], 0);
                    }
                }

?>

                <tr>
                    <td id="<?= $product['pid'] ?>" onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                        <span class="icon-on-hover<?= $product['pid'] ?>"><?= $nextValue ?></span>
                        <img onclick="removeRetailProduct(<?= $product['pid'] ?>);" class="icon-on-hover<?= $product['pid'] ?> d-none mb-1 action-icon" src="assets/images/icons/remove.png"> <br>
                        <img onclick="editRetailProductModalOpen(<?= $product['pid'] ?>,<?= $nextValue++ ?>);" class="icon-on-hover<?= $product['pid'] ?> d-none action-icon" src="assets/images/icons/edit.png">
                    </td>
                    <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                        <img src=" <?= $product['cover_image'] ?>" class="table-main-image">
                    </td>
                    <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= $product['model_no'] ?></td>
                    <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);">
                        <?= $fqty ?>
                    </td>
                    <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= ltrim($_POST['qty' . $i], 0) ?></td>

                    <?php

                    if ($price['retail_price'] == $_POST['sprice' . $i]) {
                    ?>
                        <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($price['retail_price'], 2) ?></td>
                        <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($price['retail_price'] * $_POST['qty' . $i], 2) ?></td>
                    <?php
                    } else {
                    ?>
                        <td class="text-danger f-w-500" onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($_POST['sprice' . $i], 2) ?></td>
                        <td onmouseout="showOptions(<?= $product['pid'] ?>);" onmouseover="showOptions(<?= $product['pid'] ?>);"><?= number_format($_POST['sprice' . $i] * $_POST['qty' . $i], 2) ?></td>
                    <?php
                    }

                    ?>

                </tr>

            <?php

            }
        }

        if ($i == $_POST['rows'] - 1) {
            ?>
            <tr class=" p-0 m-0" style="height: 8px !important;">
                <td class="text-center p-0 m-0" colspan="7">
                    <button class="btn btn-secondary w-100" onclick="viewAddProductModalInAddRetailOrder();">Add Product</button>
                </td>
            </tr>
<?php


        }
    }
} else {
    echo "reload";
}
