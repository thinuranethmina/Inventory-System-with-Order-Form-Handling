<?php

require "util/userStatus.php";

if (User::is_allow()) {


    if ($_SESSION['user']['user_type'] > 2) {
        $all_product_rs = Database::search("SELECT * FROM `product` WHERE `status_id` = '1' ");
    } else {
        $all_product_rs = Database::search("SELECT * FROM `product` ");
    }


    $sql = "SELECT *,`product`.`id` AS `id` FROM `product` ";

    if (isset($_POST['text'])) {
        if (!empty($_POST['text'])) {

            $sql .= " WHERE `title` LIKE '%" . trim($_POST['text']) . "%' OR `model_no` LIKE '%" . trim($_POST['text']) . "%' ";
        }
    }



    $product_rs = Database::search($sql . " ORDER BY  `product`.`date_time` DESC ");

?>

    <div class="row">
        <div class="col-12 d-none d-md-block">
            <span class="text-white f-w-300">Showing <?= $product_rs->num_rows ?> of <?= $all_product_rs->num_rows ?> entries</span>
        </div>
    </div>

    <table class="" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th class="d-none d-md-table-cell">Image</th>
                <th>Model No</th>
                <th>Credit Price</th>
                <th class="d-none d-md-table-cell">Cash Price</th>
                <?php
                if ($_SESSION['user']['user_type'] <= 2) {
                ?>
                    <th class="d-none d-md-table-cell text-center">Status</th>
                <?php
                }
                ?>
                <th class=" text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if ($product_rs->num_rows <= 0) {
            ?>
                <tr>
                    <td class="text-center rounded-3" colspan="7">
                        <h4 class="m-auto">No Result</h4>
                    </td>
                </tr>
            <?php
            }

            $x = 1;
            $date = null;
            while ($product = $product_rs->fetch_assoc()) {

                $price = Database::search("SELECT * FROM `price` WHERE `product_id` = ? ORDER BY `date_time` DESC ", "s", [$product['id']])->fetch_assoc();
            ?>

                <tr>
                    <td>
                        <?= $x ?>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <img src="<?= $product['cover_image'] ?>" class="table-main-image">
                    </td>
                    <td> <?= $product['model_no'] ?></td>
                    <td>
                        Rs.<?= number_format($price['credit_price']) ?>/=
                    </td>
                    <td class="d-none d-md-table-cell">
                        Rs.<?= number_format($price['cash_price']) ?>/=
                    </td>
                    <?php
                    if ($_SESSION['user']['user_type'] <= 2) {
                    ?>
                        <td class="d-none d-md-table-cell text-center" id="status<?= $product['id'] ?>">
                            <?= $product['status_id'] == 1 ? '<div class="status status-active mx-auto">Active</div>' : '<div class="status status-deactive mx-auto">Deactive</div>' ?>
                        </td>
                    <?php
                    }
                    ?>
                    <td class=" text-center" style="min-width: 106px;">
                        <?php
                        if ($_SESSION['user']['user_type'] <= 2) {
                        ?>
                            <div class="check-box2 p-2 d-inline-block mr-1 z-0">
                                <input class="z-0" id="toggleStatus" onchange="changeStatus('Product',<?= $product['id'] ?>);" type="checkbox" <?= $product['status_id'] == 1 ? "checked" : "" ?>>
                            </div>
                            <img class="mr-1 action-icon" onclick="viewModal('<?= $product['id'] ?>','product');" src="assets/images/icons/view.png">
                            <a href="update-product.php?pid=<?= $product['id'] ?>" target="_blank"><img class="action-icon" src="assets/images/icons/edit.png"></a>

                        <?php
                        } else {
                        ?>
                            <img class="mr-1 action-icon" onclick="viewModal('<?= $product['id'] ?>','product');" src="assets/images/icons/view.png">
                        <?php
                        }

                        ?>
                    </td>
                </tr>

            <?php

                $x++;
            }


            ?>
        </tbody>
    </table>

<?php

} else {
    echo "reload";
}
