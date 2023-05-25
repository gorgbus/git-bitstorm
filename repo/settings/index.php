<?php
require("../../session.php");
require("../../db.php");

require("../../fns/git.php");
require("../../fns/repo.php");
require("../../fns/files.php");

require("../repo.php");

$css = ["repos", "settings"]; 

$status = "";

$current_url = strtok($_SERVER["REQUEST_URI"], '?');

if (isset($_POST["repo-rename"])) {
    if ($_POST["repo-rename"] != $repo["name"]) {
        switch (rename_repo($db, $repo["name"], $_POST["repo-rename"])) {
            case 1: {
                rename_repo_dir($repo["username"], $repo["name"], $_POST["repo-rename"]);

                header("Location: /repo?name={$_POST["repo-rename"]}");

                break;
            }

            case 0: {
                $status = "Something went wrong";

                break;
            }

            case -1: {
                $status = "Name unavailable";

                break;
            }
        }
    } else $status = "Name wasn't changed";
}

if (isset($_POST["repo-visibility"])) {
    $new_vis = ($repo["private"]) ? 0 : 1;

    change_visibility($db, $repo["name"], $new_vis);

    header("Location: /repo?name={$repo["name"]}");
}

if (isset($_POST["repo-deletion"])) {
    if (delete_repo($db, $repo["name"])) {
        delete_repo_files($title);

        header("Location: /");
    }
}

require("settings.phtml");
