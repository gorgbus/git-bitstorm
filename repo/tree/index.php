<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../../404.phtml");
if (!isset($_GET["commit"]) || empty($_GET["commit"])) return require("../../404.phtml");
if (!isset($_GET["path"])) return require("../../404.phtml");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) return require("../../404.phtml");

$title = $repo["username"] . "/" . $repo["name"];
$css = ["repos"]; 

$path = $_GET["path"] . "/";
$commit = $_GET["commit"];
$commit_count = get_commit_count($title, $commit);

if (empty($_GET["path"])) $path = "";

$commit_query = "&commit=$commit";
$latest = false;

$files = get_tree($title, $commit, $path);

require("tree.phtml");
