<?php
@ob_start();
session_start();
include ('./db/connection.php');

	$id = $_GET ['id'];
	
	$query=mysql_query("UPDATE `jobs_queue` SET `processing`=0,`mover_id`=0 WHERE id='$id'") or die ( mysql_error () );
	if(mysql_affected_rows()==1) {
		echo '<script type="text/javascript"> if (confirm("Job requeue successful?") == true) {
    	window.location ="report.php";
    } </script>';

	}
	else {
		echo '<script type="text/javascript">if (confirm("Job requeue fail?") == true) {
    	window.location ="report.php";
    }</script>';
		
	}

?>
