<?php
mysql_connect("localhost:3306", "root", "password123") or die(mysql_error());
mysql_select_db("project_xml") or die(mysql_error());
$fname=$_GET['fileID'];
$que=mysql_query("SELECT file_name from package where id='$fname'") or die(mysql_error());

while ($row= mysql_fetch_array( $que )) {
	$name=$row['file_name'];
}
$myFile= trim($name).".xml";
$fh = fopen($myFile, 'w');

mysql_query("set names 'utf8'");
header('Content-type: text/html; charset=UTF-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>XML Search</title>
<SCRIPT SRC="javascript/jquery-1.9.1.js"> </SCRIPT>


<SCRIPT>
$(document).ready(function() {
        $("#yesContent").click(function() {
                $(".showContent").show();
        });
});

</SCRIPT>
</head>

<body>
<input name="sendContent" id="yesContent" type="button" value="Generate XML file">
<br>
<?php

$content="";
echo "<div class='showContent' style='display: none;'>";
echo "<a href='http://216.200.93.37/search/$myFile'>Download XML Report</a>";
echo "</div>";
fwrite($fh,"<?xml version='1.0' encoding='utf-8'?> \n");
fwrite($fh,"<package> \n");

$str=mysql_query("SELECT vid.copyright_cline as copy, vid.country as country, vid.original_spoken_locale as origin, vid.production_company as prod, vid.sub_type as sub, vid.synopsis as syn, vid.theatrical_release_date as theat, 
vid.title as title, vid.type as type, vid.vendor_id as vendorID, pack.package_name as pname, pack.date as date, pack.provider as pro, pack.language as lang FROM `package` AS pack, `video` AS vid 
WHERE vid.package_id = pack.id AND pack.id = '$fname'") or die(mysql_error());
echo "------------------------------------------------------------------------------------- <br>";
echo "<h4>Package Info</h4>";
while ($row= mysql_fetch_array( $str )) {
	echo "<strong>Vendor ID</strong> : ".$row['vendorID']."<br>";
	echo "<strong>Package Name</strong> : ".$row['pname']."<br>";
	echo "<strong>Package Date</strong> : ".$row['date']."<br>";
	echo "<strong>Provider</strong> : ".$row['pro']."<br>";
	echo "<strong>Language</strong> : ".$row['lang']."<br>";
	echo "<strong>Copyright Cline</strong> : ".$row['copy']."<br>";
	echo "<strong>Country</strong> : ".$row['country']."<br>";
	echo "<strong>Original Spoken Locale</strong> : ".$row['origin']."<br>";	
	echo "<strong>Production Company</strong> : ".$row['prod']."<br>";
	echo "<strong>Sub Type</strong> : ".$row['sub']."<br>";
	echo "<strong>Synopsis</strong> : ".$row['syn']."<br>";
	echo "<strong>Theatrical Release Date</strong> : ".$row['theat']."<br>";
	echo "<strong>Title</strong> : ".$row['title']."<br>";
	echo "<strong>Type</strong> : ".$row['type']."<br>";

	fwrite($fh,"<packagename>".$row['pname']."</packagename> \n");
	fwrite($fh,"<date>".$row['date']."</date> \n");
	fwrite($fh,"<provider>".$row['pro']."</provider> \n");
	fwrite($fh,"<language>".$row['lang']."</language> \n");	
	
	fwrite($fh,"<video>");
	fwrite($fh,"<vendor_id>".$row['vendorID']."</vendor_id> \n");
	fwrite($fh,"<copyright_cline>".$row['copy']."</copyright_cline> \n");
	fwrite($fh,"<country>".$row['country']."</country> \n");
	fwrite($fh,"<original_spoken_locale>".$row['origin']."</original_spoken_locale> \n");
	fwrite($fh,"<production_copmany>".$row['prod']."</production_copmany> \n");
	fwrite($fh,"<subtype>".$row['sub']."</subtype> \n");
	fwrite($fh,"<synopsis>".$row['syn']."</synopsis> \n");
	fwrite($fh,"<theatrical_release_date>".$row['theat']."</theatrical_release_date> \n");
	fwrite($fh,"<title>".$row['title']."</title> \n");
	fwrite($fh,"<type>".$row['type']."</type> \n");
	
	
}

echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Products Info</h4>";
$str=mysql_query("SELECT prod.territory as terri,prod.cleared_for_sale as sale,prod.cleared_for_hd_sale as saleHD, prod.wholesale_price_tier as price, prod.hd_wholesale_price_tier as HDprice,prod.preorder_sales_start_date as pre,
prod.sales_start_date as start, prod.sales_end_date as end, prod.cleared_for_vod as vod, prod.vod_type as vtype, prod.available_for_vod_date as ava,prod.physical_release_date as physical,prod.unavailable_for_vod_date as unava,
prod.hd_physical_release_date as releaseDate, prod.cleared_for_hd_vod as clearHD FROM `package` AS pack, `products` AS prod WHERE pack.id = prod.package_id AND pack.id like '$fname' group by terri") or die(mysql_error());
fwrite($fh,"<products> \n");
while($row= mysql_fetch_array( $str )) {
	echo "<strong> Product Territory</strong> : ".$row['terri']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cleared for Sale</strong> : ".$row['sale']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cleared for HD Sale</strong> : ".$row['saleHD']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Wholesale Price Tier</strong> : ".$row['price']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HD Wholesale Price Tier</strong> : ".$row['HDprice']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Preorder Sales Start Date</strong> : ".$row['pre']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sales Start Date</strong> : ".$row['start']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sales End Date</strong> : ".$row['end']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cleared for VOD</strong> : ".$row['vod']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; VOD Type</strong> : ".$row['vtype']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Available for VOD Date</strong> : ".$row['ava']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Physical Release Date</strong> : ".$row['physical']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Unavailable for VOD Date</strong> : ".$row['unava']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HD Physical Release Date</strong> : ".$row['releaseDate']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cleared for HD VOD</strong> : ".$row['clearHD']."<br>";
	echo "<br>";
	
	fwrite($fh,"<product> \n");
	fwrite($fh,"<territory>".$row['terri']."</territory> \n");
	fwrite($fh,"<cleared_for_sale>".$row['sale']."</cleared_for_sale> \n");
	fwrite($fh,"<cleared_for_hd_sale>".$row['saleHD']."</cleared_for_hd_sale> \n");
	fwrite($fh,"<wholesale_price_tier>".$row['price']."</wholesale_price_tier> \n");
	fwrite($fh,"<hd_wholesale_price_tier>".$row['HDprice']."</hd_wholesale_price_tier> \n");
	fwrite($fh,"<preorder_sales_start_date>".$row['pre']."</preorder_sales_start_date> \n");
	fwrite($fh,"<sales_start_date>".$row['start']."</sales_start_date> \n");
	fwrite($fh,"<sales_end_date>".$row['end']."</sales_end_date> \n");
	fwrite($fh,"<cleared_for_vod>".$row['vod']."</cleared_for_vod> \n");
	fwrite($fh,"<vod_type>".$row['vtype']."</vod_type> \n");
	fwrite($fh,"<available_for_vod_date>".$row['ava']."</available_for_vod_date> \n");
	fwrite($fh,"<physical_release_date>".$row['physical']."</physical_release_date> \n");
	fwrite($fh,"<unavailable_for_vod_date>".$row['unava']."</unavailable_for_vod_date> \n");
	fwrite($fh,"<hd_physical_release_date>".$row['releaseDate']."</hd_physical_release_date> \n");
	fwrite($fh,"<cleared_for_hd_vod>".$row['clearHD']."</cleared_for_hd_vod> \n");

	fwrite($fh,"</product> \n");
	
}
fwrite($fh,"</products> \n");
echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Rating Info</h4>";
fwrite($fh,"<ratings> \n");
$str=mysql_query("SELECT rate.rating_system as sys, rate.rating_code as code,rate.rating_value as val from `package` as pack, `ratings` as rate where pack.id=rate.package_id and pack.id like '$fname' group by sys") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
	if(empty($row['val'])) {
		echo "<strong> Rating System</strong> : ".$row['sys'].":".$row['code']."<br>";
		fwrite($fh,"<rating system="."'".$row['sys']."'"." code="."'".$row['code']."'"."/> \n");
	}
	else {
		echo "<strong> Rating System</strong> : ".$row['sys'].":".$row['val']."<br>";
		fwrite($fh,"<rating system="."'".$row['sys']."'/>".$row['val']." \n");
	}
}

fwrite($fh,"</ratings> \n");
echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Regions Info</h4>";
fwrite($fh,"<regions> \n");
$str=mysql_query("SELECT reg.territory as ter, reg.copyright_cline as copy, reg.theatrical_release_date as theat from `regions` as reg, `package` as pack where pack.id=reg.package_id and pack.id='$fname'") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
	 echo "<strong> Territory</strong> : ".$row['ter']."<br>";
	 echo "<strong> Copyright Cline</strong> : ".$row['copy']."<br>";
	 echo "<strong> Theatrical Release Date</strong> : ".$row['theat']."<br>";
	
	 fwrite($fh,"<territory>".$row['ter']."</territory> \n");
	 fwrite($fh,"<copyright_cline>".$row['copy']."</copyright_cline> \n");
	 fwrite($fh,"<theatrical_release_date>".$row['theat']."</theatrical_release_date> \n");
}
fwrite($fh,"</regions> \n");
echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Genres Info</h4>";
fwrite($fh,"<genre> \n");
$str=mysql_query("SELECT gen.genre as genre, gen.code as code from `genres` as gen, `package` as pack where pack.id=gen.package_id and pack.id='$fname' group by genre") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
	echo "<strong> Genre</strong> : ".$row['code']." (".$row['genre'].")<br>";
	fwrite($fh,"<genre code="."'".$row['genre']."'"."/>".$row['code']." \n");
}

