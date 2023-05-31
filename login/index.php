<?php
require("../session.php");
require("../db.php");

require("../fns/user.php");

$title = "Login";
$css = ["login"];
$status = "";

if ($logged_in) {
    header("Location: /");
}

if (isset($_POST["password"]) && !empty($_POST["password"]) && !empty($_POST["username"])) {
    $success = login($_POST["username"], $_POST["password"]);

    if ($success) header("Location: /");
    else $status = "invalid username or password";
}

require("login.phtml");
