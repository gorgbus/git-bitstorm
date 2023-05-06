<?php
require("../../session.php");
require("../../db.php");

if (!$logged_in) return require("../../404.phtml");

require("../../fns/git.php");
require("../../fns/files.php");
require("../../fns/repo.php");

$repo = get_repo($db, $_GET["name"]);

if (!$repo || $repo["owner"] != $_SESSION["user"]) return require("../../404.phtml");

$title = "Upload files";
$max_file_size = 1024 * 1024 * 10;

$dir = $repo["username"] . "/" . $repo["name"];

if (isset($_FILES["files"]) && isset($_POST["message"]) && !empty($_POST["message"])) {
    save_files($dir, $_FILES["files"], $_POST["message"]);

    header("Location: /repo/?name=" . $repo["name"]);
}

require("upload.phtml");
