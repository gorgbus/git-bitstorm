<?php
function repo_exists($name) {
    $db = db();

    $sql = "
        select *
        from repository
        where name = ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$name]);

        if ($query->fetch(PDO::FETCH_ASSOC)) return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function create_repo($name, $private, $owner) {
    if (repo_exists($name)) {
        return -1;
    }

    $db = db();

    $sql = "
        insert into repository
        (name, private, owner)
        values (?, ?, ?);
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$name, $private, $owner]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function get_repos($user_id, $visitor_id) {
    $db = db();

    $sql = "
        select r.*, u.username
        from repository r inner join user u on r.owner = u.id
        where owner = ?
    " . ($visitor_id === $user_id ? "" : " and private = 0");

    try {
        $query = $db->prepare($sql);

        $query->execute([$user_id]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return [];
}

function get_repo($name) {
    $db = db();

    $sql = "
        select r.*, u.username
        from repository r inner join user u on r.owner = u.id
        where r.name = ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$name]);

        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return 0; 
}

function search_repos($name, $visitor_id) {
    $db = db();

    $sql = "
        select r.*, u.username
        from repository r inner join user u on r.owner = u.id
        where r.name like ? and (r.private = 0 or r.owner = ?)
    ";

    $name = "%$name%";

    try {
        $query = $db->prepare($sql);

        $query->execute([$name, $visitor_id]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return [];
}

function search_my_repos($name, $user_id) {
    $db = db();

    $sql = "
        select r.*, u.username
        from repository r inner join user u on r.owner = u.id
        where r.name like ? and r.owner = ? 
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$name, $user_id]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return [];
}

function change_visibility($name, $new_vis) {
    $db = db();

    $sql = "
        update repository set private = ? 
        where name like ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$new_vis, $name]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function delete_repo($name) {
    $db = db();

    $sql = "
        delete from repository
        where name like ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$name]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}

function rename_repo($name, $new_name) {
    if (repo_exists($new_name)) {
        return -1;
    }

    $db = db();
    
    $sql = "
        update repository set name = ?
        where name like ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$new_name, $name]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0;
}
