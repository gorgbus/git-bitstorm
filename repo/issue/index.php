<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

require("../repo.php");

if (!isset($_GET["id"]) || empty($_GET["id"])) return ("../../404.phtml");

$issue = get_issue($repo["id"], $_GET["id"]);

if (!$issue) return ("../../404.phtml");

if (isset($_POST["content"])) {
    $content = $_POST["content"];

    $content = nl2br(htmlentities($content, ENT_QUOTES, 'UTF-8'));
    
    if (create_comment($issue["id"], $user["id"], $content)) header("Location: /repo/issue?name={$repo["name"]}&id={$issue["id"]}");
    exit;
}

$css = ["repos", "issue"];

$comments = get_comments($repo["id"], $issue["id"]);

require("issue.phtml");
