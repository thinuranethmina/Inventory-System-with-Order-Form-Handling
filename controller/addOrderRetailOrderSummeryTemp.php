<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['rows'])) {
        if (is_numeric($_POST['rows']) && $_POST['rows'] > 1) {
            $total = 0;
            $subTotal = 0;
            $discount = 0;
            $isReady = false;


            for ($i = 1; $i < $_POST['rows']; $i++) {
                if (isset($_POST['p' . $i]) && isset($_POST['qty' . $i]) && isset($_POST['price' . $i])) {
                    $product_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                    $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                    if ($product_rs->num_rows == 1) {
                        $product = $product_rs->fetch_assoc();
                        $price = $price_rs->fetch_assoc();

                        $subTotal += $_POST['price' . $i] * $_POST['qty' . $i];

                        if ($i == $_POST['rows'] - 1) {
                            $isReady = true;
                        }
                    } else {
                        break;
                    }
                } else {
                    break;
                }
            }


            if ($isReady) {

                $total = $subTotal * ((100 - $discount) / 100);

?>
                <table>
                    <thead>
                        <tr style="height: 8px !important;">
                            <th class="text-center rounded rounded-3" colspan="2">Order Summery</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 8px !important;">
                            <td>Sub Total:</td>
                            <td>Rs.<?= number_format($subTotal, 2) ?>/=</td>
                        </tr>
                        <tr style="height: 8px !important;">
                            <td>Discount:</td>
                            <td>
                                <div class="input-group">
                                    <input type="text" onkeydown="keyBlocker(event,'qty');" class="form-control p-0 px-1 m-0" style="height: min-content !important;" id="discount" onkeyup="updateRetailOrderSummeryPayments(); " placeholder="0">
                                    <div class="input-group-append">
                                        <div class="input-group-text input-group-text-right" style="height: 23px !important;">%</div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr style="height: 8px !important;">
                            <td>Total:</td>
                            <td id="totalView">Rs.<?= number_format($total, 2) ?>/=</td>
                        </tr>

                        <tr style="height: 8px !important;">
                            <td>Paid Amount:</td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text input-group-text-left" style="height: 23px !important;">Rs.</div>
                                    </div>
                                    <input type="text" class="form-control p-0 px-1 m-0" style="height: min-content !important;" id="paidAmount" onkeyup="updateRetailOrderSummeryPayments(); formatPrice(this);" onkeydown="keyBlocker(event,'price');" placeholder="0">
                                </div>
                            </td>
                        </tr>

                        <tr style="height: 8px !important;">
                            <td>Balance:</td>
                            <td id="balanceView">Rs.<?= number_format($total, 2) ?>/=</td>
                        </tr>

                    </tbody>
                </table>
            <?php
            } else {

            ?>
                <table>
                    <thead>
                        <tr style="height: 8px !important;">
                            <th class="text-center rounded rounded-3" colspan="2">Error</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
<?php
            }
        }
    }
} else {
    echo "reload";
}
