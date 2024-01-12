<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['eid']) && isset($_POST['price']) && isset($_POST['priceType']) && isset($_POST['eqty']) && isset($_POST['erow'])) {

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

                                        <span>Cash Price: Rs.<?= number_format($price['cash_price']) ?>/=</span>
                                        <br>
                                        <span>Credit Price: Rs.<?= number_format($price['credit_price']) ?>/=</span>

                                    </div>
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-12 text-start">
                                    <span>Price Per Item</span>
                                    <input type="text" class="form-control" id="price" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" value="<?php
                                                                                                                                                                    if ($_POST['price'] != null && !empty($_POST['price']) && $_POST['price'] != '' && is_numeric($_POST['price']) && $_POST['price'] > 0) {
                                                                                                                                                                        echo number_format($_POST['price'], 2);
                                                                                                                                                                    }
                                                                                                                                                                    ?>" placeholder="Price" />
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-12 text-start">
                                    <span>Qty</span>
                                    <input type="text" class="form-control" onkeydown="keyBlocker(event,'qty');" id="eqty" value="<?php if (is_numeric($_POST['eqty'])) {
                                                                                                                                        echo trim($_POST['eqty']);
                                                                                                                                    } ?>" placeholder="qty" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal--footer d-sm-flex text-secondary">
                    <div class="ml-auto float-right p-2">
                        <button class="btn close-btn mr-1" onclick="closeModal1();">Cancel</button>
                        <button class="btn delete-btn mr-1 " onclick="closeModal1(); removeReturnProduct(<?= $_POST['eid'] ?>);">Remove</button>
                        <button class="btn submit-btn" onclick="updateReturnProductInInvoice(<?= $_POST['erow'] ?>,<?= $_POST['eid'] ?>);">Save</button>
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
