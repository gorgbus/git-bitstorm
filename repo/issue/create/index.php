<?php
require("../../../session.php");
require("../../../db.php");

require("../../../fns/git.php");
require("../../../fns/repo.php");
require("../../../fns/files.php");

require("../../repo.php");

$css = ["repos", "issue"];

if (isset($_POST["name"])) {
    $name = $_POST["name"];
    $content = $_POST["content"];

    $content = nl2br(htmlentities($content, ENT_QUOTES, 'UTF-8'));

    if (create_new_issue($db, $repo["id"], $user["id"], $name, $content)) header("Location: /repo/issues?name={$repo["name"]}");
    exit;
}

require("create.phtml");
