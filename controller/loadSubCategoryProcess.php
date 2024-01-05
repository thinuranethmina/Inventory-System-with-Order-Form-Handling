<?php

require "util/userStatus.php";

if (User::is_allow()) {
    if (isset($_POST['id'])) {

        $sub_category_rs = Database::search("SELECT * FROM `sub_category` WHERE `category_id` = ? ORDER BY `name` ASC ", "s", [$_POST['id']]);
?>
        <?php
        if ($sub_category_rs->num_rows > 0) {

        ?>
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-3 my-auto">
                        <span class="form-text-1">Sub Category</span>
                    </div>
                    <div class="col-12 col-lg-9">
                        <select id="subCategory" class="form-control">

                            <option value="0">Select Sub Category</option>
                            <?php
                            while ($sub_category = $sub_category_rs->fetch_assoc()) {
                            ?>
                                <option value="<?= $sub_category['id'] ?>"><?= $sub_category['name'] ?></option>
                            <?php
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>
<?php
        }
    }
} else {
    echo "reload";
}