fwrite($fh,"</genre> \n");
echo "-------------------------------------------------------------------------------------<br>";

echo "<h4>Locales Info</h4>";
fwrite($fh,"<locales> \n");
$str=mysql_query("SELECT loc.name as lname, loc.title as title, loc.synopsis as syn from `locales` as loc, `package` as pack where pack.id=loc.package_id and pack.id='$fname'") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
        echo "<strong> Name</strong> : ".$row['lname']."<br>";
        echo "<strong> Title</strong> : ".$row['title']."<br>";
	echo "<strong> Synposis</strong> : ".$row['syn']."<br>";

	fwrite($fh,"<locale name="."'".$row['lname']."'> \n");
	fwrite($fh,"<title>".$row['title']."</title> \n");
	fwrite($fh,"<synopsis>".$row['syn']."</synopsis> \n");
}
fwrite($fh,"</locales> \n");

echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Crews Info</h4>";

fwrite($fh,"<crew> \n");
$str=mysql_query("SELECT cre.display_name as name, cre.apple_id as apple, cre.locale_name as lname, cre.locale_display_name as dname, cre.role as role from `crews` as cre, `package` as pack where pack.id=cre.package_id and
pack.id='$fname'") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
        echo "<strong> Display name</strong> : ".$row['name']."<br>";
	echo "<strong> Role</strong> : ".$row['role']."<br>";
        echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Apple ID</strong> : ".$row['apple']."<br>";
        echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Locale</strong> : ".$row['lname']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Display Name</strong> : ".$row['dname']."<br>";
	echo "<br>";

	fwrite($fh,"<crew_member billing='top'> \n");
	fwrite($fh,"<display_name>".$row['name']."</display_name>\n");
	fwrite($fh,"<apple_id>".$row['apple']."</apple_id>\n");
	fwrite($fh,"<locales>\n");
	fwrite($fh,"<locale name="."'".$row['lname']."'>\n");
	fwrite($fh,"<display_name>".$row['dname']."</display_name>\n");
	fwrite($fh,"</locale>");
	fwrite($fh,"</locales>\n");
	fwrite($fh,"<roles>\n");
	fwrite($fh,"<role>".$row['role']."</role> \n");
	fwrite($fh,"</roles>\n");
	fwrite($fh,"</crew_member>\n");
}
fwrite($fh,"</crew> \n");
echo "-------------------------------------------------------------------------------------<br>";

