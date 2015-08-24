<html>


<meta http-equiv="refresh" content="9;url=http://dm-mediainfo-2/itunes/">

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
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
include("db/connect.php");

date_default_timezone_set('America/Los_Angeles');
$name="itunes".date('m-d-Y-His').".txt";
$myFile = "/mnt/dm-qc/ingest/05-itunes-encoding/warner/00-media/00-incoming-media/DETE/".$name;
$fh = fopen($myFile, 'w') or die("can't open file");
$date=date('Y-m-d H:i:s');
if(isset($_POST['submit'])) {
	$files=$_POST['files'];
	$title = $_SESSION['title'];
	$year = $_SESSION['year'];
	$priority = $_POST['priority'];
	$jobName=$_POST['job'];
	
	if(!empty($year)) {
		$folder=$title."-".$year;
	}
	else {
		$folder=$title;
	}
	$primary_dir="/mnt/dm-qc/ingest/05-itunes-encoding/warner/00-media/00-incoming-media/DETE/01-transcode-complete/".$folder;
	$secondary_dir="/mnt/dm-qc/ingest/05-itunes-encoding/warner/00-media/00-incoming-media/DETE/00-incoming/".$folder;
	
	foreach($files as $file) {
		//echo $file."\n";
		$tmp=explode(".",$file);
		//echo $tmp[1];
		if($tmp[1]=="mxf"||$tmp[1]=="m2t") {
			 if(!file_exists($primary_dir)) {
                		$output= shell_exec("mkdir '$primary_dir'");
        		}

			$cmd="curl  --data 'source=".$file."&destination=".$folder."' http://172.26.80.201/api/itunes/addJob";  
			$output=shell_exec($cmd);
			$tmp=json_decode($output);
                        $file=$tmp->{'Source'};
                        $job_id=$tmp->{'Job_Id'};
                        $id=explode("Job_Id:", $output);

                        $query=mysql_query("INSERT into `transcode_jobs`(`job_id`,`job_type`, `job_name`, `submit_time`) values ('$job_id','itunes','$file','$date')") or die(mysql_error());

                        echo "Submit";

		}
		else {
			 if(!file_exists($secondary_dir)) {
                		$output= shell_exec("mkdir '$secondary_dir'");
		        }

			fwrite($fh, $file." /00-incoming/".$folder."\n");
			$query=mysql_query("INSERT into `transcode_jobs`(`job_id`,`job_type`, `job_name`, `submit_time`) values ('$job_id','itunes','$file','$date')") or die(mysql_error());
			echo "file ".$file." has copy to ".$folder."<br>";
		}
		
	}	
	fclose($fh);
}
//$date=date('Y-m-d H:i:s');
$query=mysql_query("INSERT into `jobs_queue`(`job_name`,`job_type`,`file_name`,`priority`,`date_submit`) values ('$jobName','itunes','$name', '$priority', '$date')") or die(mysql_error());
mysql_close();

?>




