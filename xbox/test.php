<?php
$cmd="curl  --data 'job_id=920' http://172.26.80.201/api/job/status";
$output=shell_exec($cmd);

$tmp = json_decode($output);
//$str=explode("status",$tmp[0]);
echo $tmp->{'Status'};
//var_dump($output);
?>
