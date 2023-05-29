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

function get_issues($db, $repo_id, $status) {
    $sql = "
        select i.*, u.username, count(c.id) as comment_count
        from (issue i inner join user u on i.author = u.id) left join comment c on c.issue = i.id
        where i.repo = $repo_id and i.status like '$status'
        group by i.id, u.username
    ";

    $query = mysqli_query($db, $sql);

    if (!$query) {
        echo mysqli_error($db);
        return [];
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}
