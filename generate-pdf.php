<?php

require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require "config/connection.php";

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 600);
    set_time_limit(600);

 $sql = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
    FROM `invoice` 
    INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
    INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` 
    INNER JOIN `district` ON `district`.`id` = `city`.`district_id` 
    INNER JOIN `province` ON `province`.`id` = `district`.`province_id` 
    WHERE  `invoice`.`id`!='0' ";

    $sql2 = "SELECT *,`shop`.`name` AS `shop`,`invoice`.`id` AS `id`,`invoice`.`date_time` AS `date_time` 
    FROM `invoice` 
    INNER JOIN `shop` ON `shop`.`id` = `invoice`.`shop_id` 
    INNER JOIN `invoice_item` ON `invoice_item`.`invoice_id`=`invoice`.`id` 
    INNER JOIN `city` ON `city`.`id` = `shop`.`city_id` 
    INNER JOIN `district` ON `district`.`id` = `city`.`district_id` 
    INNER JOIN `province` ON `province`.`id` = `district`.`province_id` 
    WHERE  `invoice`.`id`!='0' ";

    $isProvinceOkay = false;
    $isDistrictOkay = false;

    if (isset($_POST['city'])) {
        if ($_POST['city'] != 0 && $_POST['city'] != '' && $_POST['city'] != null) {
            $sql .= " AND `city`.`id` = '" . $_POST['city'] . "' ";
        } else {
            $isDistrictOkay = true;
        }
    } else {
        $isDistrictOkay = true;
    }


    if ($isDistrictOkay) {
        if (isset($_POST['district'])) {
            if ($_POST['district'] != 0 && $_POST['district'] != '' && $_POST['district'] != null) {
                $sql .= " AND `district`.`id` = '" . $_POST['district'] . "' ";
            } else {
                $isProvinceOkay = true;
            }
        } else {
            $isProvinceOkay = true;
        }
    }

    if ($isProvinceOkay) {
        if (isset($_POST['province'])) {
            if ($_POST['province'] != 0 && $_POST['province'] != '' && $_POST['province'] != null) {
                $sql .= " AND `province`.`id` = '" . $_POST['province'] . "' ";
            }
        }
    }

    if ($_SESSION['user']['user_type'] != '4'){
        if (isset($_POST['user'])) {
            if ($_POST['user'] != '' && $_POST['user'] != null) {
                $sql .= " AND `invoice`.`user_id` = '" . $_POST['user'] . "' ";
            }
        }
    }

    if (isset($_POST['shop'])) {
        if ($_POST['shop'] != '' && $_POST['shop'] != null) {
            $sql .= " AND `shop`.`id` = '" . $_POST['shop'] . "' ";
        }
    }

    $isTextEmpty = false;

    if (isset($_POST['text'])) {
        if (trim($_POST['text']) != '' && $_POST['text'] != null) {
            $sql .= " AND (`shop`.`address` LIKE ? OR `city`.`name` LIKE ?  OR `district`.`name` LIKE ? OR `province`.`name` LIKE ?  OR `shop`.`name` LIKE ?  OR `invoice`.`order_id` LIKE ? ) ";
            $sql2 .= " AND (`shop`.`address` LIKE ? OR `city`.`name` LIKE ?  OR `district`.`name` LIKE ? OR `province`.`name` LIKE ?  OR `shop`.`name` LIKE ?  OR `invoice`.`order_id` LIKE ? ) ";
        } else {
            $isTextEmpty = true;
        }
    } else {
        $isTextEmpty = true;
    }

    if (isset($_POST['product'])) {
        if ($_POST['product'] != 0 && $_POST['product'] != '' && $_POST['product'] != null) {
            $sql2 .= " AND `invoice_item`.`product_id` = '" . $_POST['product'] . "' ";
        }
    }

    if (isset($_POST['payment'])) {
        if ($_POST['payment'] == '1' || $_POST['payment'] == '0') {
            $sql .= " AND `invoice`.`is_completed` = '" . $_POST['payment'] . "' ";
            $sql2 .= " AND `invoice`.`is_completed` = '" . $_POST['payment'] . "' ";
        }
    }

    if (isset($_POST['deliver'])) {
        if ($_POST['deliver'] == '1' || $_POST['deliver'] == '0') {
            $sql .= " AND `invoice`.`is_delivered` = '" . $_POST['deliver'] . "' ";
            $sql2 .= " AND `invoice`.`is_delivered` = '" . $_POST['deliver'] . "' ";
        }
    }

    if (isset($_POST['from'])) {
        $from = $_POST['from'] ?? null;
        $sql .= " AND `invoice`.`date_time` >= '$from 00:00:00'";
        $sql2 .= " AND `invoice`.`date_time` >= '$from 00:00:00'";
    }
    
    if (isset($_POST['to'])) {
        $to = $_POST['to'] ?? null;
        $sql .= " AND `invoice`.`date_time` <= '$to 23:59:59'";
        $sql2 .= " AND `invoice`.`date_time` <= '$to 23:59:59'";
    }

    $sql .= " AND `invoice`.`is_delete` = '0' ";
    $sql2 .= " AND `invoice`.`is_delete` = '0' ";

                
    if (isset($_POST['product'])) {
        if ($_POST['product'] != 0 && $_POST['product'] != '' && $_POST['product'] != null) {
            if ($isTextEmpty) {
                $order_rs = Database::search($sql2 . " ORDER BY `invoice`.`date_time` DESC LIMIT 300");
            } else {
                $order_rs = Database::search($sql2 . " ORDER BY `invoice`.`date_time` DESC LIMIT 300", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
            }
        } else {
            if ($isTextEmpty) {
                $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC LIMIT 300");
            } else {
                $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC LIMIT 300", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
            }
        }
    } else {
        if ($isTextEmpty) {
            $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC LIMIT 300");
        } else {
            $order_rs = Database::search($sql . " GROUP BY `invoice`.`id`  ORDER BY `invoice`.`date_time` DESC LIMIT 300", "ssssss", ['%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%', '%' . $_POST['text'] . '%']);
        }
    }
    
    
if($order_rs->num_rows <= 300){
    $dompdf = new Dompdf();
    $dompdf->set_option('isHtml5ParserEnabled', true);
    $dompdf->set_option('isPhpEnabled', true);
    $dompdf->set_option('isRemoteEnabled', true);
    
    ob_start();
    include 'order-pdf.php';
    $html = ob_get_clean();
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("document.pdf", ["Attachment" => false]);

}

