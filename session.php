<?php
define("REPO", __DIR__ . "/repositories/");
define("BASE", __DIR__);

session_start();

if (isset($_SESSION["user"])) $logged_in = 1;
else $logged_in = 0;
