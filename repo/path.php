<?php
if (!isset($_GET["path"]) || empty($_GET["path"])) {
    require(__DIR__ . "/../404.phtml");
    exit;
}

if (!check_path($title, $_GET["commit"], $_GET["path"])) {
    require(__DIR__ . "/../404.phtml");
    exit;
}
