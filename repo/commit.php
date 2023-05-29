<?php
if (!isset($_GET["commit"]) || empty($_GET["commit"])) {
    require(__DIR__ . "/../404.phtml");
    exit;
}

if (!check_commit($title, $_GET["commit"])) {
    require(__DIR__ . "/../404.phtml");
    exit;
}
