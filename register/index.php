<?php
require("../session.php");
require("../db.php");

require("../fns/user.php");
require("../fns/files.php");

$title = "Register";
$css = ["login"];

if ($logged_in) {
    header("Location: /");
}

$status = "";

if (isset($_POST["password"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && !empty($_POST["username"])) {
    if (user_exists($db, $_POST["username"])) {
        $status = "username {$_POST["username"]} is unavailable";
    } else if ($_POST["password"] != $_POST["password2"]) {
        $status = "passwords do not match";
    } else {

        $success_reg = register($db, $_POST["username"], $_POST["password"]);

        if ($success_reg) {
            $success_log = login($db, $_POST["username"], $_POST["password"]);

            gen_profile_picture($_POST["username"]); 

            if ($success_log) header("Location: /");
            else $status = "invalid username or password";
        }
        else $status = "something went wrong";
    }
}

require("register.phtml");
