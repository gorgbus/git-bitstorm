<?php
function count_issues($db, $repo_id, $status) {
    $sql = "
        select count(id) as issue_count
        from issue
        where repo = $repo_id and status like '$status'
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return 0;
    }

    $query = mysqli_fetch_assoc($query);

    return $query["issue_count"];
}

function get_issues($db, $repo_id, $status, $name) {
    $order = ($status == "open") ? "i.created_at" : "i.closed_at";
    
    $sql = "
        select i.*, u.username, count(c.id) as comment_count
        from (issue i inner join user u on i.author = u.id) left join comment c on c.issue = i.id
        where i.repo = $repo_id and i.status like '$status' and i.name like '$name'
        group by i.id, u.username
        order by $order desc
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return [];
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

function create_new_issue($db, $repo_id, $author, $name, $content) {
    $sql = "
        insert into issue
        (repo, author, name, content)
        values ($repo_id, $author, '$name', '$content')
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return 0;
    }

    return 1;
}

function get_issue($db, $repo_id, $issue_id) {
    $sql = "
        select i.*, u.username, count(c.id) as comment_count
        from (issue i inner join user u on i.author = u.id) left join comment c on c.issue = i.id
        where i.repo = $repo_id and i.id = $issue_id
        group by i.id, u.username
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return 0; 
    }

    return mysqli_fetch_assoc($query);
}

function get_comments($db, $repo_id, $issue_id) {
    $sql = "
        select c.*, u.username
        from comment c inner join issue i on c.issue = i.id inner join user u on u.id = c.author
        where c.issue = $issue_id and i.repo = $repo_id
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return [];
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

function create_comment($db, $issue_id, $author_id, $content) {
    $sql = "
        insert into comment
        (issue, author, content)
        values ($issue_id, $author_id, '$content')
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return 0;
    }

    return 1;
}

function change_issue_status($db, $repo_id, $issue_id, $status) {
    $sql = "
        update issue
        set status = '$status', closed_at = CURRENT_TIMESTAMP()
        where id = $issue_id and repo = $repo_id
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return 0;
    }

    return 1;
}
