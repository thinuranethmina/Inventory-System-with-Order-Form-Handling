<?php

require "util/userStatus.php";

$response = new stdClass();

$response->status = "reload";
        
if (User::is_allow()) {

    if (!isset($_POST['priceType'])) {
        
    } else if (empty(trim($_POST['priceType'])) || $_POST['priceType'] == '0') {
        
    } elseif (!isset($_POST['rows'])) {
        
    } else {
        
        $response->status = "success";
        $response->products = [];
        
        for ($i = 1; $i < $_POST['rows']; $i++) {
            $product_rs =  Database::search("SELECT * FROM `product` WHERE `id` = ? AND `status_id` = '1' ", "s", [$_POST['p' . $i]]);
    
            $price_rs = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$_POST['p' . $i]]);
    
            if ($product_rs->num_rows == 1) {
                $product = $product_rs->fetch_assoc();
                $price = $price_rs->fetch_assoc();
                
                $inv_product = new stdClass();
            
                if ($_POST['priceType'] == '1') {
                    $inv_product->id = $_POST['p' . $i];
                    $inv_product->row = $i;
                    $inv_product->price = $price['cash_price'];
                    
                } else if ($_POST['priceType'] == '2') {
                    
                    $inv_product->id = $_POST['p' . $i];
                    $inv_product->row = $i;
                    $inv_product->price = $price['credit_price'];
                }
                    
                $response->products[] = $inv_product;
                
            }
        }
        
                
        

    }
    
    header('Content-Type: application/json');
        echo json_encode($response);
}

?>