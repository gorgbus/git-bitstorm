<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

require("../repo.php");

$css = ["repos", "issue"];

$status = "open";
$name = "%";

if (isset($_GET["status"])) $status = $_GET["status"];

if (isset($_GET["q"])) $name = "%{$_GET["q"]}%";

$issues = get_issues($repo["id"], $status, $name);
$closed_issues = count_issues($repo["id"], "closed");

require("issues.phtml");
