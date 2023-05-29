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

    echo $dir;

    if (file_exists($dir)) delete_files($dir);
}

function delete_files($dir) {
    if (is_file($dir)) return unlink($dir);

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

function save_profile_picture($username, $picture) {
    $dir = BASE . "/profile_pics/" . $username;

    if (!file_exists($dir)) mkdir($dir, 0777, true);

    if ($picture["error"] == 0) save_as_webp($dir, $picture["tmp_name"]);
}

function save_as_webp($dir, $picture) {
    list($width, $height, $type) = getimagesize($picture);

    $orig_picture = null;

    switch ($type) {
        case IMAGETYPE_PNG: $orig_picture = imagecreatefrompng($picture); break;
        case IMAGETYPE_JPEG: $orig_picture = imagecreatefromjpeg($picture); break;
        case IMAGETYPE_WEBP: $orig_picture = imagecreatefromwebp($picture);
    }

    $square_size = max($width, $height);
    if ($square_size > 512) $square_size = 512;

    $webp_picture = imagecreatetruecolor($square_size, $square_size);
    $background = imagecolorallocatealpha($webp_picture, 0, 0, 0, 127);

    imagefill($webp_picture, 0, 0, $background);
    imagesavealpha($webp_picture, true);

    imagecopyresampled($webp_picture, $orig_picture, 0, 0, 0, 0, $square_size, $square_size, $width, $height);

    imagewebp($webp_picture, $dir . "/full.webp");

    $small_picture = imagecreatetruecolor(40, 40);

    imagefill($small_picture, 0, 0, $background);
    imagesavealpha($small_picture, true);

    imagecopyresampled($small_picture, $webp_picture, 0, 0, 0, 0, 40, 40, $square_size, $square_size);

    imagewebp($small_picture, $dir . "/small.webp");

    imagedestroy($orig_picture);
    imagedestroy($webp_picture);
    imagedestroy($small_picture);
}

function get_profile_picture($username) {
    $dir = BASE . "/profile_pics/" . $username;

    if (!file_exists($dir . "/full.webp")) return 0;

    return [
        "full" => $dir . "/full.webp",
        "small" => $dir . "/small.webp",
    ];
}

function gen_profile_picture($username) {
    $dir = BASE . "/profile_pics/" . $username;

    if (!file_exists($dir)) mkdir($dir, 0777, true);

    $hash = md5($username);

    $url = "https://www.gravatar.com/avatar/$hash?d=identicon&s=512&r=g";

    save_as_webp($dir, $url);
}
