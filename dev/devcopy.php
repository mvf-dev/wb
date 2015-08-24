<html>



Please wait while we redirect you back to the homepage in
<span id="countdown">5</span>
<br>

</html>
<?php
session_start();
date_default_timezone_set('America/Los_Angeles');
$name="files".date('m-d-Y-His').".txt";
$myFile = "/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$name;
//$myFile = $name;
$fh = fopen($myFile, 'w') or die("can't open file");

mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
mysql_select_db("netflix") or die(mysql_error());


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
	$directory="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder;
	$sdi_directory="/mnt/fs4_wb_netflix_automation/".$folder."/sdi-proxy";
	$qc_directory="/mnt/fs4_wb_netflix_automation/".$folder."/qc-proxy";
	//$ep_dir="";

	if(!file_exists($directory)) {
		$output= shell_exec("mkdir '$directory'");
	}

	for($i=0; $i<sizeof($files); $i++) {
		if(!empty($files[$i][0])) {

			//echo "&nbsp-".$files[$i][0]." and ".$files[$i][1]." has been added <br>";
			$ext=explode(".",$files[$i][0]);
			if($ext[1]=="m2t") {
				$tmp=explode("YR",$files[$i][0]);
				if(sizeof($tmp[1])==0) {
					$ep=strtolower($ext[0]);

					if(!empty($files[$i][1])) {
						$e_dir=$ep.$files[$i][1];
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					else {
						$e_dir=$ep."-zz";
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
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
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					else {
						$e_dir=substr($ep,0,-1)."-zz";
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					echo "&nbsp + ".$e_dir."<br>";
					echo "&nbsp &nbsp -".$files[$i][0]." has been added <br>";
					fwrite($fh, $files[$i][0]." ".$folder."/".$e_dir."\n");
				}

				$file=$files[$i][0];
			}
			else {
				$tmp=explode("YR",$files[$i][0]);

				if(sizeof($tmp[1])==0) {
					$ep=strtolower($ext[0]);

					if(!empty($files[$i][1])) {
						$e_dir=$ep.$files[$i][1];
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
						if(!file_exists($ep_dir)) {
							$output= shell_exec("mkdir '$ep_dir'");
						}
					}
					else {
						$e_dir=$ep."-zz";
						$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
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
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
							if(!file_exists($ep_dir)) {
								$output= shell_exec("mkdir '$ep_dir'");
							}
						}
						else {
							$e_dir=$ep."zz";
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
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
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
							if(!file_exists($ep_dir)) {
								$output= shell_exec("mkdir '$ep_dir'");
							}
						}
						else {
							$e_dir=substr($ep,0,-1)."-zz";
							$ep_dir="/mnt/ifs/Netflix/wb_netflix_automation/engineering/".$folder."/".$e_dir;
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

?>

