<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['priceType'])) {
        if ($_POST['priceType'] == '1' || $_POST['priceType'] == '2') {

?>
            <div class="row mt-3 mb-3 mb-lg-5">
                <div class="col-12">
                    <h4 class="text-center">Billing Items</h4>
                    <table id="invoiceTable" style="width:100%; font-size: 10px !important;">
                        <thead>
                            <tr>
                                <th style="min-width: 40px !important;">#</th>
                                <th>Image</th>
                                <th>Item(s)</th>
                                <th>free qty</th>
                                <th>qty</th>
                                <th>Rate (Rs.)</th>
                                <th>Price (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class=" p-0 m-0" style="height: 8px !important;">
                                <td class="text-center p-0 m-0" colspan="7">
                                    <button class="btn btn-secondary w-100" onclick="viewAddProductModalInAddOrder();">Add Product</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr class="mt-2 mt-lg-4">

                <div class="col-12">
                    <h4 class="text-center">Return Items</h4>
                    <table id="returnItemTable" style="width:100%; font-size: 10px !important;">
                        <thead>
                            <tr>
                                <th style="min-width: 40px !important; background-color: #d10e00 !important;">#</th>
                                <th style="background-color: #d10e00 !important;">Image</th>
                                <th style="background-color: #d10e00 !important;">Item(s)</th>
                                <th style="background-color: #d10e00 !important;">qty</th>
                                <th style="background-color: #d10e00 !important;">Rate (Rs.)</th>
                                <th style="background-color: #d10e00 !important;">Price (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class=" p-0 m-0" style="height: 8px !important;">
                                <td class="text-center p-0 m-0" colspan="7">
                                    <button class="btn btn-secondary w-100" onclick="viewAddProductModalInAddReturnOrder();">Add Product</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-6 col-xl-5 mr-0 ml-auto mt-3" id="orderSummeryContent">

                </div>
            </div>

            <div class="row my-3">
                <div class="col-12 col-lg-2">
                    <span class="form-text-1">Note</span>
                </div>
                <div class="col-12 col-lg-10">
                    <textarea id="note" class="w-100 form-control" rows="5"></textarea>
                </div>
            </div>



            <div class="row mt-3">
                <div class="col-12 pt-5 pt-lg-0">
                    <button class="btn submit-btn w-100" onclick="AddOrderForm();">Add to System</button>
                </div>
            </div>
<?php
        }
    }
} else {
    echo "reload";
}
