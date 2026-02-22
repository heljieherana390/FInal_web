<?php
// logout.php

session_start();

// Destroy session to log out the user
session_unset();
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
?>
