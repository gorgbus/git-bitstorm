<?php
require("../../session.php");
require("../../db.php");

if (!$logged_in) return require("../../404.phtml");

$title = "New Repository";
$css = ["repos"];

require("../../fns/repo.php");
require("../../fns/user.php");

require("../../user.php");

if (isset($_POST["name"]) && !empty($_POST["name"])) {
    $name = $_POST["name"];
    $private = $_POST["private"];
    $owner = $_SESSION["user"];

    $name = preg_replace("/[[:blank:]]+/", "-", $name);
    $name = preg_replace("/[^0-9a-zA-Z-]/", "", $name);

    $res = create_repo($db, $name, $private, $owner);

    switch ($res) {
        case -1:
            echo "Repository with name $name already exists";
            break;
        case 0:
            echo "Error creating repository";
            break;
        case 1:
            header("Location: /repo/upload/?name=$name");
            break;
    }
}

require("create.phtml");
