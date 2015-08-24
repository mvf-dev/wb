<?php
mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
mysql_select_db("itunes_status") or die(mysql_error());
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

	//$mysql=mysql_query("SELECT `id`,`encode_house` , `provider_name` , `provider_shortname` , `username` , `password` , `transporter_proto_script` , `source_path` , `transport_path` , `archive_path` , `failure_path` , `log_path` , `summary_path` FROM encode_houses ") or die(mysql_error());
	$mysql=mysql_query("SELECT * FROM encode_houses") or die(mysql_error());

	$rows = array();
	while($r=mysql_fetch_assoc($mysql)) {

		$rows[] = $r;
		
	}
	var_dump(json_decode($rows));
	mysql_close();
}

?>