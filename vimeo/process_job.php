<html>

<meta http-equiv="refresh" content="9;url=http://dm-mediainfo-2/vimeo/">

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
include("db/connect.php");

if(isset($_POST['submit'])) {
	$files=$_POST['files'];
	foreach($files as $file) {
		$title=explode(".",$file);
		$result=mysql_query("INSERT INTO `vimeo_job`(`files`,`title`, `process`) VALUES ('$file','$title[0]',0)") or die(mysql_error());
		echo $file." has been added to the queue for upload. <br>";
	}
}


?>
