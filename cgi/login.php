<?php
require_once(__DIR__ . '/../vendor/autoload.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_err = $pass_err = "";
    $name = $pass = "";

    if (empty($_POST["name"])) {
        $name_err = "Name is required!";
    } else {
        $name=test_input($_POST["name"]);
    }

    if (empty($_POST["password"])) {
        $pass_err = "Password is required!";
    } else {
        $pass=test_input($_POST["password"]);
    }

    if (verify($name, $pass)) {
        // redirect to start
        $_SESSION['loggedin'] = true; $_SESSION['id'] = $name; $_SESSION['username'] = $name; 
        echo header(__DIR__ . '/../views/admin.latte');
    } else {
        // return error
        echo $name_err . " " . $pass_err;
    }
}

function verify($name, $pass) {
    $uok = false;
    $pok = false;
    $path = get_path();
    $json = json_decode(file_get_contents($path), true);
    if ("replace" == $json['admin']['name']) {
        create_user($name, $pass);
    }
    if ($name == $json['admin']['name']) {
        $uok = true;
    }
    if (password_verify($pass, $json['admin']['pass'])) {
        $pok = true;
    }

    return $uok && $pok;
}

function create_user($name, $pass) {
    $path = get_path();
    $json = json_decode(file_get_contents($path), true);
    $json['admin'] = array('name' => $name, 'pass' => password_hash($pass, PASSWORD_DEFAULT));
    file_put_contents($path, json_encode($json));
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function create_immutable() {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

function get_path() {
    create_immutable();
    $path = $_ENV['SECRET_PATH'];
    $path = __DIR__ . '/..' . $path;
    return $path;
}

?>