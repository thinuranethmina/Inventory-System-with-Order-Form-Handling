<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['id'])) {

        $invoice_rs = Database::search("SELECT * FROM `invoice` WHERE `invoice`.`id` = ? AND `is_delivered`='0' AND `is_delete` = '0'  ", "s", [$_POST['id']]);

        if ($invoice_rs->num_rows == 1) {
            $invoice = $invoice_rs->fetch_assoc();
?>
            <div class="row">
                <div class="modal--header">
                    <div class="d-flex ">
                        <h2 class="my-auto ">Mark As Delivered</h2>
                        <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                    </div>
                    <hr class="text-dark my-2">
                </div>
                <div class="modal--body">
                    <div class="row p-3 text-center">
                        <div class="col-12">
                            <table id="orderProductTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Image</th>
                                        <th>Required qty</th>
                                        <th>Primary Stock</th>
                                        <th>Ongoing Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $invoice_item_rs = Database::search("SELECT *,`invoice_item`.`id` AS `id`,`invoice_item`.`qty` AS `qty`,`stock`.`qty` AS `pqty` FROM `invoice_item` INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id` INNER JOIN `stock` ON `product`.`id`= `stock`.`product_id` WHERE `invoice_id` = ? ", "s", [$_POST['id']]);

                                    while ($invoice_item = $invoice_item_rs->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td data-product="<?= $invoice_item['id'] ?>">
                                                <img src="<?= $invoice_item['cover_image'] ?>" class="table-main-image my-auto mt-2">
                                                <p class="my-auto"><?= $invoice_item['model_no'] ?></p>
                                            </td>
                                            <td><?= $invoice_item['free_qty'] + $invoice_item['qty'] ?></td>
                                            <?php
                                            $total_qty = $invoice_item['free_qty'] + $invoice_item['qty'];
                                            ?>
                                            <td class="text-center">
                                                <span><?= $invoice_item['pqty'] ?> Available</span>
                                                <input type="text" class="form-control mx-auto" style="width: 70px; height: 30px;" value="<?= $invoice_item['pqty'] >= $total_qty ? $x = $total_qty : $x = $invoice_item['pqty']  ?>">
                                            </td>
                                            <td class="text-center">
                                                <span><?= $invoice_item['ongoing_qty'] ?> Available</span>
                                                <input type="text" class="form-control mx-auto" style="width: 70px; height: 30px;" value="<?= $invoice_item['ongoing_qty'] >= $total_qty - $x ? $total_qty - $x : $invoice_item['ongoing_qty'] ?>">
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
                <div class="modal--footer d-sm-flex text-secondary">
                    <div class="ml-auto float-right p-2">
                        <button class="btn close-btn mr-1" onclick="closeModal1();">Cancel</button>
                        <button class="btn submit-btn" onclick="markAsDelivered(<?= $_POST['id'] ?>);">Submit</button>
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
