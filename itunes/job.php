<?php
include('header.php');
include('./db/connection.php');
@ob_start();
session_start();
$now=time();
if(empty($_SESSION['group'])) {
	header( 'Location: index.php' ) ;
}
else if($now > $_SESSION['expire']) {
	session_destroy();
            echo "Your session has expired! <a href='http://http://dm-mediainfo-2/battle/'>Login here</a>";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
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
<form name="form1" id="form1" method="post" action="<?php echo $PHP_SELF; ?>">
        <td>
            <table name="search" align="center" width="100%" border="0" cellpadding="7" cellspacing="0">
                <tr><td align="center"><h3>Netflix Files Search</h3></td></tr>
                <tr><td align="center">Title: <input name="title" id="title" type="text"> </td></tr>
                <tr><td align="center">Year: <input name="year" id="year" type="yer"> </td></tr>
                <tr><td align="center"><input type="submit" name="query" value="Search"></td></tr>
           </table>
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
	$priority=$_POST['priority'];
        $str="%_".$title."_".$year."_%";
        $_SESSION['title'] = strtolower(str_replace("_","-",$title));
        $_SESSION['year'] = strtolower($year);
	$files=array();
	$i=0;
        echo "<table width='1500' border='1' align='center' cellpadding='0' cellspacing='1' >";
        echo "<tr>";
        echo "<form name='form1' id='form1' method='post' action='copy.php'>";
        echo "<td>";
        echo "<table name='search' align='center' width='100%' border='0' cellpadding='7' cellspacing='0'>";
	echo "<tr><td>Job Name: <input name='job' id='job' type='text'></td></tr>";
	 echo "<tr><td>Set Job Priority: <select name='priority'> <option value='low'>Low</option>
                <option value='high'>High</option></select></td></tr>";
	 echo "<tr><td> <input type='checkbox' id='selecctall'>Select All</td></tr>";
	 echo "<tr><th>File Name</th><th>Arrival Date</th><th>Language</th></tr>";
        $query=mysql_query("SELECT file_name, arrival_date from `netflix_files` where file_name like '$str' order by file_name") or die(mysql_error());
        while ($row= mysql_fetch_array($query)){
                $val=$row['file_name'];
                echo "<tr><td> <input type='checkbox' class='checkbox1' name='files[$i][0]' value='$val'>".$val."</td>";
		echo "<td>".$row['arrival_date']."</td>";
		echo "<td><input type='text' placeholder='zz' name='files[$i][1]'  maxlength='2' size='2'></td></tr>";
		$i++;
        }
        echo "<tr><td><input type='submit' name='submit' value='Submit'></td></tr>";
        echo "</table>";
        echo "</td>";
        echo "</form>";
        echo "</tr>";
        echo "</table>";

}

?>