echo "<h4>Casts Info</h4>";

fwrite($fh,"<cast>\n");
$str=mysql_query("SELECT cas.display_name as name, cas.apple_id as apple, cas.character_name as cname, cas.locale_name as lname, cas.locale_display_name as dname, cas.locale_character_name as chname from `cast` as cas, `package` as pack
where pack.id=cas.package_id and pack.id='$fname'") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
        echo "<strong> Display name</strong> : ".$row['name']."<br>";
	echo "<strong> Character Name</strong> : ".$row['cname']."<br>";
        echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Apple ID</strong> : ".$row['apple']."<br>";
        echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Locale</strong> : ".$row['lname']."<br>";
        echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Display Name</strong> : ".$row['dname']."<br>";
	echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Character Name</strong> : ".$row['chname']."<br>";
	echo "<br>";

	fwrite($fh,"<cast_member billing='top'> \n");
	fwrite($fh,"<display_name>".$row['name']."</display_name> \n");
	fwrite($fh,"<apple_id>".$row['apple']."</apple_id> \n");
	fwrite($fh,"<character_name>".$row['cname']."</character_name>\n");
	fwrite($fh,"<locales>\n");
	fwrite($fh,"<locale name='".$row['lname']."'>\n");
	fwrite($fh,"<display_name>".$row['dname']."</display_name>\n");
	fwrite($fh,"<character_name>".$row['chname']."</character_name>\n");
	fwrite($fh,"</locale>\n");
	fwrite($fh,"</locales>\n");
	fwrite($fh,"</cast_member>\n");
}
fwrite($fh,"</cast>\n");

echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Accessibility Info</h4>";
fwrite($fh,"<accessibility_info>\n");
$str=mysql_query("SELECT ass.role as role, ass.available as ava from `accessibility_info` as ass,`package` as pack where pack.id=ass.package_id and pack.id='$fname'") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
	echo "<strong>Role: </strong>".$row['role']."<br>";
	echo "<strong>Caption: </strong>".$row['ava']."<br>";

	fwrite($fh,"<accessibility role='".$row['role']."' available='".$row['ava']."' />\n");

}	

