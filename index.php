<?php
require("db.php");
require("session.php");

require("fns/user.php");
require("fns/repo.php");

$title = "Home";
$css = ["home"];

$repos;
$user;

if ($logged_in) {
    $repos = get_repos($db, $_SESSION["user"], $_SESSION["user"]);
    $user = get_user($db, $_SESSION["user"]);
}

require("home.phtml");
