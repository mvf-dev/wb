<html>


<meta http-equiv="refresh" content="9;url=http://dm-mediainfo-2/">

<script type="text/javascript">

(function () {
    var timeLeft = 8,
        cinterval;

    var timeDec = function (){
        timeLeft--;
        document.getElementById('countdown').innerHTML = timeLeft;
        if(timeLeft === 0){
            clearInterval(cinterval);
        }
    };

    cinterval = setInterval(timeDec, 1000);
})();

</script>
Please wait while we redirect you back to the homepage in
<span id="countdown">5</span>
<br>

</html>
<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
$name="files".date('m-d-Y-His').".txt";
$myFile = "/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$name;
//$myFile = $name;
$fh = fopen($myFile, 'w') or die("can't open file");

mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
mysql_select_db("netflix") or die(mysql_error());

include('Net/SSH2.php');
$ssh = new Net_SSH2('172.27.2.4');
$ssh->login('root', 'nafp123') or die("Login failed");

if(isset($_POST['submit'])) {
	$files=$_POST['files'];
	$territory=$_POST['territory'];
	$title = $_SESSION['title'];
	$year = $_SESSION['year'];
	//$folder=$title."-".$year;
	for($i=0; $i<sizeof($files); $i++) {
                if(!empty($files[$i][0])) {
                        $temp=explode("_",$files[$i][0]);
                        break;
                }
        }

	$folder=$temp[0]."-".$title."-".$year;
	echo "+ ".$folder."<br>";
	$directory="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder;
	$sdi_directory="/mnt/fs4_wb_netflix_automation/".$folder."/sdi-proxy";
	$qc_directory="/mnt/fs4_wb_netflix_automation/".$folder."/qc-proxy";
	//$ep_dir="";

	if(!file_exists($directory)) {
		$output= shell_exec("mkdir '$directory'");
	}

	for($i=0; $i<sizeof($files); $i++) {
		$ep="";
		if(!empty($files[$i][0])) {

			//echo "&nbsp-".$files[$i][0]." and ".$files[$i][1]." has been added <br>";
			$ext=explode(".",$files[$i][0]);
			if($ext[1]=="m2t") {
				$tmp=explode("YR",$files[$i][0]);
				if(sizeof($tmp[1])==0) {
					$ep=strtolower($ext[0]);

					if(!empty($files[$i][1])) {
						$e_dir=$ep.$files[$i][1];
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					else {
						$e_dir=$ep."-zz";
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					echo "&nbsp + ".$e_dir."<br>";
					echo "&nbsp &nbsp -".$files[$i][0]." has been added <br>";
					fwrite($fh, $files[$i][0]." ".$folder."/".$e_dir."\n");

				}
				else {
					$str=explode("_",$tmp[1]);
					for($j=3; $j<sizeof($str)-1; $j++) {
						$ep.=strtolower($str[$j])."-";
					}
					if(!empty($files[$i][1])) {
						$e_dir=substr($ep,0,-1)."-".$files[$i][1];
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					else {
						$e_dir=substr($ep,0,-1)."-zz";
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					echo "&nbsp + ".$e_dir."<br>";
					echo "&nbsp &nbsp -".$files[$i][0]." has been added <br>";
					fwrite($fh, $files[$i][0]." ".$folder."/".$e_dir."\n");
				}

				//echo "&nbsp + ".$e_dir."<br>";
				//echo "&nbsp &nbsp -".$files[$i][0]." has been added <br>";
				//echo $files[$i]." ".$folder."/".$e_dir."<br>";
				$file=$files[$i][0];
				$qc_proxy=$folder."/".$e_dir;
				$query=mysql_query("SELECT frame_rate, scan_type, audio_channel, aspect_ratio from `netflix_files` where file_name like '$file'") or die(mysql_error());
				while ($row= mysql_fetch_array($query)){
					if(($row['frame_rate']=="23.976")&&($row['scan_type']=="Progressive")) {
						if($row['aspect_ratio']=="16:9") {
							if($row['audio_channel']=="2") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_82_Netflix_Proxy_p2398_16x9_2ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}

							else if($row['audio_channel']=="8") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_83_Netflix_Proxy_p2398_16x9_8ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}

						}
						else if($row['aspect_ratio']=="4:3") {
							if($row['audio_channel']=="2") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_90_Netflix_Proxy_p2398_4x3_2ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}
							
							else if($row['audio_channel']=="8") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_91_Netflix_Proxy_p2398_4x3_8ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}
						}
					}

					else if($row['frame_rate']=="29.97") {
						if($row['aspect_ratio']=="16:9") {
							if($row['audio_channel']=="2") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_84_Netflix_Proxy_i2997_16x9_2ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}

							else if($row['audio_channel']=="8") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_85_Netflix_Proxy_i2997_16x9_8ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}

						}
						else if($row['aspect_ratio']=="4:3") {
							if($row['audio_channel']=="2") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_92_Netflix_Proxy_2997_4x3_2ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}
						
							else if($row['audio_channel']=="8") {
								$cmd="sed 's|Input_File_Pathname|$file|\n s|QC_Proxy_Directory_Name|$qc_proxy|' jobs_xml/Job_Profile_93_Netflix_Proxy_2997_4x3_8ch.xml |\\\n curl --header 'Accept: application/xml' --header 'Content-type: application/xml' --data @- http://172.25.137.31/api/v2.4.1/jobs";
								$output=shell_exec($cmd);
							}
						
						}
					}

				}
			}
			else {
				$tmp=explode("YR",$files[$i][0]);

				if(sizeof($tmp[1])==0) {
					$ep=strtolower($ext[0]);
					if(!empty($files[$i][1])) {
						$e_dir=$ep.$files[$i][1];
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					else {
						$e_dir=$ep."-zz";
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}

					echo "&nbsp + ".$e_dir."<br>";
					echo "&nbsp &nbsp -".$files[$i][0]." has been added 1<br>";
					fwrite($fh, $files[$i][0]." ".$folder."/".$e_dir."\n");
				}
				else {
					$check_str=explode("_",$tmp[0]);
					if(preg_match("/^[a-zA-Z]+$/",$check_str[0])) {
						$ep=strtolower(str_replace("_","-",$tmp[0]));
						if(!empty($files[$i][1])) {
							$e_dir=$ep.$files[$i][1];
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
							if(!file_exists($ep_dir)) {
								$output= shell_exec("mkdir '$ep_dir'");
							}
						}
						else {
							$e_dir=$ep."zz";
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
							if(!file_exists($ep_dir)) {
								$output= shell_exec("mkdir '$ep_dir'");
							}
						}
							
						echo "&nbsp + ".$e_dir."<br>";
						echo "&nbsp &nbsp -".$files[$i][0]." has been added 2<br>";
						fwrite($fh, $files[$i][0]." ".$folder."/".$e_dir."\n");
					}
					else {
						$str=explode("_",$tmp[1]);
						for($j=3; $j<sizeof($str)-2; $j++) {
							$ep.=strtolower($str[$j])."-";
						}

						if(!empty($files[$i][1])) {
							$e_dir=substr($ep,0,-1)."-".$files[$i][1];
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
							if(!file_exists($ep_dir)) {
								$output= shell_exec("mkdir '$ep_dir'");
							}
						}
						else {
							$e_dir=substr($ep,0,-1)."-zz";
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/04-for-qc/".$folder."/".$e_dir;
							if(!file_exists($ep_dir)) {
								$output= shell_exec("mkdir '$ep_dir'");
							}
						}

						echo "&nbsp + ".$e_dir."<br>";
						echo "&nbsp &nbsp -".$files[$i][0]." has been added 3<br>";
						fwrite($fh, $files[$i][0]." ".$folder."/".$e_dir."\n");

					}
				}
			}

		}

	}
}
fclose($fh);
$timeNow = date('Y-m-d H:i:s');
$cmd="php /home/kevin/copy.php ".$name." > /dev/null &";
$ssh->exec($cmd);
//$query=mysql_query("INSERT into `jobs_queue`(`job_type`,`file_name`,`date_submit`) values ('netflix','$name', '$timeNow')") or die(mysql_error());
mysql_close();
$ssh->exec('logout');
$ssh->disconnect();
?>

