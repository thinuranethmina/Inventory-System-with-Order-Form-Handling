<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['id'])) {

        $user_rs = Database::search("SELECT *,`user`.`id` AS `id`,  `city`.`name` AS `city`, `district`.`name` AS `district`,`province`.`name` AS `province`  FROM `user` INNER JOIN `city` ON `city`.`id` = `user`.`city_id` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` INNER JOIN `province` ON `province`.`id` = `district`.`province_id` WHERE `user`.`id`=? ", "s", [$_POST['id']]);

        if ($user_rs->num_rows == 1) {
            $user = $user_rs->fetch_assoc();
?>
            <div id="content-modal-1" class="col-11 col-md-10 col-lg-6 mx-auto modal--content show-modal">
                <div class="row">
                    <div class="modal--header">
                        <div class="d-flex ">
                            <h2 class="my-auto "><?php
                                                    if ($user['user_type_id'] == '2') {
                                                        echo "Admin";
                                                    } else if ($user['user_type_id'] == '3') {
                                                        echo "Sales Manager";
                                                    } else if ($user['user_type_id'] == '4') {
                                                        echo "Seller";
                                                    }  ?>
                            </h2>
                            <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                        </div>
                        <hr class="text-dark my-2">
                    </div>
                    <div class="modal--body p-3">

                        <div class="row my-3">
                            <div class="col-12 text-center">
                                <img class="rounded rounded-circle border border-3 settings-profile-img bg-white" src="<?= $user['profile_image'] ?>" alt="Profile Image">
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Name:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $user['name'] ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">NIC:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $user['nic'] ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">User Type:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?php
                                        if ($user['user_type_id'] == '2') {
                                            echo "Admin";
                                        } else if ($user['user_type_id'] == '3') {
                                            echo "Sales Manager";
                                        } else if ($user['user_type_id'] == '4') {
                                            echo "Seller";
                                        }  ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Mobile:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $user['mobile'] ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Address:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= $user['address'] . ", " . $user['city'] . ", " . $user['district'] . " district, " . $user['province'] . " province." ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Status:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?php
                                        if ($user['status_id'] == '1') {
                                            echo "Active";
                                        } else {
                                            echo "Deactive";
                                        } ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3">
                            <div class="col-12 col-lg-3 my-auto">
                                <h6 class="form-text-1">Add Date & Time:</h6>
                            </div>
                            <div class="col-12 col-lg-9">
                                <span><?= date('Y-m-d h:i:s A', strtotime($user['date_time'])) ?></span>
                            </div>
                        </div>
                        <hr>
                        <div class="row my-3 px-3 text-center">
                            <div class="col-12 col-md-6 py-4 px-1">
                                <p>NIC Front side</p>
                                <img src="<?= $user['nic_front'] ?>" style="max-width: 270px;">
                            </div>
                            <div class="col-12 col-md-6 py-4 px-1">
                                <p>NIC Back side</p>
                                <img src="<?= $user['nic_back'] ?>" style="max-width: 270px;">
                            </div>
                        </div>


                    </div>
                    <div class="modal--footer d-sm-flex text-secondary">
                        <div class="ml-auto float-right p-2">
                            <button class="btn close-btn" onclick="closeModal1();">close</button>
                        </div>

                    </div>
                </div>
            </div>
        <?php
        } else {

        ?>

            <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">
                <div class="row">
                    <div class="modal--header">
                        <div class="d-flex ">
                            <h2 class="my-auto ">Activity</h2>
                            <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                        </div>
                        <hr class="text-dark my-2">
                    </div>
                    <div class="modal--body">
                        <div class="row p-3 text-center">
                            <div class="col-12">
                                <span class="my-auto">Unexpected Error.</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal--footer d-sm-flex text-secondary">
                        <div class="ml-auto float-right p-2">
                            <button class="btn close-btn" onclick="closeModal1();">close</button>
                        </div>

                    </div>
                </div>
            </div>
        <?php
        }
    } else {

        ?>

        <div id="content-modal-1" class="col-10 col-md-8 col-lg-6 col-xl-4 mx-auto modal--content show-modal">
            <div class="row">
                <div class="modal--header">
                    <div class="d-flex ">
                        <h2 class="my-auto ">User</h2>
                        <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
                    </div>
                    <hr class="text-dark my-2">
                </div>
                <div class="modal--body">
                    <div class="row p-3 text-center">
                        <div class="col-12">
                            <span class="my-auto">Unexpected Error.</span>
                        </div>
                    </div>
                </div>
                <div class="modal--footer d-sm-flex text-secondary">
                    <div class="ml-auto float-right p-2">
                        <button class="btn close-btn" onclick="closeModal1();">close</button>
                    </div>

                </div>
            </div>
        </div>
<?php
    }
} else {
    echo "reload";
}
