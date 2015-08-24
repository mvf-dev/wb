<html>
<meta http-equiv="refresh" content="9;url=http://dm-mediainfo-2/netflix/netflix_staging.php">
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
mysql_connect("localhost:3306", "root", "ZWPCZJnx") or die(mysql_error());
mysql_select_db("netflix") or die(mysql_error());
date_default_timezone_set('America/Los_Angeles');
$name="netflix_staging_".date('m-d-Y-His').".txt";
$myFile ="/mnt/ifs/Netflix/wb_netflix_automation/06-for-delivery/".$name;
$fh = fopen($myFile, 'w') or die("can't open file");


//include('Net/SSH2.php');
//$ssh = new Net_SSH2('172.27.2.4');
//$ssh->login('root', 'nafp123') or die("Login failed");

if(isset($_POST['submit'])) {
	$files=$_POST['files'];
	foreach ($files as $file) {		
		echo $file." has been move to staging. <br>";
		//$str.=$file."\n";
		$timeNow = date('Y-m-d H:i:s');
		fwrite($fh,$file."\n");

	}
	//fwrite($fh,$str);
}
fclose($fh);

//$cmd="php /home/kevin/netflix_staging.php ".$name." > /dev/null &";
//$ssh->exec($cmd);
$query=mysql_query("INSERT into `jobs_queue`(`job_type`,`file_name`,`date_submit`) values ('netflix-staging','$name', '$timeNow')") or die(mysql_error());
//$ssh->exec('logout');
//$ssh->disconnect();
?>

