<?php
require("db.php");
require("session.php");

require("fns/user.php");
require("fns/repo.php");
require("fns/git.php");

require("user.php");

$title = "BitStorm";
$css = ["home"];

$repos;

if ($logged_in) {
    $repos = get_repos($db, $user["id"], $user["id"]);

    $repos = get_latest_changes($repos);
}

require("home.phtml");
