<?php
require("../session.php");
require("../db.php");

require("../fns/git.php");
require("../fns/repo.php");
require("../fns/files.php");

require("repo.php");

$css = ["repos"];

$commit = get_latest_commit($title);
$path = "";
$commit_count = get_commit_count($title, $commit);

$commit_query = "";
$latest = true;

$files = get_tree($title, $commit, $path);

if (empty($files)) header("Location: /repo/upload?name={$repo["name"]}");

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("repo.phtml");
