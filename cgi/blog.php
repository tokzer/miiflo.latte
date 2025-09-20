<?php
require_once(__DIR__ . '/../vendor/autoload.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $q=$_GET["blog-post"];
    if (strlen($q) > 0) { 
        echo parse($q);
    }

    $q=$_GET["q"];
    if (strlen($q) > 0) { 
        echo show();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p = $_POST["blog-post"];

    if (strlen($p) > 0) { 
        save($p);
    }
}

function save($post) {

    $header = 'Lite tankar ' . date('Y-m-d');
    $lines = preg_split('/\r\n|\n|\r/', $post);
    echo 'Saved: ' . $lines[0];
    if (str_starts_with($lines[0], '#')) {
        $header = trim(str_replace('#','', $lines[0]));
    } else {
        $post = '#' . $header . "\r\n" . $post;
    }

    $time_stamp = date('Y-m-d:H.i.s');
    $post = '##### Note posted on: ' . $time_stamp . "\r\n\r\n" . $post;

    $file_name = date('YmdHis') . '.' . str_replace(' ', '.',$header) . '.md';
    $wc = fopen(__DIR__ . '/../blog/' . $file_name, 'w');
    fwrite($wc, $post);
    fclose($wc);
}

function parse($md) {
    $parsedown = new \Parsedown();
    $parsedown->setBreaksEnabled(true);
    return $parsedown->text($md);
}

function show() {
    $posts = get_files();
    return make_it_html($posts);
}

// Parse proper and lazy load
function make_it_html($posts) {
    $html = '<div class="post">';
    foreach ($posts as $post) {
        $html .= $post;
        $html .= '</div><div class="post">';
    }

    $html .= "</div>";
    return $html;
}

function get_files() {
    $dir = __DIR__ . '/../blog/';
    $posts = array();
    // Open a directory, and read its contents
    if (is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                $path = $dir . $file;
                if (is_file($path)) {
                    array_push($posts, get_file_content($path));
                }
            }
            closedir($dh);
        }
    }

    return $posts;
}

function get_file_timestamp($file) {
    $split = $file.explode('.');
    return $split[0];
}

function get_file_content($file) {
    $content = file_get_contents($file);
    $md = parse($content);
    return $md;
}

?>