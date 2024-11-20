<?php
    // Logout Code Here
    require '../functions.php';
    session_start();
    $loginForm = '../index.php';
    logOut($loginForm);
?>