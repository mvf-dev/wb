
<!DOCTYPE html>
<?php
	session_start();
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>DevOOPS v2</title>
		<meta name="description" content="description">
		<meta name="author" content="DevOOPS">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">
		<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
		<link href="plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
		<link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
		<link href="plugins/xcharts/xcharts.min.css" rel="stylesheet">
		<link href="plugins/select2/select2.css" rel="stylesheet">
		<link href="plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
		<link href="css/style_v2.css" rel="stylesheet">
		<link href="plugins/chartist/chartist.min.css" rel="stylesheet">
	
		<link rel="stylesheet" type="text/css" href="DataTables-1.10.7/media/css/jquery.dataTables.css">
  
<!-- jQuery -->
<script src="DataTables-1.10.7/media/js/jquery.js"></script>
  
<!-- DataTables -->
<script src="DataTables-1.10.7/media/js/jquery.dataTables.js"></script>

		<script>
		$(document).ready(function() {
    		$('#example').DataTable( {
    			"scrollX": true
    		});
    		
		} );

		</script>
	</head>
<body>
<!--Start Header-->
<div id="screensaver">
	<canvas id="canvas"></canvas>
	<i class="fa fa-lock" id="screen_unlock"></i>
</div>
<div id="modalbox">
	<div class="devoops-modal">
		<div class="devoops-modal-header">
		</div>
		<div class="devoops-modal-inner">
		</div>
		<div class="devoops-modal-bottom">
		</div>
	</div>
</div>
<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2">
				<a href="http://dm-mediainfo-2/itunes_transporter/">DevOOPS v2</a>
			</div>
			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
					<div class="col-xs-8 col-sm-4">
				
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!--End Header-->
<!--Start Container-->
<div id="main" class="container-fluid">
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2">
			<ul class="nav main-menu">
				<li>
					<a href="http://dm-mediainfo-2/itunes_transporter/" class="ajax-link">
						<i class="fa fa-dashboard"></i>
						<span class="hidden-xs">Dashboard</span>
					</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-bar-chart-o"></i>
						<span class="hidden-xs">Charts</span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="http://dm-mediainfo-2/itunes_transporter/add.php">Add New Encode House</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<!--Start Content-->
		<div id="content" class="col-xs-12 col-sm-10">

			<div id="about">
				<div class="about-inner">
					<h4 class="page-header">Open-source admin theme for you</h4>
					<p>DevOOPS team</p>
					<p>Homepage - <a href="http://devoops.me" target="_blank">http://devoops.me</a></p>
					<p>Email - <a href="mailto:devoopsme@gmail.com">devoopsme@gmail.com</a></p>
					<p>Twitter - <a href="http://twitter.com/devoopsme" target="_blank">http://twitter.com/devoopsme</a></p>
					<p>Donate - BTC 123Ci1ZFK5V7gyLsyVU36yPNWSB5TDqKn3</p>
				</div>
			</div>
	
			<div>

				<?php 	
						mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
						mysql_select_db("itunes_status") or die(mysql_error());
						
						if(!empty($_SESSION['message'])) {
							$message = $_SESSION['message'];
							echo "<div class='alert alert-success'>";
							echo"	<strong>Success!</strong>".$message;
							echo "</div>";
						}

						session_unset();
						session_destroy();
			?>
				
				<table id='example' class='display' cellspacing='0' width="100%">
					
					<thead>
						<tr>
							<th>Encode House</th>
							<th>Provider Name</th>
							<th>Provider Short Name</th>
							<th>User Name</th>
							<th>Password</th>
							<th>Transporter Proto Script</th>
							<th>Source Path</th>
							<th>Transport Path</th>
							<th>Archive Path</th>
							<th>Failure Path</th>
							<th>Log Path</th>
							<th>Summary Path</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Encode House</th>
							<th>Provider Name</th>
							<th>Provider Short Name</th>
							<th>User Name</th>
							<th>Password</th>
							<th>Transporter Proto Script</th>
							<th>Source Path</th>
							<th>Transport Path</th>
							<th>Archive Path</th>
							<th>Failure Path</th>
							<th>Log Path</th>
							<th>Summary Path</th>
						</tr>
					</tfoot>
 
			        <tbody>
			           <?php
								
								$mysql=mysql_query("SELECT `id`,`encode_house` , `provider_name` , `provider_shortname` , `username` , `password` , `transporter_proto_script` , `source_path` , `transport_path` , `archive_path` , 
													`failure_path` , `log_path` , `summary_path` FROM encode_houses order by 'encode_house' asc ") or die(mysql_error());

								while($row=mysql_fetch_array($mysql)) {

									echo "<tr><td>".$row['encode_house']."</td><td>".$row['provider_name']."</td><td>".$row['provider_shortname']."</td><td>".$row['username']."</td><td>".$row['password']."</td><td>".$row['transporter_proto_script'].
										"</td><td>".$row['source_path']."</td><td>".$row['transport_path']."</td><td>".$row['archive_path']."</td><td>".$row['failure_path']."</td><td>".$row['log_path']."</td><td>".$row['summary_path']."</td><td><a href=edit.php?id=".$row['id']." class='btn btn-info'>Edit</a></td></tr>";
								}

								mysql_close();
							?>
			        </tbody>
			
				</table>

				<div class="bs-example">
				
					<a href="generateCSV.php" class="btn btn-primary" >Generate CSV</a>
		
				</div>
		
		
			</div>
		</div>
		<!--End Content-->
	</div>
</div>
<!--End Container-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="http://code.jquery.com/jquery.js"></script>-->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<script src="plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
<script src="plugins/tinymce/tinymce.min.js"></script>
<script src="plugins/tinymce/jquery.tinymce.min.js"></script>
<!-- All functions for this theme + document.ready processing -->

		  
		<!-- DataTables -->
<script src="DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script src="js/devoops.js"></script>

</body>
</html>
