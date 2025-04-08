<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['product']) && isset($_POST['operation']) && isset($_POST['stockType'])) {
        if (!empty(trim($_POST['product'])) && $_POST['product'] != 0 && !empty(trim($_POST['stockType'])) && $_POST['stockType'] != 0 && !empty(trim($_POST['operation'])) && $_POST['operation'] != 0) {

            $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `stock` ON `product`.`id`= `stock`.`product_id` WHERE `product`.`id` = ? AND `status_id`='1' ", "s", [$_POST['product']]);

            if ($product_rs->num_rows == 1) {
                $product = $product_rs->fetch_assoc();
?>

                <div class="col-12 col-lg mt-3 mt-lg-0">
                    <div class="border border-4 width-fit-contents mx-auto p-3">
                        <span>Avalilable Qty</span>
                        <h6 class="mx-auto mt-2 width-min-content" id="aqty"><?= $_POST['stockType'] == '1' ? $product['qty'] : $product['ongoing_qty'] ?></h6>
                    </div>
                </div>
                <div class="m-auto col">
                    <h4><?= $_POST['operation'] == '1' ? "+" : "-" ?></h4>
                </div>
                <div class="col-12 col-lg">
                    <div class="border border-4 width-fit-contents mx-auto p-3 px-4">
                        <span>Changing Qty</span>
                        <input type="text" id="cqty" onkeydown="keyBlocker(event,'price');" onkeyup="totalPreviewInStockUpdate();" class="text-center mt-2 mx-auto form-control border-0 f-w-600" style="width: 80px; height:20px;" id="" placeholder="XXX">
                    </div>
                </div>
                <div class="m-auto col">
                    <h4>=</h4>
                </div>
                <div class="col-12 col-lg">
                    <div class="border border-4 width-fit-contents mx-auto p-3 px-4">
                        <span>Total Qty</span>
                        <input type="text" id="tqty" class="text-center mt-2 mx-auto form-control border-0 f-w-600 bg-transparent" style="height:20px; width: 140px;" placeholder="XXX" value="<?= $_POST['stockType'] == '1' ? $product['qty'] : $product['ongoing_qty'] ?>" readonly>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="col-12 col-lg mt-3 mt-lg-0">
                <div class="border border-4 width-fit-contents mx-auto p-3">
                    <span>Avalilable Qty</span>
                    <h6 class="mx-auto mt-2 width-min-content" id="aqty">xxx</h6>
                </div>
            </div>
            <div class="m-auto col">
                <h4><?php
                    if ($_POST['operation'] != "0") {
                        echo $_POST['operation'] == '1' ? "+" : "-";
                    } else {
                        echo "?";
                    }
                    ?></h4>
            </div>
            <div class="col-12 col-lg">
                <div class="border border-4 width-fit-contents mx-auto p-3 px-4">
                    <span>Changing Qty</span>
                    <input type="text" id="cqty" onkeydown="qtyNumFilter(event);" onkeyup="totalPreviewInStockUpdate();" class="text-center mt-2 mx-auto form-control border-0 f-w-600" style="width: 80px; height:20px;" placeholder="XXX">
                </div>
            </div>
            <div class="m-auto col">
                <h4>=</h4>
            </div>
            <div class="col-12 col-lg">
                <div class="border border-4 width-fit-contents mx-auto p-3 px-4">
                    <span>Total Qty</span>
                    <input type="text" id="tqty" class="text-center mt-2 mx-auto form-control border-0 f-w-600 bg-transparent" style="width: 140px; height:20px;" placeholder="XXX" readonly>
                </div>
            </div>
<?php
        }
    }
} else {
    echo "reload";
}
