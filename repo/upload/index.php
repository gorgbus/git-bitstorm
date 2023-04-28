<?php
require("../../session.php");
require("../../db.php");

if (!$logged_in) return require("../../404.phtml");

require("../../fns/files.php");
require("../../fns/repo.php");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || $repo["owner"] != $_SESSION["user"]) return require("../../404.phtml");

$title = "Upload files";
$max_file_size = 1024 * 1024 * 10;

$dir = $repo["username"] . "/" . $repo["name"];

require("upload.phtml");

if (isset($_FILES["files"])) {
    $files = $_FILES["files"];

    save_files($files, $dir);

    header("Location: /repo/?name=" . $repo["name"]);
}