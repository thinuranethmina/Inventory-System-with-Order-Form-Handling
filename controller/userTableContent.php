<?php

require "util/userStatus.php";

if (User::is_allow()) {

    $all_users_rs = Database::search("SELECT * FROM `user` WHERE `id` != '1' ");


    $sql = "SELECT *,`user`.`id` AS `id` FROM `user` WHERE `id`!= '1' ";

    if (isset($_POST['text'])) {
        if (!empty($_POST['text'])) {

            $sql .= "AND ( `name` LIKE '%" . trim($_POST['text']) . "%' OR `nic` LIKE '%" . trim($_POST['text']) . "%' OR `address` LIKE '%" . trim($_POST['text']) . "%'  OR `mobile` LIKE '%" . trim($_POST['text']) . "%') ";
        }
    }



    $users_rs = Database::search($sql . " ORDER BY  `user`.`date_time` DESC ");

?>

    <div class="row">
        <div class="col-12 d-none d-md-block">
            <span class="text-white f-w-300">Showing <?= $users_rs->num_rows ?> of <?= $all_users_rs->num_rows ?> entries</span>
        </div>
    </div>

    <table class="" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th class="d-none d-md-table-cell">Profile Image</th>
                <th>NIC</th>
                <th>Name</th>
                <th>User Type</th>
                <th class="d-none d-md-table-cell text-center">Status</th>
                <th class=" text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php


            if ($users_rs->num_rows <= 0) {
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
            while ($user = $users_rs->fetch_assoc()) {

            ?>

                <tr>
                    <td>
                        <?= $x ?>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <img src="<?= $user['profile_image'] ?>" class="table-main-image">
                    </td>
                    <td> <?= $user['nic'] ?></td>
                    <td> <?= $user['name'] ?></td>
                    <td> <?php
                            if ($user['user_type_id'] == '2') {
                                echo "ADMIN";
                            } else if ($user['user_type_id'] == '3') {
                                echo "SALES MANAGER";
                            } else if ($user['user_type_id'] == '4') {
                                echo "SELLER";
                            }  ?></td>
                    <td class="d-none d-md-table-cell text-center" id="status<?= $user['id'] ?>">
                        <?= $user['status_id'] == 1 ? '<div class="status status-active mx-auto">Active</div>' : '<div class="status status-deactive mx-auto">Deactive</div>' ?>
                    </td>
                    <td class=" text-center" style="min-width: 106px;">
                        <div class="check-box2 p-2 d-inline-block mr-1 z-0">
                            <input class="z-0" id="toggleStatus" onchange="changeStatus('User',<?= $user['id'] ?>);" type="checkbox" <?= $user['status_id'] == 1 ? "checked" : "" ?>>
                        </div>
                        <img class="mr-1 action-icon" onclick="viewModal('<?= $user['id'] ?>','user');" src="assets/images/icons/view.png">
                        <a href="update-user.php?id=<?= $user['id'] ?>" target="_blank"><img class="action-icon" src="assets/images/icons/edit.png"></a>
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
