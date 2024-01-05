<?php

require "util/userStatus.php";

if (User::is_allow()) {
?>
    <div class="row">
        <div class="modal--header">
            <div class="d-flex ">
                <h2 class="my-auto ">Add Product</h2>
                <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
            </div>
            <hr class="text-dark my-2">
        </div>
        <div class="modal--body">
            <div class="row p-3 text-center">
                <div class="col-12">

                    <div class="row my-3" id="productPreview">

                    </div>

                    <div class="row my-3">
                        <div class="col-12">
                            <select onchange="updateAddOrderProductViewer();" id="product" class="form-control">
                                <option value="0">Select Product</option>
                                <?php
                                $sql = "SELECT * FROM `product` WHERE `status_id`='1' ";

                                if (isset($_POST['rows'])) {
                                    if (is_numeric($_POST['rows'])) {
                                        for ($i = 1; $i <= $_POST['rows']; $i++) {
                                            if (isset($_POST['p' . $i])) {
                                                $sql .= " AND `product`.`id` != '" . $_POST['p' . $i] . "' ";
                                            }
                                        }
                                    }
                                }

                                $category_rs = Database::search("SELECT * FROM `category` ORDER BY `name`");

                                while ($category = $category_rs->fetch_assoc()) {

                                    $product_check_rs = Database::search($sql . " AND `category_id` = ? ", "s", [$category['id']]);

                                    if ($product_check_rs->num_rows > 0) {
                                ?>

                                        <optgroup label="<?= $category['name'] ?>">

                                            <?php
                                            $sub_category_rs = Database::search("SELECT * FROM `sub_category` WHERE `category_id` = ? ORDER BY `name`", "s", [$category['id']]);

                                            if ($sub_category_rs->num_rows > 0) {

                                                while ($sub_category = $sub_category_rs->fetch_assoc()) {

                                                    $product_check_rs = Database::search($sql . " AND `category_id` = ?  AND `sub_category_id` = ? ", "ss", [$category['id'], $sub_category['id']]);

                                                    if ($product_check_rs->num_rows > 0) {
                                            ?>

                                        <optgroup class="text-black-50" label="&nbsp;&nbsp;&nbsp;&nbsp;<?= $sub_category['name'] ?>">
                                            <?php
                                                        $product_rs = Database::search($sql . " AND `category_id` = ? AND `sub_category_id` = ? ORDER BY `model_no`", "ss", [$category['id'], $sub_category['id']]);

                                                        while ($product = $product_rs->fetch_assoc()) {
                                            ?>
                                                <option class="text-dark" value="<?= $product['id'] ?>">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<?= $product['model_no'] ?>
                                                </option>
                                            <?php
                                                        }
                                            ?>
                                        </optgroup>

                                    <?php
                                                    }
                                                }
                                            } else {
                                                $product_rs = Database::search($sql . " AND `category_id` = ? ", "s", [$category['id']]);

                                                while ($product = $product_rs->fetch_assoc()) {
                                    ?>
                                    <option value="<?= $product['id'] ?>">
                                        <?= $product['model_no'] ?>
                                    </option>
                            <?php
                                                }
                                            }

                            ?>

                            </optgroup>
                    <?php
                                    }
                                }

                    ?>
                            </select>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-12">
                            <input type="text" class="form-control" onkeyup="formatPrice(this);" onkeydown="keyBlocker(event,'price');" id="sprice" placeholder="Special Price (Optional)" />
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-12">
                            <input type="text" class="form-control" onkeydown="keyBlocker(event,'qty');" id="qty" placeholder="qty" />
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-12">
                            <input type="text" class="form-control" onkeydown="keyBlocker(event,'qty');" id="fqty" placeholder="Free qty (Optional)" />
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="modal--footer d-sm-flex text-secondary">
            <div class="ml-auto float-right p-2">
                <button class="btn close-btn mr-1" onclick="closeModal1();">Cancel</button>
                <button class="btn submit-btn" onclick="addProductUpdatetoInvoice();">Add</button>
            </div>
        </div>
    </div>
<?php
} else {
    echo "reload";
}
