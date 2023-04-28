<?php
session_start();

if (isset($_SESSION["user"])) $logged_in = 1;
else $logged_in = 0;
