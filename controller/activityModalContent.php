<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_POST['id'])) {

        $activity_rs = Database::search("SELECT * FROM `activity` INNER JOIN `activity_has_viewer` ON `activity_has_viewer`.`activity_id`=`activity`.`id` WHERE `activity`.`id` = ? AND `activity_has_viewer`.`viewer_id`= ?", "ss", [$_POST['id'], $_SESSION['user']['id']]);

        if ($activity_rs->num_rows == 1) {
            $activity = $activity_rs->fetch_assoc();

            $user;

            if ($activity['user_id'] == $_SESSION['user']['id']) {
                $user = "<b>You</b>";
            } else {
                $user = "<b>" . $activity['name'] . "</b>";
            }

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
                                <span class="my-auto"><?= $user . " " . $activity['message'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal--footer d-flex text-secondary">
                        <span class="text-start ms-0 my-auto"> Date & Time:<span class="fst-italic text-secondary"> <?= date('Y-m-d h:i:s A', strtotime($activity['date_time'])) ?></span>
                        </span>
                        <div class="ml-auto float-right" style="min-width: fit-content;">
                            <button class="btn  close-btn" onclick="closeModal1();">close</button>
                            <?php

                            if ($activity['href'] != '') {
                            ?>
                                <button class="btn btn-primary">View</button>
                            <?php
                            }
                            ?>
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
                            <button class="btn  close-btn" onclick="closeModal1();">close</button>
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
    echo "reload";
}
