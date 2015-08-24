<?php
include('../header.php');
mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
mysql_select_db("netflix_staging") or die(mysql_error());
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
<SCRIPT SRC="../scripts/jquery-1.9.1.js"> </SCRIPT>
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
<body>
<table width="400" border="1" align="center" cellpadding="0"
                cellspacing="1" >
<tr>
<form name="form1" id="form1" method="post" action="<?php echo $PHP_SELF; ?>">
        <td>
            <table name="search" align="center" width="100%" border="0" cellpadding="7" cellspacing="0">
                <tr><td align="center"><h3>SDI Proxy Staging Files Search</h3></td></tr>
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
        $str="%_".$title."_".$year."_%";
        $_SESSION['title'] = strtolower(str_replace("_","-",$title));
        $_SESSION['year'] = strtolower($year);
        echo "<table width='1500' border='1' align='center' cellpadding='0' cellspacing='1' >";
        echo "<tr>";
        echo "<form name='form1' id='form1' method='post' action='move.php'>";
        echo "<td>";
        echo "<table name='search' align='center' width='100%' border='0' cellpadding='7' cellspacing='0'>";
	 echo "<tr><td colspan='3'> <input type='checkbox' id='selecctall'>Select All</td></tr>";
	 echo "<tr><th width='70%'>File Name</th><th width='15%'>Status</th><th width='15%'>Time Stamp</th></tr>";
        $query=mysql_query("SELECT file_name, path,status,time_stamp from `sdi_staging` where file_name like '$str' order by file_name") or die(mysql_error());
        while ($row= mysql_fetch_array($query)){
                $val=$row['file_name'];
		$loc=$row['path'];
		//$tmp=explode("/",$loc);
		//$tmp1=$tmp[count($tmp)-1];
		//$tmp2=$tmp[count($tmp)-2];
		//$final=$tmp2."/".$tmp1."/".$val;
                echo "<tr><td> <input type='checkbox' class='checkbox1' name='files[]' value='$val'>".$val."</td>";
		echo "<td>".$row['status']."</td>";
		echo "<td>".$row['time_stamp']."</td>";
        }
        echo "<tr><td colspan='3'><input type='submit' name='submit' value='Submit'></td></tr>";
        echo "</table>";
        echo "</td>";
        echo "</form>";
        echo "</tr>";
        echo "</table>";

}

?>

