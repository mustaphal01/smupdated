<?php
// includes/auth.php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /school_management/login.php");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['user_type'] !== 'admin') {
        header("Location: /school_management/dashboard.php");
        exit();
    }
}

function requireStudent() {
    requireLogin();
    if ($_SESSION['user_type'] !== 'student') {
        header("Location: /school_management/admin/dashboard.php");
        exit();
    }
}

// Function to safely logout user
function logout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    
    // Destroy the session
    session_destroy();
}