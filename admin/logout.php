<?php
    // Logout Code Here
    include '../functions.php';
    session_start();
    $loginForm = '../index.php';
    logOut($loginForm);
?>