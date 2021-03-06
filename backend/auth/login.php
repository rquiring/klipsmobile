<?php

error_reporting(E_ALL);
ini_set("display_errors",1);

session_start();

require_once "../includes/config.php";
require_once "../includes/connect.php";
require_once "helpers.php";

$username = $_POST['username'];
$password = $_POST['password'];
//connect to the database here
$st = $db->prepare("SELECT password, salt FROM users WHERE username = :username");

$st->execute(array('username'=>$username));


if($st->rowCount() < 1) //no such user exists
{
    header('Location: ../../frontend/');
    die();
}
$userData = $st->fetchAll();
$userData = $userData[0];
$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
if($hash != $userData['password']) //incorrect password
{
    header('Location: ../../frontend/');
    die();
}
else
{
    validateUser($userData['username']); //sets the session data for this user
}
//redirect to another page or display "login success" message

header('Location: ../../frontend/');


?>