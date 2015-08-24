<?php
mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
mysql_select_db("itunes_status") or die(mysql_error());	

			
$fh = fopen("pts.csv", 'w') or die("can't open file");
$output="";
$mysql=mysql_query("SELECT `id`,`encode_house` , `provider_name` , `provider_shortname` , `username` , `password` , `transporter_proto_script` , `source_path` , `transport_path` , `archive_path` , 
							`failure_path` , `log_path` , `summary_path` FROM encode_houses ") or die(mysql_error());

while($row=mysql_fetch_array($mysql)) {

$output.=$row['encode_house'].",".$row['provider_name'].",".$row['provider_shortname'].",".$row['username'].",".$row['password'].",".$row['transporter_proto_script'].
",".$row['source_path'].",".$row['transport_path'].",".$row['archive_path'].",".$row['failure_path'].",".$row['log_path'].",".$row['summary_path']."\n";
}

mysql_close();

fwrite($fh,"encode_house,provider_name,provider_shortname,username,password,transporter_proto_script,source_path,transport_path,archive_path,failure_path,log_path,summary_path \n");
fwrite($fh,$output);
fclose($fh);

$cmd=shell_exec('scp pts.csv root@si-mgr-1:/home/len');

//$cmd=shell_exec('ls');
echo $cmd;
?>
