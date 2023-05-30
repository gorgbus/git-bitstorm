<?php
function login($db, $username, $password) {
    $password = sha1($password);

    $sql = "
        select username 
        from user
        where username = '$username'
        and password = '$password'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) echo mysqli_error($db);

    $user = mysqli_fetch_assoc($res);

    if ($user) {
        $_SESSION["user"] = $user["username"];

        return 1;
    }

    return 0;
}

function register($db, $username, $password) {
    $password = sha1($password);

    $sql = "
        insert into user (username, name, password)
        values ('$username', '', '$password')
    ";

    $res = mysqli_query($db, $sql);

    if ($res) return 1;
    else echo mysqli_error($db);

    return 0;
}

function user_exists($db, $username) {
    $sql = "
        select id
        from user
        where username = '$username'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) echo mysqli_error($db);
    
    $user = mysqli_fetch_assoc($res);

    if ($user) return 1;
    
    return 0;
}

function get_user($db, $username) {
    $sql = "
        select id, username, name
        from user
        where username like '$username'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) echo mysqli_error($db);

    $user = mysqli_fetch_assoc($res);

    return $user;
}

function search_users($db, $username) {
    $sql = "
        select id, username
        from user
        where username like '%$username%'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
        return [];
    }

    $users = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $users;
}

function change_user_name($db, $username, $new_name) {
    $sql = "
        update user set name = '$new_name'
        where username like '$username'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
    }
}

function change_user_password($db, $username, $new_password) {
    $new_password = sha1($new_password);

    $sql = "
        update user set password = '$new_password'
        where username like '$username'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
    }
}
