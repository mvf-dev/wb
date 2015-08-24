<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>iTunes Job Status</title>
<link rel="stylesheet" type="text/css" href="DataTables-1.10.4/media/css/jquery.dataTables.css">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="DataTables-1.10.4/media/js/jquery.js"></script>

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="DataTables-1.10.4/media/js/jquery.dataTables.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#report').DataTable();
} );


</script>
</head>
<body>

<?php
include ('header.php');
session_start();
include("db/connect.php");
echo"<br>";
echo"<br>";
echo"<br>";
echo "<h3 align='center'>Job Status Report</h3>";
echo "<table width='1500' bgcolor='white' border='1' align='center' cellpadding='0' cellspacing='1' >";
echo "<tr>";
echo "<td>";

echo "<table id='report' class='display' align='center' width='100%' border='0' cellpadding='1' cellspacing='0'>";
echo "<thead>";
echo "<tr><th>Job ID</th><th>Job Type</th><th>Job Name</th><th>Job Status</th><th>Date Submit</th></tr>";
echo "</thead>";
echo "<tbody>";
$result = mysql_query("SELECT job_id, job_type, job_name, submit_time from `transcode_jobs` where job_type like 'itunes'") or die ( mysql_error () );
while ($row=mysql_fetch_array($result)) {
	$id=$row['job_id'];
	$tmp=explode(".",$row['job_name']);

	if($tmp[1]=="mxf"||$tmp[1]=="m2t") {

		$cmd="curl  --data 'job_id=".$id."' http://172.26.80.201/api/job/status";
		$output=shell_exec($cmd);
		$tmp = json_decode($output);
		$status=$tmp->{'Status'};
	
		echo "<tr><td align='center'>".$id."</td><td align='center'>".$row['job_type']."</td><td align='center'>".$row['job_name']."</td><td align='center'>".$status."</td><td align='center'>".$row['submit_time']."</td></tr>";
	}
	else {
		$cmd="curl  --data 'file=".$row['job_name']."' http://172.27.2.5/report.php";
		$output=shell_exec($cmd);
		echo "<tr><td align='center'>".$id."</td><td align='center'>".$row['job_type']."</td><td align='center'>".$row['job_name']."</td><td align='center'>".$output."</td><td align='center'>".$row['submit_time']."</td></tr>";
	}
}

echo "</tbody>";

echo "</table>";
echo "</td>";
echo "</tr>";
echo "</table>";

?>
