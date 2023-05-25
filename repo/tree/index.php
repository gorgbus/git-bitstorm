<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

if (!isset($_GET["commit"]) || empty($_GET["commit"])) return require("../../404.phtml");
if (!isset($_GET["path"])) return require("../../404.phtml");

require("../repo.php");

$css = ["repos"]; 

$path = $_GET["path"] . "/";
$commit = $_GET["commit"];
$commit_count = get_commit_count($title, $commit);

if (empty($_GET["path"])) $path = "";

$commit_query = "&commit=$commit";
$latest = false;

$files = get_tree($title, $commit, $path);

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("tree.phtml");
