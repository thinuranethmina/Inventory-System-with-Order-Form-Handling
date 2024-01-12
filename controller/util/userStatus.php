<?php


class User
{

    public const SUPER_ADMIN = 1;
    public const ADMIN = 2;
    public const SALES_MANAGER = 3;
    public const SELLER = 4;

    public static function is_allow()
    {
        $cookie_lifetime = 60 * 60 * 24; // 24 hours in seconds
        session_set_cookie_params($cookie_lifetime);
        ini_set('session.gc_maxlifetime', $cookie_lifetime);
        session_start();

        date_default_timezone_set('Asia/Kolkata');

        require "..//config/connection.php";

        if (isset($_SESSION['user']['id'])) {

            $user_resultset = Database::search("SELECT * FROM `user` WHERE `id`=? ", "s", [$_SESSION['user']['id']]);

            if ($user_resultset->num_rows == 1) {
                $user_status_id = $user_resultset->fetch_assoc()['status_id'];

                if ($user_status_id == '1') {
                    return true;
                } else {
                    $_SESSION["user"] = null;
                    session_destroy();
                    return false;
                }
            } else {
                $_SESSION["user"] = null;
                session_destroy();
                return false;
            }
        } else {
            return false;
        }
    }

    public static function allowOnly($userTypes)
    {

        session_set_cookie_params(60 * 60 * 8);
        session_start();

        date_default_timezone_set('Asia/Kolkata');

        require "..//config/connection.php";

        if (isset($_SESSION['user']['id'])) {

            $query = "SELECT * FROM `user` WHERE `id`=?  ";

            $arrayLength = count($userTypes);

            for ($i = 0; $i < $arrayLength; $i++) {
                if ($i == 0) {
                    $query .= " AND ( ";
                } else {
                    $query .= " OR ";
                }

                $query .= " `user_type_id` = '" . $userTypes[$i] . "' ";

                if ($i == $arrayLength - 1) {
                    $query .= " ) ";
                }
            }

            $user_resultset = Database::search($query, "s", [$_SESSION['user']['id']]);


            if ($user_resultset->num_rows == 1) {
                $user_status_id = $user_resultset->fetch_assoc()['status_id'];

                if ($user_status_id == '1') {
                    return true;
                } else {
                    $_SESSION["user"] = null;
                    session_destroy();
                    return false;
                }
            } else {
                $_SESSION["user"] = null;
                session_destroy();
                return false;
            }
        } else {
            return false;
        }
    }
}
