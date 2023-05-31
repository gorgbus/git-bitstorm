<?php
function count_issues($repo_id, $status) {
    $db = db();

    $sql = "
        select count(id) as issue_count
        from issue
        where repo = ? and status like ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$repo_id, $status]);

        return $query->fetch(PDO::FETCH_ASSOC)["issue_count"];
    } catch (PDOException $e) {
        echo $e;
    }

    return 0; 
}

function get_issues($repo_id, $status, $name) {
    $db = db();

    $order = ($status == "open") ? "i.created_at" : "i.closed_at";
    
    $sql = "
        select i.*, u.username, count(c.id) as comment_count
        from (issue i inner join user u on i.author = u.id) left join comment c on c.issue = i.id
        where i.repo = ? and i.status like ? and i.name like ?
        group by i.id, u.username
        order by $order desc
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$repo_id, $status, $name]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return [];
}

function create_new_issue($repo_id, $author, $name, $content) {
    $db = db();

    $sql = "
        insert into issue
        (repo, author, name, content)
        values (?, ?, ?, ?)
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$repo_id, $author, $name, $content]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0; 
}

function get_issue($repo_id, $issue_id) {
    $db = db();

    $sql = "
        select i.*, u.username, count(c.id) as comment_count
        from (issue i inner join user u on i.author = u.id) left join comment c on c.issue = i.id
        where i.repo = ? and i.id = ?
        group by i.id, u.username
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$repo_id, $issue_id]);

        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return 0; 
}

function get_comments($repo_id, $issue_id) {
    $db = db();

    $sql = "
        select c.*, u.username
        from comment c inner join issue i on c.issue = i.id inner join user u on u.id = c.author
        where c.issue = ? and i.repo = ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$issue_id, $repo_id]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return [];
}

function create_comment($issue_id, $author_id, $content) {
    $db = db();

    $sql = "
        insert into comment
        (issue, author, content)
        values (?, ?, ?)
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$issue_id, $author_id, $content]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0; 
}

function change_issue_status($repo_id, $issue_id, $status) {
    $db = db();

    $sql = "
        update issue
        set status = ?, closed_at = CURRENT_TIMESTAMP()
        where id = ? and repo = ?
    ";

    try {
        $query = $db->prepare($sql);

        $query->execute([$status, $issue_id, $repo_id]);

        return 1;
    } catch (PDOException $e) {
        echo $e;
    }

    return 0; 
}
