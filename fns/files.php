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

function save_files($dir, $files, $msg) {
    $files = re_array_files($files);
    $dir = __DIR__ . "/../repositories/" . $dir;

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        
        init_repo($dir);
    }

    foreach ($files as $file) {
        $tmp_name = $file["tmp_name"];
        $name = $file["name"];

        if ($file["error"] == 0) 
            move_uploaded_file($tmp_name, "$dir/$name");
        else echo "Error uploading file $name";
    }

    commit($dir, $msg); 
}
