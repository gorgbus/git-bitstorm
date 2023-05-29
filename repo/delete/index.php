<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

$_GET["commit"] = "HEAD";

require("../repo.php");
require("../path.php");

if (!$logged_in || $repo["username"] != $_SESSION["user"]) return require("../../404.phtml");

delete_repo_files($title . "/" . $_GET["path"]);

$filename = basename($_GET["path"]);
$dir = get_repo_dir($title);

commit($dir, "Deleted $filename", $_SESSION["user"]);

header("Location: /repo?name={$repo["name"]}");
exit;
