<?php
require("../session.php");
require("../db.php");

require("../fns/repo.php");
require("../fns/files.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../404.phtml");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) return require("../404.phtml");

$title = $repo["username"] . "/" . $repo["name"];

$files = get_files($title);

require("repo.phtml");
