<?php
require_once(__DIR__ . '/vendor/autoload.php');
session_start();

$latte = new Latte\Engine();
$latte->setTempDirectory(__DIR__ . '/compiles');
$latte->setautoRefresh();

// echo 'before';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //echo 'after';
    // Array
    // (
    //     [scheme] => http
    //     [host] => www.mydomain.com
    //     [path] => /abc/
    // )
    $path2 = array();
    $page2 = 'tomte';
    $path2 = explode('/', urldecode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
    $page2 = array_shift($path2);
    // echo $page2 . ':e2, ';
    // echo $path2[0] . ':h.20, ';
    // echo $path2[1] . ':h.21, ';
    // echo $path2[2] . ':h.22, ';
    //$path = parse_url($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], PHP_URL_PATH);
    //echo $path . ' empty?';
    $layout = true;
    if ($path2[1] == 'btn') {
        $layout = false;
    }

    switch ($path2[0]) {
        case '':
        case ' ':
        case 'home':
        //case 'miiflo.se/':
            echo $latte->render(__DIR__ . '/views/home.latte', ['layout' => $layout]);
            break;
        case "/home/btn":
            echo $latte->render(__DIR__ . '/views/home.latte', ['layout' => false]);
            break;
        case "about":
            echo $latte->render(__DIR__ . '/views/about.latte', ['layout' => $layout]);
            break;
        case "/about/btn":
            echo $latte->render(__DIR__ . '/views/about.latte', ['layout' => false]);
            break;
        case "admin":
            echo $latte->render(__DIR__ . '/views/admin.latte', ['loggedIn' => $_SESSION['loggedin']]);
            break;
        case "logout":
            session_unset();
            session_destroy();
            break;
        default:
            // echo '404 Not found.';
            echo $latte->render(__DIR__ . '/views/404.latte');
            break;
    }
}
   
?>