<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

require("../repo.php");

if (!$logged_in || $repo["username"] != $_SESSION["user"]) return ("../../404.phtml");

if (!isset($_GET["id"]) || empty($_GET["id"])) return ("../../404.phtml");

$issue = get_issue($repo["id"], $_GET["id"]);

if (!$issue) return ("../../404.phtml");

$status = ($issue["status"] == "open") ? "closed" : "open";

if (change_issue_status($repo["id"], $issue["id"], $status)) header("Location: /repo/issue?name={$repo["name"]}&id={$issue["id"]}");
exit;
