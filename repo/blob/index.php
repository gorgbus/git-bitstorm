<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../../404.phtml");
if (!isset($_GET["commit"]) || empty($_GET["commit"])) return require("../../404.phtml");
if (!isset($_GET["path"]) || empty($_GET["path"])) return require("../../404.phtml");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) return require("../../404.phtml");

$title = $repo["username"] . "/" . $repo["name"];
$css = ["repos", "highlight.js"];

$commit = $_GET["commit"];
$path = $_GET["path"];

$file = get_file($title, $commit, $path);

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("blob.phtml");
