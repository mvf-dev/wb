<?php
//$cmd="curl  --data 'source=1734278_1_2_E1985639_WHOSE_LINE_IS_IT_ANYWAY_YR03_00_01_SHOW_346_AIRED_IN_YEAR_6_7858062.mxf&destination=whose' http://172.26.80.201/api/xbox/addJob";
//$output=shell_exec($cmd);

$str="{Source:1734278_1_2_E1985639_WHOSE_LINE_IS_IT_ANYWAY_YR03_00_01_SHOW_346_AIRED_IN_YEAR_6_7858062.mxf,Job_Id:912}";

//echo $str;
$tmp=explode("Source:",$str);
$file=explode(",",$tmp[1]);
$id=explode("Job_Id:", $str);

echo substr($id[1],0,-1);
//echo $file[0];
?>
