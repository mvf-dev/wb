<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Netflix Report</title>
<link rel="stylesheet" type="text/css" href="DataTables-1.10.4/media/css/jquery.dataTables.css">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="DataTables-1.10.4/media/js/jquery.js"></script>

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="DataTables-1.10.4/media/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript">
$(document).ready(function() {
    $('#report').DataTable();
} );

function verified(value) {
    
    if (confirm("Are you sure you want to requeue this job?") == true) {
    	window.location ='requeue.php?id='+value;
    } 
    else {
		return false;
        }
}
</script>
</head>
<body>

<?php
include ('header.php');
include ('./db/connection.php');
@ob_start();
$group=$_SESSION['group'];
if(empty($group)) {
	header("Location: index.php");
}
echo"<br>";
echo"<br>";
echo"<br>";
echo "<h3 align='center'>Job Status Report</h3>";
echo "<table width='900' bgcolor='white' border='1' align='center' cellpadding='0' cellspacing='1' >";
echo "<tr>";
echo "<form name='form1' id='form1' method='post' action='requeue.php'>";
echo "<td>";
echo "<table id='report' class='display' align='center' width='100%' border='0' cellpadding='1' cellspacing='0'>";
echo "<thead>";
echo "<tr><th>Job ID</th><th>Job Title</th><th>Job Priority</th><th>Job Submit Date</th><th>Job Status</th><th>Mover</th>";
	if($group=="admin"){
		echo "<th>Requeue Job</th></tr>";
	}
	else {
		
		echo "</tr>";
	}
echo "</thead>";
echo "<tbody>";
$query = mysql_query ( "SELECT id ,job_name,date_submit,priority, processing, mover_id from jobs_queue" ) or die ( mysql_error () );
while ( $row = mysql_fetch_array ( $query ) ) {
		$jobName=$row['job_name'];
        $priority = $row ['priority'];
        if($row ['processing']==1) {
                $process="Processing";
        }
        else {
                $process="In Queue";
        }

        $id = $row['id'];
        $mover=$row['host'];
        $date = $row['date_submit'];
        echo "<tr><td align='center'>".$id."</td><td align='center'>".$jobName."</td> <td align='center'>".$priority."</td><td align='center'> ".$date."</td>
                <td align='center'>".$process."</td><td align='center'>".$mover."</td><td><button type='button' id='job' value='$id' onclick='verified(this.value)'>Requeue</button></td></tr>";

}
echo "</tbody>";
echo "<tr><td><input type='submit' name='submit' value='Submit'></td></tr>";

echo "</table>";
echo "</td>";
echo "</form>";
echo "</tr>";
echo "</table>";
?>
