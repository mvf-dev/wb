
<?php
session_start();
mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
mysql_select_db("itunes_status") or die(mysql_error());

if(isset($_POST['submit'])) {
	$id=$_POST['id'];
	$encode_house=$_POST['encode_house'];
	$provider_name=$_POST['provider_name'];
	$provider_shortname=$_POST['provider_shortname'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$transporter_proto_script=$_POST['transporter_proto_script'];
	$source_path=$_POST['source_path'];
	$transport_path=$_POST['transport_path'];
	$archive_path=$_POST['archive_path'];
	$failure_path=$_POST['failure_path'];
	$log_path=$_POST['log_path'];
	$summary_path=$_POST['summary_path'];

	$query=mysql_query("UPDATE `encode_houses` SET `encode_house`='$encode_house',`provider_name`='$provider_name',`provider_shortname`='$provider_shortname',`username`='$username',`password`='$password',`transporter_proto_script`='$transporter_proto_script',`source_path`='$source_path',`transport_path`='$transport_path',`archive_path`='$archive_path',`failure_path`='$failure_path',`log_path`='$log_path',`summary_path`='$summary_path' WHERE id='$id'") or die(mysql_error());

	mysql_close();
	$_SESSION['message'] = 'Encode house successfully updated';
	header( 'Location: http://dm-mediainfo-2/itunes_transporter' ) ;
}

if(isset($_POST['add'])) {
	$_SESSION['message'] = 'New encode house successfully added';
	$encode_house=$_POST['encode_house'];
	$provider_name=$_POST['provider_name'];
	$provider_shortname=$_POST['provider_shortname'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$transporter_proto_script=$_POST['transporter_proto_script'];
	$source_path=$_POST['source_path'];
	$transport_path=$_POST['transport_path'];
	$archive_path=$_POST['archive_path'];
	$failure_path=$_POST['failure_path'];
	$log_path=$_POST['log_path'];
	$summary_path=$_POST['summary_path'];

	$query=mysql_query("INSERT INTO `encode_houses`(`encode_house`, `provider_name`, `provider_shortname`, `username`, `password`, `transporter_proto_script`, `source_path`, `transport_path`, `archive_path`, `failure_path`, `log_path`, `summary_path`) VALUES ('$encode_house','$provider_name','$provider_shortname','$username','$password','$transporter_proto_script','$source_path','$transport_path','$archive_path','$failure_path','$log_path','$summary_path')") or die(mysql_error());

	mysql_close();
	header( 'Location: http://dm-mediainfo-2/itunes_transporter' ) ;
}
?>