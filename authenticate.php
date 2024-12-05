<?php
/**w*** 
    Name: Rajvir Singh 
    Date: September 29, 2024
    Description: Manages user authentication for the blog.
******/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ADMIN_LOGIN','wally'); 
define('ADMIN_PASSWORD','mypass'); 

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Our Blog"');
    if (!isset($_SERVER['PHP_AUTH_USER']) || 
        !isset($_SERVER['PHP_AUTH_PW']) || 
        $_SERVER['PHP_AUTH_USER'] !== ADMIN_LOGIN || 
        $_SERVER['PHP_AUTH_PW'] !== ADMIN_PASSWORD) {
        exit("Access Denied: Username and password required.");
    }
    $_SESSION['user_logged_in'] = true;
}
?>