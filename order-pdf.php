
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        @page {
            size: A4;
            margin: 20px 20px 20px 1.5cm;
            padding: 0;
        }

        body {
            width: 100%;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            margin: 0 !important;
            padding: 0 !important;
        }


        .table {
            width: 100%;
            border-spacing: 0 !important;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table, .table td, .table th {
          border: 1px solid;
        }

        .table th,
        .table td {
            padding: 4px;
            font-size: 12px;
            line-height: 14px;
            font-weight: 400;
            text-align: left;
        }

        .table td p {
            margin-top: 0 !important;
            margin-bottom: 2px !important;
        }
            
        body .page-break:first-child {
            padding-top: 0;
        }
        
        .page-break {
            page-break-inside: avoid;
            border-bottom: 1px dotted black;
            border-radius: 2px;
            padding: 22px 0;
        }
        
        body .page-break:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .page-break .table:last-child {
            margin-bottom: 0;
        }

    </style>

</head>

<body>
    
    <?php
    
    while ($order = $order_rs->fetch_assoc()) {

    $invoice_rs = Database::search("SELECT *,`invoice`.`id` AS `id`,`shop`.`id` AS `shopID`,`shop`.`name` AS `shop`, `invoice`.`date_time` AS `date_time`, `invoice`.`note` AS `invoiceNote` , `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province`  FROM `invoice` INNER JOIN `shop`  ON `shop`.`id` = `invoice`.`shop_id`  INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id`  WHERE `invoice`.`id` = ? ", "s", [$order['id']]);

    $invoice = $invoice_rs->fetch_assoc();
    
    ?>
    
    <div class="page-break">
    
        <table class="table">
            <tr>
                <td>Shop</td>
                <td><?=$order['shop']?></td>
                
                <td>Order Id</td>
                <td><?=$order['order_id']?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?= $invoice['address'] . ", " . $invoice['city'] . ", <br> " . $invoice['district'] . " district, " . $invoice['province'] . " province." ?></td>
                
                <td>Price Type</td>
                <td><?php
                        
                        if($invoice['shopID'] != 0 && $invoice['is_credit'] == '1'){
                            echo "Credit";
                        } else if($invoice['shopID'] != 0 && $invoice['is_credit'] == '0'){
                            echo "Cash";
                        }
                        
                    ?>
                </td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><?= $invoice['mobile'] ?>&nbsp;&nbsp;<?= $invoice['other_mobile'] ?></td>
                
                <td>Added Date</td>
                <td><?=date('Y M d h:i A', strtotime($order['date_time']))?></td>
            </tr>
            <?php
            
            $user_rs = Database::search("SELECT * FROM `user` WHERE `id` = ? ", "s", [$order['user_id']])->fetch_assoc();
            
            ?>
            <tr>
                <td>Sales Ref</td>
                <td><?= $user_rs['name'] ?></td>
                
                <td>Status</td>
                <td><?= $order['is_delivered'] == 1 ? 'Delivered':'Not Delivered' ?>, <?= $order['is_completed'] == 1 ? 'Paid':'Not Paid' ?></td>
            </tr>
        </table>
    
        <table class="table">
            <tr>
                <td colspan="6" style="text-align:center;">Invoiced Items</td>
            </tr>
            <tr>
                <th>#</th>
                <th>Items</th>
                <th>Free Qty</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total Price</th>
            </tr>
            <?php
            $invoice_item_rs = Database::search("SELECT * FROM `invoice_item` INNER JOIN `product` ON `product`.`id` = `invoice_item`.`product_id` WHERE `invoice_id` = ? ", "s", [$order['id']]);

            $invoiced_qty = 0;
            $free_qty = 0;

            $x = 1;
            while ($invoice_item = $invoice_item_rs->fetch_assoc()) {
            $invoiced_qty = $invoiced_qty + $invoice_item['qty'];
            $free_qty = $free_qty + $invoice_item['free_qty'];
            ?>
                <tr>
                    <td>
                        <?= $x ?>
                    </td>
                    <td><?= $invoice_item['model_no'] ?></td>
                    <td><?= $invoice_item['free_qty'] ?></td>
                    <td><?= $invoice_item['qty'] ?></td>
                    <?php
                    if ($invoice_item['is_special_price'] == '1') {
                    ?>
                        <td class="text-danger f-w-500">
                            <?= number_format($invoice_item['sold_price_per_item'], 2) ?>
                        </td>
                    <?php
                    } else {
                    ?>
                        <td>
                            <?= number_format($invoice_item['sold_price_per_item'], 2) ?>
                        </td>
                    <?php
                    }

                    ?>
                    <td>
                        <?= number_format($invoice_item['sold_price_per_item'] * $invoice_item['qty'], 2) ?>
                    </td>

                </tr>
            <?php
                $x++;
            }
            ?>
        </table>
        
        <?php
        
        $invoice_return_item_rs = Database::search("SELECT * FROM `return_item` INNER JOIN `product` ON `product`.`id` = `return_item`.`product_id` WHERE `invoice_id` = ? ", "s", [$order['id']]);

        $return_qty = 0;
                
        if ($invoice_return_item_rs->num_rows > 0) {
            
        ?>
        
        <table class="table">
            <tr>
                <td colspan="5" style="text-align:center;">Returned Items</td>
            </tr>
            <tr>
                <th>#</th>
                <th>Items</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total Price</th>
            </tr>
            <?php
                

                while ($invoice_return_item = $invoice_return_item_rs->fetch_assoc()) {
                    $return_qty = $return_qty + $invoice_return_item['qty'];
                ?>
                    <tr>
                        <td>
                            <?= $x ?>
                        </td>
                        <td><?= $invoice_return_item['model_no'] ?></td>
                        <td><?= $invoice_return_item['qty'] ?></td>
                        <td>
                            <?= number_format($invoice_return_item['price'], 2) ?>
                        </td>
                        <td>
                            <?= number_format($invoice_return_item['price'] * $invoice_return_item['qty'], 2) ?>
                        </td>

                    </tr>
                <?php
                    $x++;
                }

                ?>
        </table>
            <?php
        }
            ?>
        
        <table class="table">
            <tr>
                <td colspan="4" style="text-align:center;">Order Summery</td>
            </tr>
            <tr>
                <td>Sub Total</td>
                <td>Rs.<?= number_format($invoice['sub_total'], 2) ?>/=</td>
                
                <td>Invoiced Qty</td>
                <td><?= number_format($invoiced_qty) ?></td>
            </tr>
            <tr>
                <td>Discount</td>
                <td><?= $invoice['discount'] ?>%</td>
                
                <td>Free Qty</td>
                <td><?= number_format($free_qty) ?></td>
            </tr>
            <tr>
                <td>Returned Items Total</td>
                <td>Rs.<?= number_format($invoice['return_total'], 2) ?>/=</td>
                
                <td>Returned Qty</td>
                <td><?= number_format($return_qty) ?></td>
            </tr>
            <?php
                $additional_amount_rs = Database::search("SELECT * FROM `invoice_payment` INNER JOIN `payment_type` ON `payment_type`.`id` = `invoice_payment`.`payment_type_id` WHERE `invoice_id` = ? AND `is_additional_amount`=? ORDER BY `date_time` ASC", "ss", [$order['id'], '1']);
                $additional_amount = 0;
                while ($additional_adding = $additional_amount_rs->fetch_assoc()) {
                    $additional_amount = $additional_amount + $additional_adding['paid_amount'];
                }
    
                if ($additional_amount_rs->num_rows > 0) {
                ?>
                    <tr>
                        <td>Additional Am.:</td>
                        <td>Rs.<?= number_format($additional_amount, 2) ?>/=</td>
                    </tr>
                <?php
                }
            ?>
            <tr>
                <td>Grand Total</td>
                <td>Rs.<?= number_format($invoice['total'], 2) ?>/=</td>
                
                <td colspan="2">
                <?php
                    if($invoice['cheque_term_id'] != ''){
                        $cheque_term_rs = Database::search("SELECT * FROM `cheque_term`");
        
                        while ($cheque_term = $cheque_term_rs->fetch_assoc()) {
                            echo $invoice['cheque_term_id'] == $cheque_term['id'] ? $cheque_term['name']:'';
    
                        }
                    }
                ?>
                </td>
            </tr>
        </table>
        
        <?php
        
        if($invoice['invoiceNote']){
        ?>
            <table class="table">
                <tr>
                    <td colspan="4">
                        Note:
                        <pre><?= $invoice['invoiceNote'] ?></pre>
                    </td>
                </tr>
            </table>
        <?php
        }
        ?>
        
    
    </div>
    
    <?php
    
    }
    
    ?>
    
</body>

</html>
