<?php
  session_start();

  // unistavanje sesije 'userid'
  if(isset($_SESSION['userid'])) {
    unset($_SESSION["userid"]);
  }
  
  header('Location: ./index.php');
?>