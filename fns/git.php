<?php
function get_latest_commit($dir) {
    $dir = __DIR__ . "/../repositories/" . $dir;

    if (!file_exists($dir)) return 0;

    $commit = [];

    exec("cd $dir && git log -n 1 --format=" . '"%H"', $commit);

    if (count($commit) < 1) return 0;

    return $commit[0];
}

function get_first_commit($dir) {
    $first_commit = [];

    exec("cd $dir && git log --reverse --format=" . '"%H"' . " --max-parents=0", $first_commit);

    if (count($first_commit) < 1) return 0;

    return $first_commit[0];
}

function get_commits($dir, $commit, $page) {
    $dir = __DIR__ . "/../repositories/" . $dir;

    if (!file_exists($dir)) return 0;

    $first_commit = get_first_commit($dir);

    $commits = [];
    $offset = 20 * ($page - 1);

    $range = "$first_commit..$commit";

    if ($first_commit == $commit) $range = $commit;
    
    exec("cd $dir && git log -n 20 --skip=$offset --format=" . '"%h %H %s"' . " $range --boundary", $commits);

    if (count($commits) < 1) return 0;

    return re_array_commits($commits);
}

function re_array_commits($commits) {
    $n_commits = [];
    $i = 0;

    foreach ($commits as $commit) {
        $short_hash = substr($commit, 0, 7);
        $full_hash = substr($commit, 8, 40);
        $msg = substr($commit, 49);

        $n_commits[$i] = [
            "short_hash" => $short_hash,
            "full_hash" => $full_hash,
            "msg" => $msg,
        ];

        $i++;
    }

    return $n_commits;
}

function get_commit_count($dir, $commit) {
    $dir = __DIR__ . "/../repositories/" . $dir;

    if (!file_exists($dir)) return 0;
     
    $count = [];

    exec("cd $dir && git rev-list $commit --count", $count);

    if (count($count) < 1) return 0;

    return (int) $count[0];
}

function commit($dir, $name) {
    exec("cd $dir && git config user.email upload@email.com");

    exec("cd $dir && git config user.name upload");

    exec("cd $dir && git add .");

    exec("cd $dir && git commit -m $name");
}

function init_repo($dir) {
    exec("cd $dir && git init");
}

function get_tree($dir, $commit, $path) {
    $dir = __DIR__ . "/../repositories/" . $dir;
    $tree = [];

    if (!file_exists($dir)) return [];

    exec("cd $dir && git ls-tree $commit:$path", $tree);

    return re_array_tree($dir, $tree, $commit);
}

function re_array_tree($dir, $tree, $commit) {
    $n_tree = [];
    $i = 0;

    foreach ($tree as $file) {
        $type_start = strpos($file, " ");

        $type = substr($file, $type_start + 1, 4);
        $hash = substr($file, $type_start + 6, 40);
        $name = substr($file, $type_start + 47);
        
        $first_commit = get_first_commit($dir);

        $commit_info = [];
        $range = "$first_commit..$commit";

        if ($first_commit == $commit) $range = $commit;

        exec("cd $dir && git log -n 1 --format=" . '"%s %ar"' . " --find-object=$hash $range --boundary", $commit_info);

        $msg = "";
        $date = "";

        if (count($commit_info) > 0) {
            $msg = strtok($commit_info[0], " ");
            $date = substr($commit_info[0], strlen($msg));
        } 

        $n_tree[$i] = [
            "type" => $type,
            "name" => $name,
            "msg" => $msg,
            "date" => $date,
        ];

        $i++;
    }

    return $n_tree;
}

function get_file($dir, $commit, $path) {
    $dir = __DIR__ . "/../repositories/" . $dir;
    $file = [];

    exec("cd $dir && git cat-file -p $commit:$path", $file);

    return $file;
}
