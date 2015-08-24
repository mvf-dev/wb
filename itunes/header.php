<?php
session_start();
$user=$_SESSION['login_user'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="css/header.css" media="screen" />
<SCRIPT SRC="scripts/jquery-1.11.1.js"> </SCRIPT>
<SCRIPT>

$(function() {
  if ($.browser.msie && $.browser.version.substr(0,1)<7)
  {
        $('li').has('ul').mouseover(function(){
                $(this).children('ul').css('visibility','visible');
                }).mouseout(function(){
                $(this).children('ul').css('visibility','hidden');
                })
  }
});

</SCRIPT>

</head>
<body>
<br>
<div id="container" name="container">
<img src="images/images.jpg" style="float:left" alt="Logo"/>
<ul id="menu">
<li><a href="http://dm-mediainfo-2/itunes/">iTunes Files Search</a></li>
<li><a href="http://dm-mediainfo-2/xbox/">Xbox Files Search</a></li>
<li><a href="http://dm-mediainfo-2">Netflix Files Search</a></li>
<li><a href="http://dm-mediainfo-2/itunes/report.php">iTunes Job Report</a></li>

</ul>
<br>
<br>
<br>
</div>
</body>
</html>

