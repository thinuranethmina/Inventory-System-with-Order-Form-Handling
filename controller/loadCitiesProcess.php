<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['id'])) {

        $city_rs = Database::search("SELECT * FROM `city` WHERE `district_id` = ? ORDER BY `name` ASC ", "s", [$_POST['id']]);
?>
        <option value="0">Select City</option>
        <?php
        while ($city = $city_rs->fetch_assoc()) {
        ?>
            <option value="<?= $city['id'] ?>"><?= $city['name'] ?></option>
<?php
        }
    }
} else {
    echo "reload";
}
