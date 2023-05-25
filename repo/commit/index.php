<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");

if (!isset($_GET["commit"]) || empty($_GET["commit"])) return require("../../404.phtml");

require("../repo.php");

$css = ["repos", "diff", "highlight.js"];

$prev_commit = get_prev_commit($title, $_GET["commit"]);
$diff = get_diff($title, $prev_commit, $_GET["commit"]);

$diff = str_replace("\\", "\\" . "\\", $diff);
$diff = str_replace("`", "\`", $diff);

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("commit.phtml");
