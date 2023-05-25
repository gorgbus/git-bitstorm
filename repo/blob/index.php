<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

if (!isset($_GET["commit"]) || empty($_GET["commit"])) return require("../../404.phtml");
if (!isset($_GET["path"]) || empty($_GET["path"])) return require("../../404.phtml");

require("../repo.php");

$css = ["repos", "highlight.js"];

$commit = $_GET["commit"];
$path = $_GET["path"];

$file = get_file($title, $commit, $path);

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("blob.phtml");
