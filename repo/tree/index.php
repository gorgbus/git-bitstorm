<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

if (!isset($_GET["path"])) return require("../../404.phtml");

require("../repo.php");
require("../commit.php");

$css = ["repos"]; 

$path = $_GET["path"] . "/";
$commit = $_GET["commit"];
$commit_count = get_commit_count($title, $commit);

$commit_query = "&commit=$commit";
$latest = false;

if ($commit == get_latest_commit($title)) $latest = true;

if (empty($_GET["path"])) {
    $path = "";
    $commit_info = get_commit_info($title, $commit); 
} else require("../path.php");

$files = get_tree($title, $commit, $path);

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("tree.phtml");
