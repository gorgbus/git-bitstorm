<?php
require("../session.php");

require("../fns/files.php");

if (!isset($_GET["user"]) || empty($_GET["user"]) || !isset($_GET["v"]) || empty($_GET["v"])) exit;

if (!in_array($_GET["v"], ["full", "small"])) exit;

$file = get_profile_picture($_GET["user"]);

if (!$file)  exit;

$v = $_GET["v"];

header('Content-Description: Profile picture');
header('Content-Type: image/webp');
header('Content-Length: ' . filesize($file[$v]));

readfile($file[$v]);

exit;
