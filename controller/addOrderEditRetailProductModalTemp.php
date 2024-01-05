<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['eid']) && isset($_POST['efqty']) && isset($_POST['eqty']) && isset($_POST['epqty'])  && isset($_POST['eoqty']) && isset($_POST['erow'])) {

        $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `stock` ON `product`.`id`= `stock`.`product_id` WHERE `product`.`id` = ? AND `status_id`='1' ", "s", [$_POST['eid']]);

        $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['eid']]);

        if ($product_rs->num_rows == 1 && is_numeric($_POST['erow'])) {
            $product = $product_rs->fetch_assoc();
            $price = $price_rs->fetch_assoc();
?>
            <div class="row">
                <div class="modal--header">
                    <div class="d-flex ">
                        <h2 class="my-auto ">Edit Product</h2>
                        <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                    </div>
                    <hr class="text-dark my-2">
                </div>
                <div class="modal--body">
                    <div class="row p-3 text-center">
                        <div class="col-12">

                            <div class="row my-3" id="productPreview">
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
                            </div>

                            <div class="row my-3">
                                <div class="col-12 text-start">
                                    <span>Special Price (Optional)</span>
                                    <input type="text" class="form-control" id="sprice" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" value="<?php
                                                                                                                                                                    if ($_POST['sprice'] != null && !empty($_POST['sprice']) && $_POST['sprice'] != '' && is_numeric($_POST['sprice']) && $_POST['sprice'] > 0 && $_POST['sprice'] != $price['retail_price']) {
                                                                                                                                                                        echo number_format($_POST['sprice'], 2);
                                                                                                                                                                    }
                                                                                                                                                                    ?>" placeholder="Special Price (Optional)" />
                                </div>
                            </div>

                            <div class="my-3 d-flex">
                                <div class="">
                                    <span>Buying qty</span>
                                    <input type="text" class="form-control" id="eqty" onkeydown="keyBlocker(event,'qty');" value="<?php if (is_numeric($_POST['eqty'])) {
                                                                                                                                        echo trim($_POST['eqty']);
                                                                                                                                    } ?>" />
                                </div>
                                <div class="px-3 my-auto fs-5">+</div>
                                <div class="">
                                    <span>Free qty</span>
                                    <input type="text" class="form-control" onkeydown="keyBlocker(event,'qty');" id="efqty" value="<?php if (is_numeric($_POST['efqty'])) {
                                                                                                                                        echo trim($_POST['efqty']);
                                                                                                                                    } ?>" />
                                </div>
                            </div>

                            <hr>
                            <hr>

                            <div class="my-3 d-flex">
                                <div class="">
                                    <span>Qty from primary stock</span>
                                    <input type="text" class="form-control" onkeydown="keyBlocker(event,'qty');" id="epqty" value="<?php if (is_numeric($_POST['epqty'])) {
                                                                                                                                        echo trim($_POST['epqty']);
                                                                                                                                    } ?>" />
                                </div>
                                <div class="px-3 my-auto fs-5">+</div>
                                <div class="">
                                    <span>Qty from ongoing stock</span>
                                    <input type="text" class="form-control" onkeydown="keyBlocker(event,'qty');" id="eoqty" value="<?php if (is_numeric($_POST['eoqty'])) {
                                                                                                                                        echo trim($_POST['eoqty']);
                                                                                                                                    } ?>" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal--footer d-sm-flex text-secondary">
                    <div class="ml-auto float-right p-2">
                        <button class="btn close-btn mr-1" onclick="closeModal1();">Cancel</button>
                        <button class="btn delete-btn mr-1 " onclick="closeModal1(); removeRetailProduct(<?= $_POST['eid'] ?>);">Remove</button>
                        <button class="btn submit-btn" onclick="updateRetailProductInInvoice(<?= $_POST['erow'] ?>,<?= $_POST['eid'] ?>);">Save</button>
                    </div>
                </div>
            </div>
<?php
        } else {
            echo "reload";
        }
    } else {
        echo "reload";
    }
} else {
    echo "reload";
}
