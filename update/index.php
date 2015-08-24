<?php
session_start();

include('db/connect.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<link rel="stylesheet" type="text/css" href="css/header.css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css'/> 
    <link media="screen" rel="stylesheet" href="css/demo-style.css" /> 
<!--[if lt IE 9]>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<title>iTunes File Search</title>
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
    background:#555;
    margin: 80px auto 100px;
    overflow: hidden;
    padding: 10px;
    width: 550px;
    border-radius: 30px 30px 30px 30px;
    border: 1px solid #444444;
    box-shadow: 0 0 10px rgba(0,0,0,0.5), 0 1px 3px rgba(255, 255, 255, 0.2) inset;
}




/* Apple-like Search Box */


#apple {
   height: 98px;
   padding: 40px 40px;
   background:#555;
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
            <img src='images/images.jpg'/></a> 
            </div> 


<ul id="menu">
<li><a href="http://dm-mediainfo-2/xbox/">Xbox Files Search</a></li>
<li><a href="http://dm-mediainfo-2/itunes/">iTunes Files Search</a></li>
<li><a href="http://dm-mediainfo-2/">Netflix Files Search</a></li>
<li><a href="http://dm-mediainfo-2/xbox/report.php">XBox Job Report</a></li>

</ul>


 
            </section><!-- #masthead --> 
    </header><!-- #header --> 
<h1 class='title'>Xbox Files Search</h1>
<div id="demo-wrapper">

<div id="apple">
<form name="form1" id="form1" method="post" action="<?php echo $PHP_SELF; ?>">
        <td>
            <table name="search" align="center" width="100%" border="0" cellpadding="7" cellspacing="0">
       
                <tr><td align="center"><font color='white'>Title: </font><input name="title" id="title" type="text"> </td></tr>
                <tr><td align="center"><font color='white'>Year: </font><input name="year" id="year" type="yer"> </td></tr>
                <tr><td align="center"><input type="submit" name="query" value="Search"></td></tr>
           </table>
        </td>
</form>
</tr>
</div>

</div>
<div class="tutorial"></div>
</body>
<br>
<?php
if(isset($_POST['query'])) {
        $title=strtoupper(str_replace(" ","_",$_POST['title']));
        $year=strtoupper(str_replace(" ","",$_POST['year']));
        $str="%_".$title."%";
        $_SESSION['title'] = strtolower(str_replace("_","-",$title));
        if(!empty($year)) {
        	$_SESSION['year'] = strtolower($year);
        }
 
        $files=array();
        echo "<table width='1500' border='1' align='center' cellpadding='0' cellspacing='1' >";
        echo "<tr>";
        echo "<form name='form1' id='form1' method='post' action='transcode.php'>";
        echo "<td>";
        echo "<table name='search' align='center' width='100%' border='0' cellpadding='7' cellspacing='0'>";
        echo "<tr><td>Job Name: <input name='job' id='job' type='text'></td></tr>";
        echo "<tr><td>Set Job Priority: <select name='priority'> <option value='low'>Low</option>";
        
         echo "<tr><td> <input type='checkbox' id='selecctall'>Select All</td></tr>";
         echo "<tr><th>File Name</th><th>Arrival Date</th></tr>";
        $query=mysql_query("SELECT file_name, arrival_date from `netflix_files` where file_name like '$str' order by file_name") or die(mysql_error());
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

