<?php
function get_latest_commit($dir) {
    $dir = REPO . $dir;

    if (!file_exists($dir)) return 0;

    $commit = [];

    exec("cd $dir && git log -n 1 --format=" . '"%H"', $commit);

    if (count($commit) < 1) return 0;

    return $commit[0];
}

function get_commit_info($dir, $commit) {
    $dir = REPO . $dir;

    if (!file_exists($dir)) return 0;

    $commit_info = [];
    $empty_tree = "4b825dc642cb6eb9a060e54bf8d69288fbee4904";

    exec("cd $dir && git log -n 1 --date=format:" . '"%b %d, %Y"' . " --format=" . '"%an%n%s%n%h%n%ar%n%ad%n%H"' . " $empty_tree..$commit", $commit_info);

    if (count($commit_info) < 1) return 0;

    return [
        "author" => $commit_info[0],
        "msg" => $commit_info[1],
        "short_hash" => $commit_info[2],
        "full_hash" => $commit_info[5],
        "date" => $commit_info[3],
        "date_alt" => $commit_info[4],
    ];
}

function get_commits($dir, $commit, $page, $page_size) {
    $dir = REPO . $dir;

    if (!file_exists($dir)) return 0;

    $commits = [];
    $offset = $page_size * ($page - 1);

    $empty_tree = "4b825dc642cb6eb9a060e54bf8d69288fbee4904";
    $range = "$empty_tree..$commit";
    
    exec("cd $dir && git log -n $page_size --skip=$offset --date=format:" . '"%b %d, %Y"' . " --format=" . '"%h %H %ad %s"' . " $range", $commits);

    if (count($commits) < 1) return 0;

    return re_array_commits($commits);
}

function re_array_commits($commits) {
    $n_commits = [];

    foreach ($commits as $commit) {
        $short_hash = substr($commit, 0, 7);
        $full_hash = substr($commit, 8, 40);
        $date = substr($commit, 49, 12);
        $msg = substr($commit, 62);

        array_push($n_commits, [
            "short_hash" => $short_hash,
            "full_hash" => $full_hash,
            "msg" => $msg,
            "date" => $date,
        ]);
    }

    return group_commits($n_commits);
}

function get_group($groups, $date) {
    foreach ($groups as $group) {
        if ($group["date"] == $date) return $group;
    }

    return 0;
}

function get_group_index($groups, $date) {
    foreach ($groups as $i => $group) {
        if ($group["date"] == $date) return $i;
    }

    return -1;
}

function group_commits($commits) {
    $groups = [];

    foreach ($commits as $commit) {
        $group = get_group($groups, $commit["date"]);


        if (!$group) {
            array_push($groups, [
                "date" => $commit["date"],
                "commits" => [$commit],
            ]);
        } else {
            $i = get_group_index($groups, $commit["date"]);

            array_push($group["commits"], $commit);

            $groups[$i] = $group;
        }
    }

    return $groups;
}

function get_commit_count($dir, $commit) {
    $dir = REPO . $dir;

    if (!file_exists($dir)) return 0;
     
    $count = [];

    exec("cd $dir && git rev-list $commit --count", $count);

    if (count($count) < 1) return 0;

    return (int) $count[0];
}

function get_repo_dir($dir) {
    return REPO . $dir;
}

function commit($dir, $msg, $username) {
    exec("cd $dir && git config user.email upload@email.com");

    exec("cd $dir && git config user.name $username");

    exec("cd $dir && git add .");

    exec("cd $dir && git commit -m " . '"' . $msg. '"');
}

function init_repo($dir) {
    exec("cd $dir && git init");
}

function get_tree($dir, $commit, $path) {
    $dir = REPO . $dir;
    $tree = [];

    if (!file_exists($dir)) return [];

    exec("cd $dir && git ls-tree $commit:" . '"'. $path . '"', $tree);

    return re_array_tree($dir, $tree, $commit);
}

