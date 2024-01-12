<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (!isset($_POST['city'])) {
        echo "Please select city";
    } else if (empty(trim($_POST['city']))) {
        echo "Please select city";
    } else {

        $isOkay = true;

        if (isset($_POST['cityList'])) {

            $arrayList = $_POST['cityList'];

            foreach ($arrayList as $city) {

                if ($city == $_POST['city']) {
                    echo "Already exist this location";
                    $isOkay = false;
                    break;
                }
            }
        }


        if ($isOkay) {
            $city_rs = Database::search("SELECT *,`city`.`name` AS `city`, `district`.`name` AS `district`  FROM `city` INNER JOIN `district` ON `district`.`id`=`city`.`district_id` WHERE `city`.`id` = ? ", "s", [$_POST['city']]);

            if ($city_rs->num_rows == 1) {
                $location = $city_rs->fetch_assoc();
?>
                <div class="border border-1 ml-3 border-secondary px-3 py-2 rounded rounded-3">
                    <?= $location['district'] ?> district -> <?= $location['city'] ?>
                </div>
<?php
            } else {
                echo "Already exist this location";
            }
        }
    }
} else {
    echo "reload";
}
