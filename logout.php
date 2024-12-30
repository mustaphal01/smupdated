<?php
// logout.php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Call the logout function
logout();

// Redirect to login page
header("Location: login.php");
exit();