<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../../404.phtml");
if (!isset($_GET["commit"]) || empty($_GET["commit"])) return require("../../404.phtml");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) return require("../../404.phtml");

$title = $repo["username"] . "/" . $repo["name"];
$css = ["repos", "diff", "highlight.js"];

$prev_commit = get_prev_commit($title, $_GET["commit"]);
$diff = get_diff($title, $prev_commit, $_GET["commit"]);

$diff = str_replace("\\", "\\" . "\\", $diff);
$diff = str_replace("`", "\`", $diff);

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("commit.phtml");
