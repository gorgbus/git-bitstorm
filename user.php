<?php
if ($logged_in) {
    $user = get_user($db, $_SESSION["user"]);
}
