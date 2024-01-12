<?php

require "util/userStatus.php";

if (User::is_allow()) {

    if (isset($_FILES['image'])) {

        $fileex = $_FILES['image']["type"];
        $allowed_image_extention = array("image/jpg", "image/jpeg", "image/png",);

        if (in_array($fileex, $allowed_image_extention)) {

            $image_info = getimagesize($_FILES["image"]["tmp_name"]);
            $image_width = $image_info[0];
            $image_height = $image_info[1];


            if ($_FILES['image']["size"] < 2000000) {
                echo "success";
            } else {
                echo  "Image size must be less than 2MB";
            }
        } else {
            echo 'Sorry, only JPG, JPEG, and PNG files are allowed to upload.';
        }
    } else {
        echo 'Not uploaded image.';
    }
} else {
    echo "reload";
}
