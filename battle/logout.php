<?php

session_start();
unset($_SESSION["group"]);
header("Location: index.php");
?>
