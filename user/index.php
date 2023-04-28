<?php
require("../session.php");
require("../db.php");

require("../fns/user.php");
require("../fns/repo.php");

$title = "404";

if (!isset($_GET["id"]) || empty($_GET["id"])) return require("../404.phtml");

$user = get_user($db, $_GET["id"]);

if (!$user) return require("../404.phtml");

$repos = get_repos($db, $_GET["id"], ($logged_in) ? $_SESSION["user"] : -1);

$title = $user["username"];
$css = ["repos"];

require("user.phtml");