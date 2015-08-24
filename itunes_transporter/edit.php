<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
		mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
		mysql_select_db("itunes_status") or die(mysql_error());
		$id=$_GET['id'];
		?>
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
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
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
						<li><a class="ajax-link" href="http://dm-mediainfo-2/itunes_transporter/add.php">Add New Encode House</a></li>
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

				<div class="box">
			<div class="box-header">
				<div class="box-name">
				</div>
				
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
				<h4 class="page-header">Add New Encode House</h4>
				<form class="form-horizontal" role="form" action="http://dm-mediainfo-2/itunes_transporter/update.php" method="post">
					<div class="form-group">
						<?php 

							$query=mysql_query("SELECT `id`,`encode_house` , `provider_name` , `provider_shortname` , `username` , `password` , `transporter_proto_script` , `source_path` , `transport_path` , `archive_path` , 
													`failure_path` , `log_path` , `summary_path` FROM encode_houses where id='$id'") or die(mysql_error());
							while($row=mysql_fetch_array($query)) {
								echo "<input type='hidden' name='id' value=".$row['id']." >";
								echo "<label class='col-sm-2 control-label'>Encode House</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='encode_house' class='form-control' placeholder='Enocode House' data-toggle='tooltip' data-placement='bottom' title='Tooltip for encode house' value=".$row['encode_house'] ."></div>";
					
								echo "<label class='col-sm-2 control-label'>Provider Name</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='provider_name' class='form-control' placeholder='Provider Name' data-toggle='tooltip' data-placement='bottom' title='Tooltip for short name' value=".$row['provider_name'] ."></div>";
								
								echo "<label class='col-sm-2 control-label'>Provider Short Name</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='provider_shortname' class='form-control' placeholder='Provider Short Name' data-toggle='tooltip' data-placement='bottom' title='Tooltip for short name' value=".$row['provider_shortname'] ."></div>";
								
								echo "<label class='col-sm-2 control-label'>Username</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='username' class='form-control' placeholder='Username' data-toggle='tooltip' data-placement='bottom' title='Tooltip for username' value=".$row['username'] ."></div>";
					
								echo "<label class='col-sm-2 control-label'>Password</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='password' class='form-control' placeholder='Password' data-toggle='tooltip' data-placement='bottom' title='Tooltip for passowrd' value=".$row['password']."></div>";
						
								echo "<label class='col-sm-2 control-label'>Transporter Proto Script</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='transporter_proto_script' class='form-control' placeholder='Transporter Proto Script' data-toggle='tooltip' data-placement='bottom' title='Tooltip for transporter script' value=".$row['transporter_proto_script'] ."></div>";
								
								echo "<label class='col-sm-2 control-label'>Source Path</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='source_path' class='form-control' placeholder='Source Path' data-toggle='tooltip' data-placement='bottom' title='Tooltip for last source path' value=".$row['source_path']."></div>";
							
								echo "<label class='col-sm-2 control-label'>Transport Path</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='transport_path' class='form-control' placeholder='Transport Path' data-toggle='tooltip' data-placement='bottom' title='Tooltip for transport path' value=".$row['transport_path']."></div>";
								
								echo "<label class='col-sm-2 control-label'>Archive Path</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='archive_path' class='form-control' placeholder='Archive Path' data-toggle='tooltip' data-placement='bottom' title='Tooltip for archive path' value=".$row['archive_path']."></div>";
								
								echo "<label class='col-sm-2 control-label'>Failure Path</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='failure_path' class='form-control' placeholder='Failure Path' data-toggle='tooltip' data-placement='bottom' title='Tooltip for failure path' value=".$row['failure_path']."></div>";
								
								echo "<label class='col-sm-2 control-label'>Log Path</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='log_path' class='form-control' placeholder='Log Path' data-toggle='tooltip' data-placement='bottom' title='Tooltip for log path' value=".$row['log_path']."></div>";
								
								echo "<label class='col-sm-2 control-label'>Summary Path</label>";
								echo "<div class='col-sm-4'>";
								echo "<input type='text' name='summary_path' class='form-control' placeholder='Summary Path' data-toggle='tooltip' data-placement='bottom' title='Tooltip for summary path' value=".$row['summary_path']."></div>";
								
							}
						?>
						<div>
							<input type="submit" class="btn btn-primary btn-label-left" name="submit" value="submit" onClick='alert("Are your sure?")'>
						</div>
					</div>
				</form>
			</div>
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
<script src="js/devoops.js"></script>
</body>
</html>
