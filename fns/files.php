<?php
function re_array_files($file_post) {

    $file_ary = [];
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function save_files($dir, $files, $msg, $user_id) {
    $files = re_array_files($files);
    $dir = REPO . $dir;

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        
        init_repo($dir);
    }

    foreach ($files as $file) {
        $tmp_name = $file["tmp_name"];
        $name = $file["full_path"];

        if (str_contains($name, ".git/")) continue;

        $name = str_replace("../", "", $name); 

        if ($file["error"] == 0) { 
            if (!file_exists(pathinfo("$dir/$name")["dirname"])) mkdir(pathinfo("$dir/$name")["dirname"], 0777, true);
            move_uploaded_file($tmp_name, "$dir/$name");
        }
        else echo "Error uploading file $name";
    }

    commit($dir, $msg, $user_id); 
}

function is_binary($file) {
    return false === mb_detect_encoding(implode("\n", $file), null, true);
}

function delete_repo_files($dir) {
    $dir = REPO . $dir;

    delete_files($dir);
}

function delete_files($dir) {
    $files = scandir($dir);
    $files = array_filter($files, function($f) {
        return !in_array($f, [".", ".."]);
    });

    foreach ($files as $file) {
        $tmp_dir = $dir . "/" . $file;

        if (is_dir($tmp_dir)) {
            delete_files($tmp_dir);

            rmdir($tmp_dir);
        } else {
            chmod($tmp_dir, 0777);

            unlink($tmp_dir);
        }
    }

    rmdir($dir);
}

function rename_repo_dir($username, $name, $new_name) {
    $dir = REPO . $username;
    
    rename($dir . "/" . $name, $dir . "/" . $new_name);
}
