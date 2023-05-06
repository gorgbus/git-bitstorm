<?php
require("../session.php");
require("../db.php");

require("../fns/git.php");
require("../fns/repo.php");
require("../fns/files.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../404.phtml");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) return require("../404.phtml");

$title = $repo["username"] . "/" . $repo["name"];

$commit = get_latest_commit($title);
$path = "";
$commit_count = get_commit_count($title, $commit);

$commit_query = "";

$files = get_tree($title, $commit, $path);

require("repo.phtml");
