<?php
function login($username, $password) {
    $db = db();
    
    $password = sha1($password);

    $sql = "
        select username 
        from user
        where username = ?
        and password = ? 
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$username, $password]);

        $user = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;

        return 0;
    }

    $_SESSION["user"] = $user["username"];

    return 1;
}

function register($username, $password) {
    $db = db();

    $password = sha1($password);

    $sql = "
        insert into user (username, name, password)
        values (?, ?, ?)
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$username, "", $password]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function user_exists($username) {
    $db = db();

    $sql = "
        select id
        from user
        where username = ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$username]);

        if ($query->fetch(PDO::FETCH_ASSOC)) return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function get_user($username) {
    $db = db();

    $sql = "
        select id, username, name
        from user
        where username like ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$username]);

        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function search_users($username) {
    $db = db();    
    
    $sql = "
        select id, username
        from user
        where username like ?
    ";

    $username = "%$username%";

    try {
        $query = $db->prepare($sql);

        $query->execute([$username]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return [];
}

function change_user_name($username, $new_name) {
    $db = db();

    $sql = "
        update user set name = ?
        where username like ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$new_name, $username]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function change_user_password($username, $new_password) {
    $db = db();

    $new_password = sha1($new_password);

    $sql = "
        update user set password = '$new_password'
        where username like '$username'
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$new_password, $username]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}
