<?php
session_start();
include('header.php');
include('db/connect.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


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
</head>
<br>
<br>
<br>
<body>
<table width="400" border="1" align="center" cellpadding="0"
                cellspacing="1" >
<tr>
<form role="form" name="form1" id="form1" method="post" action="<?php echo $PHP_SELF; ?>">
        <td>
                <h3 align="center">Xbox Files Search</h3>
		<div class="form-group">
            	<label for="title">Title:</label>
		 <input class="form-control" placeholder="title" name="title" id="title" type="text">
		</div>
		<div class="form-group">
                <label for="year">Year:</label> 
			<input class="form-control" placeholder="year" name="year" id="year" type="text">
			<span class="help-block">For year use YR follow by 2 digits number, ie: YR01</span> 
		</div>

		<button type="submit" class="btn btn-primary" name="query">Search</button>
        </td>
</form>
</tr>
</table>
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
	echo "<div class='container-fluid'>";
        echo "<table width='1500' border='1' align='center' cellpadding='0' cellspacing='1' >";
        echo "<tr>";
        echo "<form name='form1' id='form1' method='post' action='transcode.php'>";
        echo "<td>";
        echo "<table class='table table-striped' name='search' align='center' width='100%' border='0' cellpadding='7' cellspacing='0'>";
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
	echo "</div>";

}

?>

