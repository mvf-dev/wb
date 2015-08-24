
<?php
mysql_connect("localhost:3306", "root", "password123") or die(mysql_error());
mysql_select_db("project_xml") or die(mysql_error());
mysql_query("set names 'utf8'");
header('Content-type: text/html; charset=utf-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-type" value="text/html; charset=UTF-8" />
<title>XML Search</title>

</head>

<body>
	<table width="1200" border="1" align="center" cellpadding="0"
		cellspacing="1">
		<tr>
			<form name="form1" id="form1" method="post"
				action="<?php echo $PHP_SELF; ?>">
				<td>
					<table name="search" align="center" width="100%" border="0"
						cellpadding="7" cellspacing="0">
						<tr>
							<td colspan="5" align="center"><h3>MetaData Search</h3></td>
						</tr>
						<tr>
							<td width="13%">Vendor ID:</td>
							<td><input name="vendorID" id="vendorID" type="text">
							</td>
						</tr>
						<tr>
							<td width="13%">Title:</td>
							<td><input type="text" name="title" id="title">
							</td>
						</tr>
						<tr>
							<td width="13%">Territory:</td>
							<td><input type="text" name="territory" id="territory">
							</td>
						</tr>
						<tr>
							<td width="13%">Studio:</td>
							<td><input type="text" name="studio" id="studio">
							</td>
						</tr>
						<tr>
							<td colspan="4" align="center"><input type="submit" name="submit"
								value="Submit"></td>
						</tr>
					</table>
				</td>
			</form>
		</tr>
	</table>
</body>
<br>
<br>
<br>
<?php
if(isset($_POST['submit'])) {
	$flag=0;
	echo "<table  width='1200' border='1' align='center' cellpadding='0' cellspacing='1' >";
	$title=$_POST['title'];
	$id=$_POST['vendorID'];
	$ter=$_POST['territory'];
	$studio=$_POST['studio'];
	//$sub="";
	$tmp="";
	if(empty($_POST['vendorID'])&&empty($_POST['territory'])&&empty($_POST['title'])&&empty($_POST['studio'])) {
		echo "<script type='text/javascript'>";
		echo "window.alert('Please enter at least one field!')";
		echo "</script>";
	}

	else {
		$flag=1;
		$myFile = "report-".date('m-d-Y-His').".csv";
		$date=date('m-d-Y-His');
		$fh = fopen($myFile, 'w');

		////////////////////////////////////////////// Single value check ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if(empty($_POST['vendorID'])&&empty($_POST['territory'])&&!empty($_POST['title'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Close Captions</th>";
			echo "<th>Encode House</th>";
			if(!empty($_POST['studio'])) {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, pack.encode_house as encode, pack.media_type as media,vid.original_spoken_locale as ori, pack.provider as pro, vid.title as title, pack.id as fileID, ass.id as aid,ass.asset_type as type FROM `assets` as ass, package AS pack, video AS vid
						WHERE vid.package_id = pack.id  and pack.id=ass.package_id AND ass.asset_type LIKE 'full' and vid.title like '%$title%' and pack.provider like '$studio'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid=$row['vid'];
					$encode=$row['encode'];
					$aid=$row['aid'];
					$titl=$row['title'];
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";

											}
										}
									}
								}
									
							}
						}

						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}

					else {

						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}

			else {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode,pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro, pack.id as fileID, ass.id as aid,ass.asset_type as type FROM `assets` as ass, package AS pack, video AS vid
						WHERE vid.package_id = pack.id  and pack.id=ass.package_id AND ass.asset_type LIKE 'full' and vid.title like '%$title%'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$cap="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {
							
						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}
									
							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}
							else if($au=='captions') {
								$cap="yes";
							}


							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}
									
								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$cap.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='5%'>".$med."</td><td width='5%'>".$cap."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}
											
									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='captions') {
										$cap="yes";
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$cap.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='5%'>".$med."</td><td width='5%'>".$cap."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);

									if($au='captions') {
										$cap="yes";
									}
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}
											
									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='captions') {
										$cap="yes";
									}


									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
											
										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";
															
													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";
															
													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$cap.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='5%'>".$med."</td><td width='5%'>".$cap."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
		}

		if(empty($_POST['title'])&&empty($_POST['territory'])&&!empty($_POST['vendorID'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Encode House</th>";
			if(!empty($_POST['studio'])) {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode, pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type FROM `assets` as ass, package AS pack, video AS vid
						WHERE vid.package_id = pack.id  and pack.id=ass.package_id AND ass.asset_type LIKE 'full' and vid.vendor_id='$id' and pack.provider like '$studio'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {
							
						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}
									
							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}
									
								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if(strtolower($attribute)=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}
											
									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if(strtolower($attribute)=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}
											
									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
											
										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";
															
													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";
															
													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
			else {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode,pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID,
						ass.id as aid,ass.asset_type as type FROM `assets` as ass, package AS pack, video AS vid
						WHERE vid.package_id = pack.id  and pack.id=ass.package_id AND ass.asset_type LIKE 'full' and vid.vendor_id='$id'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=trim($row['att']);
							if(strtolower($attribute)=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast
										WHERE ass.id = asse.assets_id and ast.assets_id=ass.id and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if(strtolower($attribute)=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}

		}
		if(empty($_POST['title'])&&empty($_POST['vendorID'])&&!empty($_POST['territory'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Territory </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Encode House</th>";
			if(!empty($_POST['studio'])) {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode, pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter' and pack.provider like '$studio'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
			else {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode, pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
		}


		if(empty($_POST['title'])&&empty($_POST['vendorID'])&&empty($_POST['territory'])&&!empty($_POST['studio'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Close Captions</th>";
			echo "<th>Encode House</th>";
			$str=mysql_query("SELECT vid.vendor_id as vid, vid.original_spoken_locale as ori, pack.encode_house as encode, pack.media_type as media, pack.provider as pro,vid.title as title, pack.id as fileID, ass.id as aid,ass.asset_type as type FROM `assets` as ass, package AS pack, video AS vid
					WHERE vid.package_id = pack.id  and pack.id=ass.package_id AND ass.asset_type LIKE 'full' and pack.provider like '$studio%'") or die(mysql_error());
			while ($row= mysql_fetch_array( $str )) {
				$name=$row['fileID'];
				$vid="[".$row['vid']."]";
				$aid=$row['aid'];
				$encode=$row['encode'];
				$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
				$original=$row['ori'];
				$provider=$row['pro'];
				$cap="";
				$med=$row['media'];
				$audio="";
				$subtitle="";
				$csv_audio="";
				$csv_sub="";
				$csv_native_audio="";
				$csv_force_sub="";
				$csv_att_nar="";
				$csv_att_sub="";
				$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
				if(mysql_num_rows($test)==0) {

					$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
							AND ass.id ='$aid'") or die(mysql_error());
					while($row= mysql_fetch_array( $sub1 )) {
						$au=trim($row['role']);


						if(strtolower($au)=='audio') {
							$l=trim($row['lname']);
							//$csv_audio.="[".$l."] ";
							$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
							while($row= mysql_fetch_array( $query )) {
								$audio.=$row['language'].", ";
								$csv_audio.="[".$row['language']."] ";
							}
						}
						else if(strtolower($au)=='captions') {
							$cap="yes";
						}

						else if(strtolower($au)=='subtitles') {
							$n=trim($row['lname']);
							//$csv_sub.="[".$n."] ";
							$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
							while($row= mysql_fetch_array( $que )) {
								$subtitle.=$row['language'].", ";
								$csv_sub.="[".$row['language']."] ";
							}

						}
						else if(strtolower($au)=='forced_subtitles') {
							$s=trim($row['lname']);
							$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
							while($row= mysql_fetch_array( $que )) {
								$csv_force_sub.="[".$row['language']."] ";
							}
						}

						else if(strtolower($au)=='source') {
							$sr=trim($row['lname']);
							$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
							while($row= mysql_fetch_array( $que )) {
								$csv_native_audio.="[".$row['language']."] ";
							}

							$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
							while($row= mysql_fetch_array( $sub2)) {
								$check=trim($row['aname']);
								if(strtolower($check)=="image.burned_forced_narrative.locale") {
									$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
									while($row= mysql_fetch_array( $sub3)) {
										$en=trim($row['att']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_att_nar.="[".$row['language']."] ";
										}
									}
								}
								if(strtolower($check)=="image.burned_subtitles.locale") {
									$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
									while($row= mysql_fetch_array( $sub3)) {
										$es=trim($row['att']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_att_sub.="[".$row['language']."] ";
										}
									}
								}
							}
						}
					}
					$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$cap.", ".$encode." \n";
					echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='5%'>".$med."</td><td width='5%'".$cap."</td><td width='10%'>".$encode."</td></tr>";
				}
				else {
					$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					while($row= mysql_fetch_array( $sub )) {
						$attribute=trim($row['att']);
						if(strtolower($attribute)=="true") {
							$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
									and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
							while($row= mysql_fetch_array( $sub1 )) {
								$au=trim($row['role']);


								if(strtolower($au)=='audio') {
									$l=trim($row['lname']);
									//$csv_audio.="[".$l."] ";
									$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
									while($row= mysql_fetch_array( $query )) {
										$audio.=$row['language'].", ";
										$csv_audio.="[".$row['language']."] ";
									}
								}
								else if(strtolower($au)=='captions') {
									$cap="yes";
								}

								else if(strtolower($au)=='subtitles') {
									$n=trim($row['lname']);
									//$csv_sub.="[".$n."] ";
									$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
									while($row= mysql_fetch_array( $que )) {
										$subtitle.=$row['language'].", ";
										$csv_sub.="[".$row['language']."] ";
									}

								}
								else if(strtolower($au)=='forced_subtitles') {
									$s=trim($row['lname']);
									$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
									while($row= mysql_fetch_array( $que )) {
										$csv_force_sub.="[".$row['language']."] ";
									}
								}
								else if(strtolower($au)=='source') {
									$sr=trim($row['lname']);
									$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
									while($row= mysql_fetch_array( $que )) {
										$csv_native_audio.="[".$row['language']."] ";
									}
								}

							}
							$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.",true , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$cap.", ".$encode." \n";
							echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='5%'>".$med."</td><td width='5%'".$cap."</td><td width='10%'>".$encode."</td></tr>";
						}

						else if(strtolower($attribute)=="false") {
							$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
									and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
							while($row= mysql_fetch_array( $sub1)) {
								$au=trim($row['role']);

								if(strtolower($au)=='audio') {
									$l=trim($row['lname']);
									//$csv_audio.="[".$l."] ";
									$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
									while($row= mysql_fetch_array( $query )) {
										$audio.=$row['language'].", ";
										$csv_audio.="[".$row['language']."] ";
									}
								}
								else if(strtolower($au)=='captions') {
									$cap="yes";
								}

								else if(strtolower($au)=='subtitles') {
									$n=trim($row['lname']);
									//$csv_sub.="[".$n."] ";
									$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
									while($row= mysql_fetch_array( $que )) {
										$subtitle.=$row['language'].", ";
										$csv_sub.="[".$row['language']."] ";
									}

								}
								else if(strtolower($au)=='forced_subtitles') {
									$s=trim($row['lname']);
									$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
									while($row= mysql_fetch_array( $que )) {
										$csv_force_sub.="[".$row['language']."] ";
									}
								}

								else if(strtolower($au)=='source') {
									$sr=trim($row['lname']);
									$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
									while($row= mysql_fetch_array( $que )) {
										$csv_native_audio.="[".$row['language']."] ";
									}

									$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
									while($row= mysql_fetch_array( $sub2)) {
										$check=trim($row['aname']);
										if(strtolower($check)=="image.burned_forced_narrative.locale") {
											$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
											while($row= mysql_fetch_array( $sub3)) {
												$en=trim($row['att']);
												$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
												while($row= mysql_fetch_array( $que )) {
													$csv_att_nar.="[".$row['language']."] ";

												}
											}
										}
										if(strtolower($check)=="image.burned_subtitles.locale") {
											$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
											while($row= mysql_fetch_array( $sub3)) {
												$es=trim($row['att']);
												$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
												while($row= mysql_fetch_array( $que )) {
													$csv_att_sub.="[".$row['language']."] ";

												}
											}
										}
									}
								}
							}
							$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.",false , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$cap.", ".$encode." \n";
							echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='5%'>".$med."</td><td width='5%'".$cap."</td><td width='10%'>".$encode."</td></tr>";
						}
					}
				}
			}
		}
		////////////////////////////////////////////////////////////////////////  Multiple values check //////////////////////////////////////////////////////////////////////////////////////////////////////////

		if(empty($_POST['territory'])&&!empty($_POST['title'])&&!empty($_POST['vendorID'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Encode House</th>";
			if(!empty($_POST['studio'])) {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode, pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type FROM `assets` as ass, package AS pack, video AS vid
						WHERE vid.package_id = pack.id  and pack.id=ass.package_id AND ass.asset_type LIKE 'full' and vid.vendor_id='$id' and vid.title like '%$title%' and pack.provider like '$studio'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if(strtolower($au)=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if(strtolower($au)=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if(strtolower($au)=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if(strtolower($au)=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if(strtolower($check)=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if(strtolower($check)=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if(strtolower($attribute)=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if(strtolower($au)=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if(strtolower($au)=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if(strtolower($au)=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if(strtolower($au)=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if(strtolower($attribute)=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if(strtolower($au)=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if(strtolower($au)=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if(strtolower($au)=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if(strtolower($au)=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if(strtolower($check)=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if(strtolower($check)=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
			else {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode,pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type FROM `assets` as ass, package AS pack, video AS vid
						WHERE vid.package_id = pack.id  and pack.id=ass.package_id AND ass.asset_type LIKE 'full' and vid.vendor_id='$id' and vid.title like '%$title%'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}

		}
		if(!empty($_POST['territory'])&&!empty($_POST['title'])&&empty($_POST['vendorID'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Territory </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Encode House</th>";
			if(!empty($_POST['studio'])) {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, vid.title as title, pack.encode_house as encode, pack.media_type as media, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter' and vid.title like '%$title%' and pack.provider like '$studio'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
			else {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, pack.encode_house as encode, pack.media_type as media, vid.title as title, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter' and vid.title like '%$title%'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}

			}

		}
		if(!empty($_POST['territory'])&&empty($_POST['title'])&&!empty($_POST['vendorID'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Territory </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Encode House</th>";
			if(!empty($_POST['studio'])) {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, pack.encode_house as encode, pack.media_type as media, vid.title as title, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter' and vid.vendor_id='$id' and pack.provider like '$studio'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
			else {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, pack.encode_house as encode, pack.media_type as media, vid.title as title, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter' and vid.vendor_id='$id'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
		}
		if(!empty($_POST['territory'])&&!empty($_POST['title'])&&!empty($_POST['vendorID'])) {
			echo "<th>Vendor ID </th>";
			echo "<th>Title </th>";
			echo "<th>Territory </th>";
			echo "<th>Audio</th>";
			echo "<th>Subtitles</th>";
			echo "<th>Media Type</th>";
			echo "<th>Encode House</th>";
			if(!empty($_POST['studio'])) {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, pack.encode_house as encode,pack.media_type as media, vid.title as title, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter' and vid.vendor_id='$id' and vid.title like '%$title%' and pack.provider like '$studio'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$es=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_sub.="[".$row['language']."] ";

													}
												}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}
			}
			else {
				$str=mysql_query("SELECT DISTINCT (vid.vendor_id) as vid, pack.encode_house as encode, pack.media_type as media, vid.title as title, vid.original_spoken_locale as ori, pack.provider as pro,pack.id as fileID, ass.id as aid,ass.asset_type as type,reg.territory as ter FROM `assets` as ass, package AS pack, video AS vid,regions as reg
						WHERE  reg.package_id=pack.id and vid.package_id = pack.id and pack.id=ass.package_id and ass.asset_type LIKE 'full' and reg.territory like '$ter' and vid.vendor_id='$id' and vid.title like '%$title%'") or die(mysql_error());
				while ($row= mysql_fetch_array( $str )) {
					$name=$row['fileID'];
					$vid="[".$row['vid']."]";
					$aid=$row['aid'];
					$encode=$row['encode'];
					$titl=str_replace("\n","",str_replace(",","",trim($row['title'])));
					$original=$row['ori'];
					$provider=$row['pro'];
					$med=$row['media'];
					$audio="";
					$subtitle="";
					$csv_audio="";
					$csv_sub="";
					$csv_native_audio="";
					$csv_force_sub="";
					$csv_att_nar="";
					$csv_att_sub="";
					$test=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
					if(mysql_num_rows($test)==0) {

						$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id
								AND ass.id ='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub1 )) {
							$au=trim($row['role']);
							if($au=='audio') {
								$l=trim($row['lname']);
								//$csv_audio.="[".$l."] ";
								$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
								while($row= mysql_fetch_array( $query )) {
									$audio.=$row['language'].", ";
									$csv_audio.="[".$row['language']."] ";
								}
							}
							else if($au=='subtitles') {
								$n=trim($row['lname']);
								//$csv_sub.="[".$n."] ";
								$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$subtitle.=$row['language'].", ";
									$csv_sub.="[".$row['language']."] ";
								}

							}
							else if($au=='forced_subtitles') {
								$s=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_force_sub.="[".$row['language']."] ";
								}
							}

							else if($au=='source') {
								$sr=trim($row['lname']);
								$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
								while($row= mysql_fetch_array( $que )) {
									$csv_native_audio.="[".$row['language']."] ";
								}

								$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
								while($row= mysql_fetch_array( $sub2)) {
									$check=trim($row['aname']);
									if($check=="image.burned_forced_narrative.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$en=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_nar.="[".$row['language']."] ";
											}
										}
									}
									if($check=="image.burned_subtitles.locale") {
										$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
										while($row= mysql_fetch_array( $sub3)) {
											$es=trim($row['att']);
											$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
											while($row= mysql_fetch_array( $que )) {
												$csv_att_sub.="[".$row['language']."] ";
											}
										}
									}
								}
							}
						}
						$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
						echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
					}
					else {
						$sub=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.name like 'image.textless_master' and ast.assets_id='$aid'") or die(mysql_error());
						while($row= mysql_fetch_array( $sub )) {
							$attribute=$row['att'];
							if($attribute=="true") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast  WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'true' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1 )) {
									$au=trim($row['role']);

									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}
									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}
									}

								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}

							else if($attribute=="false") {
								$sub1=mysql_query("SELECT asse.role as role, asse.locale_name as lname FROM `assets` AS ass, `asset` AS asse, `asset_attributes` as ast WHERE ass.id = asse.assets_id and ast.assets_id=ass.id
										and ast.attribute like 'false' AND ass.id ='$aid'") or die(mysql_error());
								while($row= mysql_fetch_array( $sub1)) {
									$au=trim($row['role']);
									if($au=='audio') {
										$l=trim($row['lname']);
										//$csv_audio.="[".$l."] ";
										$query=mysql_query("SELECT language from `language_iso` where code like '$l'") or die(mysql_error());
										while($row= mysql_fetch_array( $query )) {
											$audio.=$row['language'].", ";
											$csv_audio.="[".$row['language']."] ";
										}
									}
									else if($au=='subtitles') {
										$n=trim($row['lname']);
										//$csv_sub.="[".$n."] ";
										$que=mysql_query("SELECT language from `language_iso` where code like '$n'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$subtitle.=$row['language'].", ";
											$csv_sub.="[".$row['language']."] ";
										}

									}
									else if($au=='forced_subtitles') {
										$s=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$s'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_force_sub.="[".$row['language']."] ";
										}
									}

									else if($au=='source') {
										$sr=trim($row['lname']);
										$que=mysql_query("SELECT language from `language_iso` where code like '$sr'") or die(mysql_error());
										while($row= mysql_fetch_array( $que )) {
											$csv_native_audio.="[".$row['language']."] ";
										}

										$sub2=mysql_query("SELECT ast.name as aname FROM `asset_attributes` as ast WHERE  ast.assets_id='$aid' ") or die(mysql_error());
										while($row= mysql_fetch_array( $sub2)) {
											$check=trim($row['aname']);
											if($check=="image.burned_forced_narrative.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
													$en=trim($row['att']);
													$que=mysql_query("SELECT language from `language_iso` where code like '$en'") or die(mysql_error());
													while($row= mysql_fetch_array( $que )) {
														$csv_att_nar.="[".$row['language']."] ";

													}
												}
											}
											if($check=="image.burned_subtitles.locale") {
												$sub3=mysql_query("SELECT ast.attribute as att FROM `asset_attributes` as ast WHERE ast.assets_id ='$aid' and ast.name like '$check'") or die(mysql_error());
												while($row= mysql_fetch_array( $sub3)) {
												$es=trim($row['att']);
												$que=mysql_query("SELECT language from `language_iso` where code like '$es'") or die(mysql_error());
												while($row= mysql_fetch_array( $que )) {
													$csv_att_sub.="[".$row['language']."] ";

												}
											}
											}
										}
									}
								}
								$content.=$provider.", ".$vid.", ".$original.", ".$titl.", ".$csv_native_audio.", , ".$csv_att_sub.", ".$csv_att_nar.", ".$csv_audio.", ".$csv_sub.",".$csv_force_sub.", ".$med.", ".$encode." \n";
								echo "<tr><td width='10%'><a href='result.php?fileID=$name'>$vid</td><td width='13%'>".$titl."</td><td width='20%'>".$audio."</td><td width='40%'>".$subtitle."</td><td width='10%'>".$med."</td><td width='10%'>".$encode."</td></tr>";
							}
						}
					}
				}

			}
		}
		fwrite($fh, pack("CCC",0xef,0xbb,0xbf));
		fwrite($fh,"Provider, MVF ID, Original Spoken Locale, Original Title, Feature Native Audio, Textless, Embedded Subtitles, Embedded Forces Narratives, Additional Audio, Subtitles Files, Forced Narratives Files, Media Type, Closed Captions, Encode House \n");
		fwrite($fh,$content);

	}

}
mysql_close();
fclose($fh);
echo "<br>";
if($flag==1) {
        echo "<a href='http://216.200.93.37/search/$myFile'>Download CSV Report</a>";
        echo "<br>";
 }
 echo "<br>";

 ?>