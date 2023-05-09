<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");

if (!isset($_GET["name"]) || empty($_GET["name"])) return require("../../404.phtml");
if (!isset($_GET["commit"]) || empty($_GET["commit"])) return require("../../404.phtml");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || ($repo["private"] && !$logged_in) || ($repo["private"] && $repo["owner"] != $_SESSION["user"])) return require("../../404.phtml");

$file = "";

if (!isset($_GET["path"]) || empty($_GET["path"])) {
    $file = create_zip($repo["username"], $repo["name"], $_GET["commit"]);

    if (!$file || !file_exists($file)) {
        echo "failed to create zip";
        exit;
    }
} else {
    $file = create_file($repo["username"], $repo["name"], $_GET["path"], $_GET["commit"]);

    if (!$file || !file_exists($file)) {
        echo "failed to create file";
        exit;
    }
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($file));

readfile($file);

unlink($file);

exit;
