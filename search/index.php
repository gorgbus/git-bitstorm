<?php
require("../db.php");
require("../session.php");

require("../fns/repo.php");
require("../fns/user.php");

require("../user.php");

$title = "Search";
$css = ["repos"];

if (isset($_GET["q"]) && !empty($_GET["q"])) {
    if (str_starts_with($_GET["q"], "user:")) {
        if (isset($_GET["t"]) && $_GET["t"] === "users") {
            $users = [];
        } else {
            $repos = search_my_repos($db, substr($_GET["q"], 5), $_SESSION["user"]);
        }
    } else {
        if (isset($_GET["t"]) && $_GET["t"] === "users") {
            $users = search_users($db, $_GET["q"]);
        } else {
            $repos = search_repos($db, $_GET["q"], ($logged_in) ? $_SESSION["user"] : -1);
        }
    }
}

require("search.phtml");