fwrite($fh,"</accessibility_info>\n");

echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Assets Info</h4>";

fwrite($fh,"<assets>\n");
$str=mysql_query("SELECT ass.id as id,ass.asset_type as type from `assets` as ass,`package` as pack where pack.id=ass.package_id and pack.id='$fname'") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
	$id=$row['id'];
	echo "<strong>Asset type: ".$row['type']."</strong><br>";
	fwrite($fh,"<asset type='".$row['type']."'>\n");
	$sub1=mysql_query("SELECT ter.territory as territory from `asset_territory` as ter,`assets` as ass where ter.assets_id=ass.id and ass.id='$id'") or die(mysql_error());

	while($row= mysql_fetch_array( $sub1 )) {
		echo "<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Territory</strong> : ".$row['territory']."<br>";
	}
	$sub2=mysql_query("SELECT asse.attribute as attr, asse.name as name FROM `assets` AS ass, `asset_attributes` AS asse WHERE ass.id = asse.assets_id AND ass.id ='$id'") or die(mysql_error());
        while($row= mysql_fetch_array( $sub2 )) {
                echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Atrribute </strong> : ".$row['name']." : ".$row['attr']."<br>";
		fwrite($fh,"<attribute name='".$row['name']."'>".$row['attr']."</attribute>\n");
        }

	$sub=mysql_query("SELECT asse.role as role, asse.locale_name as lname, asse.file_name as fname, asse.size as size, asse.asset_checksum as checksum
	FROM `assets` AS ass, `asset` AS asse WHERE ass.id = asse.assets_id AND ass.id ='$id'") or die(mysql_error());
	while($row= mysql_fetch_array( $sub )) {
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Role</strong> : ".$row['role']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Locale name</strong> : ".$row['lname']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; File Name</strong> : ".$row['fname']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Size</strong> : ".$row['size']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Checksum</strong> : ".$row['checksum']."<br>";
		echo "<br>";

		fwrite($fh,"<data_file role='".$row['role']."'>\n");
		fwrite($fh,"<locale name='".$row['lname']."'/>\n");
		fwrite($fh,"<file_name>".$row['fname']."</file_name>\n");
		fwrite($fh,"<size>".$row['size']."</size>\n");
		fwrite($fh,"<checksum type='md5'>".$row['checksum']."</checksum>\n");
		fwrite($fh,"</data_file>\n");
	}
	fwrite($fh,"</asset>\n");
	
}

fwrite($fh,"</assets>\n");
echo "-------------------------------------------------------------------------------------<br>";
echo "<h4>Chapters Info</h4>";

fwrite($fh,"<chapters>\n");
$str=mysql_query("SELECT chap.id as id,chap.timecode_format as time from `chapters` chap, `package` pack where pack.id=chap.package_id and pack.id='$fname'") or die(mysql_error());
while($row= mysql_fetch_array( $str )) {
	echo "<strong> Timecode Format</strong> : ".$row['time']."<br>";
	fwrite($fh,"<timecode_format>".$row['time']."</timecode_format>\n");
	$id=$row['id'];
	$sub=mysql_query("SELECT cht.title as title,chap.start_time as time,chap.file_name as file,chap.chapter_checksum as checksum, chap.size as size
	FROM `chapter_titles` AS cht, `chapter` AS chap, `chapters` AS ch WHERE chap.chapters_id = ch.id AND cht.chapter_id = chap.id AND ch.id ='$id'") or die(mysql_error());
	while($row= mysql_fetch_array( $sub )) {
		echo "<strong> Chapter Title</strong> : ".$row['title']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Start Time</strong> : ".$row['time']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; File Name</strong> : ".$row['file']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Size</strong> : ".$row['size']."<br>";
		echo "<strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Checksum</strong> : ".$row['checksum']."<br>";
		echo "<br>";


		fwrite($fh,"<chapter>\n");
		fwrite($fh,"<start_time>".$row['time']."</start_time>\n");
		fwrite($fh,"<titles>\n");
		fwrite($fh,"<title>".$row['title']."</title>\n");
		fwrite($fh,"</titles>\n");		
		fwrite($fh,"<artwork_file>\n");
		fwrite($fh,"<file_name>".$row['file']."</file_name>\n");
		fwrite($fh,"<checksum type='md5'>".$row['checksum']."</checksum>\n");
		fwrite($fh,"<size>".$row['size']."</size>\n");

		fwrite($fh,"</artwork_file>\n");
		fwrite($fh,"</chapter>\n");

	}
} 
fwrite($fh,"</chapters>\n");
fwrite($fh,"</video>");
fwrite($fh,"</package>");
mysql_close();
fclose($fh);


?>
</body>
