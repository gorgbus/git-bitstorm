<?php
require(__DIR__ . "/../fns/user.php");
require(__DIR__ . "/../user.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) {
    require(__DIR__ . "/../404.phtml");
    exit;
} 

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) {
    require(__DIR__ . "/../404.phtml");
    exit;
} 


$title = $repo["username"] . "/" . $repo["name"];