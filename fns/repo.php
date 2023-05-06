<?php

function repo_exists($db, $name)
{
    $sql = "
        select *
        from repository
        where name = '$name'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
        return 0;
    }

    if (mysqli_fetch_assoc($res)) {
        return 1;
    }

    return 0;
}

function create_repo($db, $name, $private, $owner)
{
    if (repo_exists($db, $name)) {
        return -1;
    }

    $sql = "
        insert into repository
        (name, private, owner)
        values ('$name', $private, $owner);
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
        return 0;
    }

    return 1;
}

function get_repos($db, $user_id, $visitor_id)
{
    $sql = "
        select *
        from repository
        where owner = $user_id
    " . ($visitor_id === $user_id ? "" : " and private = 0");

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
        return [];
    }

    $repos = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $repos;
}

function get_repo($db, $name)
{
    $sql = "
        select r.*, u.username
        from repository r inner join user u on r.owner = u.id
        where r.name = '$name'
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
        return 0;
    }

    $repo = mysqli_fetch_assoc($res);

    return $repo;
}

function search_repos($db, $name, $visitor_id)
{
    $sql = "
        select r.*, u.username
        from repository r inner join user u on r.owner = u.id
        where r.name like '%$name%' and (r.private = 0 or r.owner = $visitor_id)
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
        return [];
    }

    $repos = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $repos;
}

function search_my_repos($db, $name, $user_id)
{
    $sql = "
        select r.*, u.username
        from repository r inner join user u on r.owner = u.id
        where r.name like '%$name%' and r.owner = $user_id
    ";

    $res = mysqli_query($db, $sql);

    if (!$res) {
        echo mysqli_error($db);
        return [];
    }

    $repos = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $repos;
}
