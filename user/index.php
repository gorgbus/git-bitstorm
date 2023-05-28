<?php
require("../session.php");
require("../db.php");

require("../fns/user.php");
require("../fns/repo.php");
require("../fns/git.php");

$title = "404";

require("../user.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../404.phtml");

$current_user = get_user($db, $_GET["name"]);

if (!$current_user) return require("../404.phtml");

$repos = get_repos($db, $current_user["id"], ($logged_in) ? $user["id"] : -1);
$repos = get_latest_changes($repos);

$title = $current_user["username"];
$css = ["home", "user"];

require("user.phtml");
