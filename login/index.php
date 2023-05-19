<?php
require("../session.php");
require("../db.php");

require("../fns/user.php");

$title = "Login";
$css = ["login"];

if ($logged_in) {
    header("Location: /");
}

if (isset($_POST["password"]) && !empty($_POST["password"]) && !empty($_POST["username"])) {
    $success = login($db, $_POST["username"], $_POST["password"]);

    if ($success) header("Location: /");
    else echo "invalid username or password";
}

require("login.phtml");
