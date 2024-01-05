<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['id'])) {

        $invoice_rs = Database::search("SELECT * FROM `invoice` WHERE `invoice`.`id` = ? AND `is_completed`='0' ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {
            $invoice = $invoice_rs->fetch_assoc();
?>
            <div class="row">
                <div class="modal--header">
                    <div class="d-flex ">
                        <h2 class="my-auto ">Add Payment</h2>
                        <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                    </div>
                    <hr class="text-dark my-2">
                </div>
                <div class="modal--body">
                    <div class="row p-3 text-center">
                        <div class="col-12">
                            <?php
                            $invoice_payment_rs = Database::search("SELECT * FROM `invoice_payment` INNER JOIN `payment_type` ON `payment_type`.`id` = `invoice_payment`.`payment_type_id` WHERE `invoice_id` = ? ORDER BY `date_time` DESC", "s", [$_POST['id']]);

                            if ($invoice_payment_rs->num_rows > 0) {
                                $balance = $invoice_payment_rs->fetch_assoc();
                            ?>
                                <div class="row my-3">
                                    <div class="col-12">
                                        <span id="balance">Balance: Rs.<?= number_format($balance['balance'], 2) ?>/= </span>
                                    </div>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="row my-3">
                                    <div class="col-12">
                                        <span id="balance">Balance: Rs.<?= number_format($invoice['total'], 2) ?>/= </span>
                                    </div>
                                </div>
                            <?php
                            }

                            ?>

                            <div class="row my-3">
                                <div class="col-12">
                                    <input type="text" onkeyup="balanceUpdate(<?= $invoice['id'] ?>); formatPrice(this);" onkeydown="keyBlocker(event,'price');" class="form-control" id="paidAmount" placeholder="Paid Amount" />
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-12">
                                    <select class="form-control" id="paymentType">
                                        <option value="0">Select Option</option>
                                        <option value="1">Cash</option>
                                        <option value="2">Cheque</option>
                                    </select>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal--footer d-sm-flex text-secondary">
                    <div class="ml-auto float-right p-2">
                        <button class="btn close-btn mr-1" onclick="closeModal1();">Cancel</button>
                        <button class="btn submit-btn" onclick="addOrderPayment(<?= $_POST['id'] ?>);">Add</button>
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
