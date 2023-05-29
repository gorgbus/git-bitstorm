<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

require("../repo.php");
require("../commit.php");
require("../path.php");

$css = ["repos", "highlight.js"];

$commit = $_GET["commit"];
$path = $_GET["path"];

$file = get_file($title, $commit, $path);

$latest = false;

if ($commit == get_latest_commit($title)) $latest = true;

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("blob.phtml");
