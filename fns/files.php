<?php
function re_array_files($file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function save_files($files, $dir) {
    $files = re_array_files($files);
    $dir = __DIR__ . "/../repositories/" . $dir;

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);

        exec("cd $dir && git init");
    }

    foreach ($files as $file) {
        $tmp_name = $file["tmp_name"];
        $name = $file["name"];

        if ($file["error"] == 0) move_uploaded_file($tmp_name, "$dir/$name");
        else echo "Error uploading file $name";
    }
}

function get_files($dir) {
    $dir = __DIR__ . "/../repositories/" . $dir;
    $files = 0;

    if (!file_exists($dir)) return $files;

    exec("cd $dir && git ls-tree", $files);
    echo $files;

    $files = scandir($dir);

    $files = array_filter($files, function($file) {
        $exclude = [".", "..", ".git"];
        return !in_array($file, $exclude);
    });

    return $files;
}
