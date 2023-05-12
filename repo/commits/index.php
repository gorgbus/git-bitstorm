<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../../404.phtml");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) return require("../../404.phtml");

$title = $repo["username"] . "/" . $repo["name"];
$css = ["repos", "commits"];

$commit = get_latest_commit($title);
$page = 1;

if (isset($_GET["commit"]) && !empty($_GET["commit"])) $commit = $_GET["commit"];
if (isset($_GET["page"]) && !empty($_GET["page"])) $page = (int) $_GET["page"];

$commits = get_commits($title, $commit, $page);

require("commits.phtml");