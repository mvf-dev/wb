<?php
$folder="angel";
$primary_dir="/mnt/dm-qc/ingest/05-itunes-encoding/warner/00-media/00-incoming-media/DETE/01-transcode-complete/angel";
if(!file_exists($primary_dir)) {
		$output= shell_exec("mkdir '$primary_dir'");
	}
?>