function re_array_tree($dir, $tree, $commit) {
    $n_tree = [];

    foreach ($tree as $file) {
        $type_start = strpos($file, " ");

        $type = substr($file, $type_start + 1, 4);
        $hash = substr($file, $type_start + 6, 40);
        $name = substr($file, $type_start + 47);
        
        $empty_tree = "4b825dc642cb6eb9a060e54bf8d69288fbee4904";

        $commit_info = [];
        $range = "$empty_tree..$commit";

        exec("cd $dir && git log -n 1 --format=" . '"%s%n%ar%n%H"' . " --find-object=$hash $range --boundary", $commit_info);

        $msg = "";
        $date = "";
        $file_commit = "";

        if (count($commit_info) > 0) {
            $msg = $commit_info[0];
            $date = $commit_info[1];
            $file_commit = $commit_info[2];
        } 

        array_push($n_tree, [
            "type" => $type,
            "name" => $name,
            "msg" => $msg,
            "date" => $date,
            "commit" => $file_commit,
        ]);
    }

    $trees = array_filter($n_tree, function($t) { return $t["type"] == "tree"; });
    $blobs = array_filter($n_tree, function($t) { return $t["type"] == "blob"; });

    function cmp($a, $b) {
        return strcmp($a["name"], $b["name"]);
    }

    usort($trees, "cmp");
    usort($blobs, "cmp");

    $n_tree = array_merge($trees, $blobs);

    return $n_tree;
}

function get_file($dir, $commit, $path) {
    $dir = REPO . $dir;
    $file = [];

    if (!file_exists($dir)) return [];

    exec("cd $dir && git cat-file -p $commit:" . '"' . $path . '"', $file);

    return $file;
}

function get_diff($dir, $old_commit, $new_commit) {
    $dir = REPO . $dir;
    $diff = [];

    if (!file_exists($dir)) return [];

    $range = "$old_commit $new_commit";
    $empty_tree = "4b825dc642cb6eb9a060e54bf8d69288fbee4904";

    if ($old_commit == $new_commit) $range = "$empty_tree $old_commit";
    
    exec("cd $dir && git diff $range", $diff);
    
    return implode("\n", $diff);
}

function get_prev_commit($dir, $commit) {
    $dir = REPO . $dir;
    $prev_commit = [];

    if (!file_exists($dir)) return [];

    exec("cd $dir && git log -n 1 --format=" . '"%H"' . " $commit~1", $prev_commit);

    if (empty($prev_commit)) return $commit;

    return $prev_commit[0];
}

function create_zip($username, $repo_name, $commit) {
    $dir = REPO . $username . "/" . $repo_name;

    if (!file_exists($dir)) return 0; 

    $out_dir = BASE . "/tmp/" . $username;
    $output = $out_dir . "/" . $repo_name;

    if (!file_exists($out_dir)) mkdir($out_dir, 0777, true);

    if ($commit != get_latest_commit($username . "/" . $repo_name)) $output = $output . "-" . $commit;

    $output = $output . ".zip";

    if (!file_exists($out_dir)) mkdir($out_dir, 0777, true);

    exec("cd $dir && git archive --format=zip --output=$output $commit");

    return $output;
}

function create_file($username, $repo_name, $file, $commit) {
    $dir = REPO . $username . "/" . $repo_name;

    if (!file_exists($dir)) return 0; 

    $out_dir = BASE . "/tmp/" . $username;
    $output = $out_dir . "/" . pathinfo($file, PATHINFO_FILENAME);

    if (!file_exists($out_dir)) mkdir($out_dir, 0777, true);

    if ($commit != get_latest_commit($username . "/" . $repo_name)) $output = $output . "-" . $commit;

    $output = $output . "." . pathinfo($file, PATHINFO_EXTENSION);

    exec("cd $dir && git cat-file -p $commit:" . '"' . $file . '"' . " > $output");

    return $output;
}

function get_latest_changes($repos) {
    $dir = REPO;
    $i = 0;

    foreach ($repos as $repo) {
        $change = [];

        exec("cd $dir/{$repo["username"]}/{$repo["name"]} && git log -n 1 --format=" . '"%ar"' . " HEAD", $change);

        $repo["change"] = "";

        if (!empty($change)) $repo["change"] = $change[0];

        $repos[$i] = $repo;
        
        $i++;
    }

    return $repos;
}

function check_commit($dir, $commit) {
    $dir = REPO . $dir;

    $valid = [];

    exec("cd $dir && git rev-parse --verify $commit", $valid);

    if (empty($valid)) return 0;

    return 1;
}

function check_path($dir, $commit, $path) {
    $dir = REPO . $dir;

    $valid = [];

    exec("cd $dir && git show $commit:" . '"' . $path . '"', $valid);

    if (empty($valid)) return 0;

    return 1;
}
