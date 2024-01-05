<?php

require "util/userStatus.php";

if (User::allowOnly([User::SUPER_ADMIN, User::ADMIN])) {
    if (isset($_POST['id'])) {

        if ($_POST['id'] == '0') {
?>

            <div class="row mb-5 text-center" id="shopPreview">

            </div>

            <div class="row my-3">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Shop</span>
                </div>
                <div class="col-12 col-lg-9">
                    <select name="" id="shop" onchange="updateSendSMSShopViewer();" class="form-control">
                        <option value="0">Select Shop</option>
                        <?php
                        $shop_rs = Database::search("SELECT * FROM `shop` WHERE `id` != '0' AND `shop`.`mobile` != '' AND `status_id`='1' ORDER BY `name` ASC");

                        while ($shop = $shop_rs->fetch_assoc()) {
                        ?>
                            <option value="<?= $shop['id'] ?>"><?= $shop['name'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Sender Id</span>
                </div>
                <div class="col-12 col-lg-9">
                    <select name="" id="send_id" onchange="updateStockProductViewer();" class="form-control">
                        <option value="0">Select Sender Id</option>
                        <?php
                        $shop_rs = Database::search("SELECT * FROM `message_send_id` ORDER BY `name` ASC");

                        while ($shop = $shop_rs->fetch_assoc()) {
                        ?>
                            <option value="<?= $shop['id'] ?>"><?= $shop['name'] ?></option>
                        <?php
                        }

                        ?>
                    </select>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Template</span>
                </div>
                <div class="col-12 col-lg-9">
                    <select name="" onchange="updateSendSMSTemplate(this);" class="form-control">
                        <option value="0">No Template</option>
                        <?php
                        $template_rs = Database::search("SELECT * FROM `message_template` ORDER BY `name` ASC");

                        while ($template = $template_rs->fetch_assoc()) {
                        ?>
                            <option value="<?= $template['id'] ?>"><?= $template['name'] ?></option>
                        <?php
                        }

                        ?>
                    </select>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12 col-lg-3">
                    <span class="form-text-1">Text Message</span>
                </div>
                <div class="col-12 col-lg-9">
                    <textarea id="msg" class="w-100 form-control" rows="10"></textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 pt-5 pt-lg-0">
                    <button class="btn submit-btn w-100" onclick="sendSMS();">Send Now</button>
                </div>
            </div>


        <?php

        } else if ($_POST['id'] == '1') {
        ?>


            <div class="row my-3 mt-5">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Phone</span>
                </div>
                <div class="col-12 col-lg-9">
                    <input type="text" id="phone" class="form-control" placeholder="0xxxxxxxxx" />
                </div>
            </div>
            <div class="row my-3">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Sender Id</span>
                </div>
                <div class="col-12 col-lg-9">
                    <select id="send_id" onchange="updateStockProductViewer();" class="form-control">
                        <option value="0">Select Sender Id</option>
                        <?php
                        $shop_rs = Database::search("SELECT * FROM `message_send_id` ORDER BY `name` ASC");

                        while ($shop = $shop_rs->fetch_assoc()) {
                        ?>
                            <option value="<?= $shop['id'] ?>"><?= $shop['name'] ?></option>
                        <?php
                        }

                        ?>
                    </select>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12 col-lg-3 my-auto">
                    <span class="form-text-1">Template</span>
                </div>
                <div class="col-12 col-lg-9">
                    <select name="" onchange="updateSendSMSTemplate(this);" class="form-control">
                        <option value="0">No Template</option>
                        <?php
                        $template_rs = Database::search("SELECT * FROM `message_template` ORDER BY `name` ASC");

                        while ($template = $template_rs->fetch_assoc()) {
                        ?>
                            <option value="<?= $template['id'] ?>"><?= $template['name'] ?></option>
                        <?php
                        }

                        ?>
                    </select>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12 col-lg-3">
                    <span class="form-text-1">Text Message</span>
                </div>
                <div class="col-12 col-lg-9">
                    <textarea id="msg" class="w-100 form-control" rows="10"></textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 pt-5 pt-lg-0">
                    <button class="btn submit-btn w-100" onclick="sendMobileSMS();">Send Now</button>
                </div>
            </div>


<?php
        }
    }
} else {
    echo "reload";
}
?>