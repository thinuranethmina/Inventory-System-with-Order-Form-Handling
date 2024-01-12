<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['id'])) {

        $district_rs = Database::search("SELECT * FROM `district` WHERE `province_id` = ? ORDER BY `name` ASC ", "s", [$_POST['id']]);
?>
        <option value="0">Select District</option>
        <?php
        while ($district = $district_rs->fetch_assoc()) {
        ?>
            <option value="<?= $district['id'] ?>"><?= $district['name'] ?></option>
<?php
        }
    }
} else {
    echo "reload";
}
