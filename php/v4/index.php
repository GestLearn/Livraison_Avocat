<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require './controllers/config.php';

  // Start the session
  session_start();

  // Check if the user is logged in
  if (isset($_SESSION['user_id'])) {
    // Redirect to home.php if the user is connected
    header('Location: home.php');
    exit();
  }
  else{
    header('Location: views/login.php');
  }
?>