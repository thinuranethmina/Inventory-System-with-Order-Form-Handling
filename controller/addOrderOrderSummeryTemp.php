<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['return_table_rows']) && isset($_POST['rows']) && isset($_POST['priceType'])) {
        if ((is_numeric($_POST['rows']) && $_POST['rows'] > 1) || (is_numeric($_POST['return_table_rows']) && $_POST['return_table_rows'] > 1)) {
            $total = 0;
            $subTotal = 0;
            $returnTotal = 0;
            $discount = 0;
            $isReady = true;


            for ($i = 1; $i < $_POST['rows']; $i++) {
                $isReady = false;
                if (isset($_POST['p' . $i]) && isset($_POST['qty' . $i]) && isset($_POST['price' . $i])) {
                    $product_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);

                    $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);

                    if ($product_rs->num_rows == 1) {
                        $product = $product_rs->fetch_assoc();
                        $price = $price_rs->fetch_assoc();

                        if ($_POST['priceType'] == '1') {
                            if ($_POST['price' . $i] != null && !empty($_POST['price' . $i]) && $_POST['price' . $i] != '' && is_numeric($_POST['price' . $i]) && $_POST['price' . $i] > 0 && $_POST['price' . $i] != $price['cash_price']) {
                                $subTotal += $_POST['price' . $i] * $_POST['qty' . $i];
                            } else {
                                $subTotal += $price['cash_price'] * $_POST['qty' . $i];
                            }
                        } else if ($_POST['priceType'] == '2') {
                            if ($_POST['price' . $i] != null && !empty($_POST['price' . $i]) && $_POST['price' . $i] != '' && is_numeric($_POST['price' . $i]) && $_POST['price' . $i] > 0 && $_POST['price' . $i] != $price['credit_price']) {
                                $subTotal += $_POST['price' . $i] * $_POST['qty' . $i];
                            } else {
                                $subTotal += $price['credit_price'] * $_POST['qty' . $i];
                            }
                        }


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


                for ($i = 1; $i < $_POST['return_table_rows']; $i++) {
                    $isReady = false;
                    if (isset($_POST['rp' . $i]) && isset($_POST['rqty' . $i]) && isset($_POST['rprice' . $i])) {
                        $rproduct_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? ", "s", [$_POST['rp' . $i]]);

                        if ($rproduct_rs->num_rows == 1) {
                            $returnTotal += $_POST['rprice' . $i] * $_POST['rqty' . $i];


                            if ($i == $_POST['return_table_rows'] - 1) {
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

                    $total = ($subTotal * ((100 - $discount) / 100)) - ($returnTotal);

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
                                        <input type="text" onkeydown="keyBlocker(event,'qty');" class="form-control p-0 px-1 m-0" style="height: min-content !important;" id="discount" onkeyup="updateOrderSummeryPayments(); " placeholder="0">
                                        <div class="input-group-append">
                                            <div class="input-group-text input-group-text-right" style="height: 23px !important;">%</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr style="height: 8px !important;">
                                <td>Return Items Total:</td>
                                <td id="returnView">Rs.<?= number_format($returnTotal, 2) ?>/=</td>
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
                                        <input type="text" class="form-control p-0 px-1 m-0" style="height: min-content !important;" id="paidAmount" onkeyup="updateOrderSummeryPayments(); formatPrice(this);" onkeydown="keyBlocker(event,'price');" placeholder="0">
                                    </div>
                                </td>
                            </tr>

                            <tr style="height: 8px !important;">
                                <td>Balance:</td>
                                <td id="balanceView">Rs.<?= number_format($total, 2) ?>/=</td>
                            </tr>

                            <tr style="height: 8px !important;">
                                <td colspan="2">
                                    <select id="paymentType" class="form-control p-0 px-1 border-0" onChange="selectPaymentType(this);" style="height: min-content !important;">
                                        <option value="0">Select Payment Type</option>
                                        <?php
                                        $shop_rs = Database::search("SELECT * FROM `payment_type` ORDER BY `name` ASC");

                                        while ($shop = $shop_rs->fetch_assoc()) {
                                        ?>
                                            <option value="<?= $shop['id'] ?>"><?= $shop['name'] ?></option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="chequeTermRow" class="d-none" style="height: 8px !important;">
                                <td colspan="2">
                                    <select id="chequeTerm" class="form-control p-0 px-1 border-0" style="height: min-content !important;">
                                        <option value="0">Select Cheque Term</option>
                                        <?php
                                        $cheque_term_rs = Database::search("SELECT * FROM `cheque_term`");

                                        while ($cheque_term = $cheque_term_rs->fetch_assoc()) {
                                        ?>
                                            <option value="<?= $cheque_term['id'] ?>"><?= $cheque_term['name'] ?></option>
                                        <?php
                                        }

                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php
                } else {

                ?>
                    <table>
                        <thead>
                            <tr style="height: 8px !important;">
                                <th class="text-center rounded rounded-3" colspan="2">Return Items Error</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                <?php
                }
            } else {

                ?>
                <table>
                    <thead>
                        <tr style="height: 8px !important;">
                            <th class="text-center rounded rounded-3" colspan="2">Billing Items Error</th>
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
