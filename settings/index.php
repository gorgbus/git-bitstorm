<?php
require("../db.php");;
require("../session.php");

require("../fns/user.php");
require("../fns/files.php");

require("../user.php");

if (!$logged_in) header("Location: /");

$title = "Settings";
$css = ["settings"];

$max_file_size = 1024 * 1024;
$allowed_types = ["image/png", "image/jpeg", "image/webp"];
$pswd_status = "";
$img_status = "";

$profile_pic = get_profile_picture($user["username"]);

if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] == 2) $img_status = "Please upload images under 1MB";

if (isset($_FILES["picture"]) && in_array($_FILES["picture"]["type"], $allowed_types)) {
    save_profile_picture($user["username"], $_FILES["picture"]);

    header("Location: /settings");
    exit;
}

if (isset($_POST["name"]) && !empty($_POST["name"])) {
    change_user_name($user["username"], $_POST["name"]);; 

    header("Location: /settings");
    exit;
}

if (isset($_POST["old_password"])) {
    if (login($user["username"], $_POST["old_password"])) {
        if ($_POST["new_password"] == $_POST["confirm_password"]) {
            change_user_password($user["username"], $_POST["new_password"]); 

            header("Location: /settings");
            exit;
        } else $pswd_status = "Password do not match";
    } else $pswd_status = "Incorrect password";
}

require("settings.phtml");
