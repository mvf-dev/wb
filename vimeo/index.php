<?php
include('db/connect.php');
?>

<!DOCTYPE html> 
<html lang="en"> 
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<head>
	<meta charset=utf-8 /> 
	<title>Vimeo Files Search</title> 
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css'/> 
	<link media="screen" rel="stylesheet" href="css/demo-style.css" /> 
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->


<SCRIPT SRC="scripts/jquery-1.9.1.js"> </SCRIPT>
<SCRIPT>
$(document).ready(function() {
         $('#selecctall').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });


});

</SCRIPT>


<style>



#demo-wrapper {
    background:#0D95BB;
    margin: 80px auto 100px;
    overflow: hidden;
    padding: 10px;
    width: 550px;
    border-radius: 30px 30px 30px 30px;
	border: 1px solid #0D95BB;
    box-shadow: 0 0 10px rgba(0,0,0,0.5), 0 1px 3px rgba(255, 255, 255, 0.2) inset;
}




/* Apple-like Search Box */


#apple {
   height: 98px;
   padding: 40px 40px;
   background:#0D95BB;
}

#apple #search {

}

#apple #search input[type="text"] {
    background: url(search-white.png) no-repeat 10px 6px #444;
    border: 0 none;
    font: bold 12px Arial,Helvetica,Sans-serif;
    color: #d7d7d7;
    width:150px;
    padding: 6px 15px 6px 35px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
    text-shadow: 0 2px 2px rgba(0, 0, 0, 0.3); 
    -webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 3px rgba(0, 0, 0, 0.2) inset;
    -moz-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 3px rgba(0, 0, 0, 0.2) inset;
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 3px rgba(0, 0, 0, 0.2) inset;
    -webkit-transition: all 0.7s ease 0s;
    -moz-transition: all 0.7s ease 0s;
    -o-transition: all 0.7s ease 0s;
    transition: all 0.7s ease 0s;
    }

#apple #search input[type="text"]:focus {
    background: url(search-dark.png) no-repeat 10px 6px #fcfcfc;
    color: #6a6f75;
    width: 200px;
    -webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(0, 0, 0, 0.9) inset;
    -moz-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(0, 0, 0, 0.9) inset;
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(0, 0, 0, 0.9) inset;
    text-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
    }


</style>
</head>

<body>

<header> 
        <section id="masthead"> 
 
            <div id='logo'> 
            <img src='images/VimeoButton1.jpg'/></a> 
            </div> 

 
            </section><!-- #masthead --> 
    </header><!-- #header --> 

<h1 class='title'>Vimeo Files Search</h1>
<div id="demo-wrapper">

<div id="apple">
   

<form name="search" id="search" method="post" action="<?php echo $PHP_SELF; ?>">
        <td>
            <table name="search" align="center" width="100%" border="0" cellpadding="2" cellspacing="2">
                <tr><td align="center"><font color='white'>Title: </font><input name="title" type="text" size="40" placeholder="Title" /> </td></tr>
                <tr><td></td></tr>
                <tr><td align="center"><input type="submit" name="query" value="Search"></td></tr>
           </table>
        </td>
</form>
</tr>


</div>

</div>
<div class="tutorial"></div>

</body>
</html>

<?php

if (isset($_POST['query'])) {
	$title=$_POST['title'];
	$year=$_POST['year'];

	$title=strtoupper(str_replace(" ","_",$_POST['title']));
        $str="%".$title."__%";

	$files=array();
        echo "<table width='1500' border='1' align='center' cellpadding='0' cellspacing='1' >";
        echo "<tr>";
        echo "<form name='form1' id='form1' method='post' action='process_job.php'>";
        echo "<td>";
        echo "<table name='search' align='center' width='100%' border='0' cellpadding='7' cellspacing='0'>";

         echo "<tr><td> <input type='checkbox' id='selecctall'>Select All</td></tr>";
         echo "<tr><th>File Name</th><th>Arrival Date</th></tr>";
        $query=mysql_query("SELECT file_name, date from `vimeo_files` where file_name like '$str' order by file_name") or die(mysql_error());
        while ($row= mysql_fetch_array($query)){
                $val=$row['file_name'];
                echo "<tr><td> <input type='checkbox' class='checkbox1' name='files[]' value='$val'>".$val."</td>";
                echo "<td>".$row['arrival_date']."</td>";


        }
        echo "<tr><td><input type='submit' name='submit' value='Submit'></td></tr>";
        echo "</table>";
        echo "</td>";
        echo "</form>";
        echo "</tr>";
        echo "</table>";


}


?>
