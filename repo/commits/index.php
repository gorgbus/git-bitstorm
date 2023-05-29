<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");

require("../repo.php");

$css = ["repos", "commits"];

$commit = get_latest_commit($title);
$page = 1;
$page_size = 35;

$commits_url = "name={$repo["name"]}";

if (isset($_GET["commit"]) && !empty($_GET["commit"])) {
    require("../commit.php");

    $commit = $_GET["commit"];

    $commits_url = $commits_url . "&commit=$commit";
}

if (isset($_GET["page"]) && !empty($_GET["page"])) $page = (int) $_GET["page"];

$commit_count = get_commit_count($title, $commit);

if ($page < 1) return require("../../404.phtml");

if (ceil($commit_count / $page_size) < $page && 1 < $page) return require("../../404.phtml");

$last_page = ceil($commit_count / $page_size) < $page + 1 && 1 < $page + 1;

$commits = get_commits($title, $commit, $page, $page_size);

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

require("commits.phtml");
