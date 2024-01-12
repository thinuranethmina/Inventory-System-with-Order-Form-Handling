<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['stock'])) {
        if ($_POST['stock'] == '1') {
            ?>
            <div class="row my-3">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Product</span>
                </div>
                <div class="col-12 col-lg-9">
                    <select name="" id="product" onchange="updateStockProductViewer();operationInfoViewer();" class="form-control">
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

            <div class="row mb-3">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Operation</span>
                </div>
                <div class="col-12 col-lg-9">

                    <select name="" id="operation" class="form-control" onchange="operationInfoViewer();">
                        <option value="0">Select Operation</option>
                        <option value="1">Increase</option>
                        <option value="2">Decrease</option>
                        <option value="5">Move to Ongoing Stock </option>
                    </select>
                </div>
            </div>
            <?php
        } else if ($_POST['stock'] == '2') {

            ?>
                <div class="row my-3">
                    <div class="col-12 col-lg-3 my-auto">
                        <span class="form-text-1">Product</span>
                    </div>
                    <div class="col-12 col-lg-9">
                        <select name="" id="product" onchange="updateStockProductViewer();operationInfoViewer();" class="form-control">
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

                <div class="row mb-3">
                    <div class="col-12 col-lg-3 my-auto">
                        <span class="form-text-1">Operation</span>
                    </div>
                    <div class="col-12 col-lg-9">

                        <select name="" id="operation" class="form-control" onchange="operationInfoViewer();">
                            <option value="0">Select Operation</option>
                            <option value="1">Increase</option>
                            <option value="4">Move to Primary Stock</option>
                            <option value="2">Decrease</option>
                        </select>
                    </div>
                </div>
            <?php
        }
        ?>


        <div class="row my-3 d-lg-flex text-center" id="operation-content">
            <div class="col-12 col-lg mt-3 mt-lg-0">
                <div class="border border-4 width-fit-contents mx-auto p-3">
                    <span>Avalilable Qty</span>
                    <h6 class="mx-auto mt-2 width-min-content" id="aqty">xxx</h6>
                </div>
            </div>
            <div class="m-auto col">
                <h4>?</h4>
            </div>
            <div class="col-12 col-lg">
                <div class="border border-4 width-fit-contents mx-auto p-3 px-4">
                    <span>Changing Qty</span>
                    <input type="text" id="cqty" onkeydown="keyBlocker(event,'price');" onkeyup="totalPreviewInStockUpdate();"
                        class="text-center mt-2 mx-auto form-control border-0 f-w-600" style="width: 80px; height:20px;" id=""
                        placeholder="XXX">
                </div>
            </div>
            <div class="m-auto col">
                <h4>=</h4>
            </div>
            <div class="col-12 col-lg">
                <div class="border border-4 width-fit-contents mx-auto p-3 px-4">
                    <span>Total Qty</span>
                    <input type="text" id="tqty" class="text-center mt-2 mx-auto form-control border-0 f-w-600 bg-transparent"
                        style="width: 140px; height:20px;" placeholder="XXX" readonly>
                </div>
            </div>
        </div>

        <div class="row my-3 mb-5 pb-5">
            <div class="col-12 col-lg-3 my-auto">
                <span class="form-text-1">Note</span>
            </div>
            <div class="col-12 col-lg-9">
                <div id="editor" style="height: 100%;" class="">
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-5 pt-lg-0">
            <div class="col-12 pt-5 pt-lg-0">
                <button class="btn submit-btn w-100" onclick="updateStock();">Update Now</button>
            </div>
        </div>

        <?php
    }
} else {
    echo "reload";
}
