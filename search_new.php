<?php
ob_start();
mysql_connect("mvf-paramount-web:3306", "root", "skunkstripe") or die(mysql_error());
mysql_select_db("paramountmdl") or die(mysql_error());

$myFile = "report-".date('m-d-Y-His').".xml";
$date=date('m-d-Y-His');
$fh = fopen($myFile, 'w');

$myFile2 = "report-".date('m-d-Y-His').".txt";
$date=date('m-d-Y-His');
$fh2 = fopen($myFile2, 'w');

?>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Paramount MetaData Search</title>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
</head>
<body>
 
<table width="1000" border="1" align="center" cellpadding="0"
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
                                                        <td colspan="1">PPC: <input name="pui" id="pui" type="text">
                                                        </td>

                                                        <td colspan="1">Title: <input name="title" id="title" type="text">
                                                        </td>


                                                        <td colspan="1">Version: <input name="version" id="version" value="001"
                                                                type="text">
                                                        </td>
							<td>
                                                                XML Current Date: <?php
                                                                    $result=mysql_query("SELECT xmldate FROM titlesUpdate GROUP BY xmldate DESC LIMIT 0 , 1") or die(mysql_error());
                                                                    while ($row=mysql_fetch_array($result)) {
                                                                        echo $row['xmldate'];
                                                                     }

                                                                 ?>
                                                        </td>

                                                </tr>
                                                <tr>
                                                </tr>
                                                <tr>
                                                         <td colspan="2">ISOCountry: <textarea name="isocountry"
                                                                        id="isocuntry" type="text" rols="40" rows="6">US</textarea>

                                                        </td>
                                    

                                                </tr>
                                                <tr>
                                                	<td colspan="4">
                                                        	<input type="checkbox" name="tags[]" value="USRating">USRating &nbsp 
                                                        	<input type="checkbox" name="tags[]" value="LocalRating">LocalRating &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalRatingReason">LocalRatingReason &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalTitle">LocalTitle &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalSynopsis">LocalSynopsis &nbsp
                                                        	<input type="checkbox" name="tags[]" value="ShortSynopsis">ShortSynopsis &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LongSynopsis">LongSynopsis &nbsp
                                                        	<input type="checkbox" name="tags[]" value="USGenre">USGenre 
                                                        </td>	
                                                </tr>
                                                 <tr>
                                                	<td colspan="4">
                                                        	<input type="checkbox" name="tags[]" value="Actors">Actors &nbsp 
                                                        	<input type="checkbox" name="tags[]" value="Composers">Composers &nbsp
                                                        	<input type="checkbox" name="tags[]" value="Directors">Directors &nbsp
                                                        	<input type="checkbox" name="tags[]" value="Producers">Producers &nbsp
                                                        	<input type="checkbox" name="tags[]" value="Writers">Writers &nbsp
                                                        	<input type="checkbox" name="tags[]" value="CopyRight">CopyRight &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalReleaseDate">LocalReleaseDate &nbsp
                                                        	<input type="checkbox" name="tags[]" value="ProductionCompany">ProductionCompany &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalActors">LocalActors
                                                        </td>	
                                                </tr>
                                                        <tr>
                                                	<td colspan="4">
                                                        	<input type="checkbox" name="tags[]" value="LocalDirectors">LocalDirectors &nbsp 
                                                        	<input type="checkbox" name="tags[]" value="LocalProducers">LocalProducers &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalWriters">LocalWriters &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalComposers">LocalComposers &nbsp
                                                        	<input type="checkbox" name="tags[]" value="PhysicalReleaseDate">PhysicalReleaseDate &nbsp
                                                        	<input type="checkbox" name="tags[]" value="LocalPhysicalReleaseDate">LocalPhysicalReleaseDate &nbsp
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
 
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>	
<script type="text/javascript">
$(function() {
//autocomplete
$("#pui").autocomplete({
source: "pui.php",
minLength: 2,
open: function(event, ui)
{
           $("#pui").autocomplete ("widget").css("width","120px");  
} 
});	
 
});

$(function() {
//autocomplete
$("#title").autocomplete({
source: "title.php",
minLength: 2,
open: function(event, ui)
{
           $("#title").autocomplete ("widget").css("width","400px");
}

});

});

</script>
<SCRIPT LANGUAGE="JavaScript">
function checkPUI(field) {
        var dat=field.value;
        var check =/^[0-9]+$/;
        if(dat.length==0||dat==""||dat==null){
                field.style.background = 'Yellow';
                alert("PUI cannot be blank");
                return false;
        }

        if(dat.length!=5 || !dat.match(check))  {
                field.style.background = 'Yellow';
                alert("Invalid PUI");
                return false;
        }
        else {
                field.style.background = 'White';
        }
        return true;
}


function checkIt() {
         var PUI = document.form1.pui;
        if(checkPUI(PUI)) {
                return true;
        }
return false;
}

</SCRIPT>

</body> 
</html> 
<?php if(isset($_POST['submit'])) {
	$pui=$_POST['pui'];
	$check="";
	$version=$_POST['version'];
	$tag=$_POST['tags'];
	echo "<br>";
	echo "<table width='60%' id='myTable' border='1' align='center' cellpadding='0'
		cellspacing='1' col='13'>";
	echo "<tr>";
	echo "<th>PPC</th>";
	echo "<th>ISOCountry</th>";
	echo "<th>TitleSort</th>";
	echo "<th>LongSynopsis</th>";
	echo "<th>Company</th>";
	echo "<th>CopyRight</th>";
	echo "<th>LocalReleaseDate</th>";
	echo "<th>USGenre</th>";
	echo "<th>USRating</th>";
	echo "<th>LocalRating</th>";
	echo "<th>LocalTitle</th>";
	echo "<th>LocalSynopsis</th>";
	echo "<th>Actors</th>";
	echo "<th>Composers</th>";
	echo "<th>Directors</th>";
	echo "<th>Producers</th>";
	echo "<th>Writers</th>";
	echo "<th>XML Date</th>";
	
	echo "</tr>";
	if(!empty($_POST['isocountry'])&&!empty($_POST['title'])) {
		$iso=str_replace(" ","",$_POST['isocountry']);
		$title=str_replace("*","%",$_POST['title']);
		$code=explode(',',$iso);
		$size=sizeof($code);
		//$str="SELECT * FROM titlesUpdate WHERE TitleSort like '$title' AND ISOCountry IN (";
		if(!empty($_POST['pui'])) {
			$str="SELECT * FROM titlesUpdate WHERE PPC like '$pui' AND TitleSort like '$title' AND ISOCountry IN (";
			$country=array();
			for ($i=0; $i<$size; $i++) {
				$str.="'$code[$i]',";
			}
			$str=substr($str,0,-1);
			$str.=")";
			if(!empty($version)) {
				$str.=" AND Version like '%$version%'";
			}
		}
		else {
			$str="SELECT * FROM titlesUpdate WHERE TitleSort like '$title' AND ISOCountry IN (";
			$country=array();
			for ($i=0; $i<$size; $i++) {
				$str.="'$code[$i]',";
			}
			$str=substr($str,0,-1);
			$str.=")";
			if(!empty($version)) {
				$str.=" AND Version like '%$version%'";
			}
		}
		$query=$str;
		$result=mysql_query($query);
		fwrite($fh,"<library> \n");
		$count=0;
		while ($row=mysql_fetch_array($result)) {
			$counter=1;
			echo "<tr><td>".$row['PPC']."</td>";
			echo "<td>".$row['ISOCountry']."</td>";
			array_push($country,$row['ISOCountry']);
			fwrite($fh2,$row['ISOCountry'].":"."\n");
			if($row['TitleSort']==""||$row['TitleSort']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". TitleSort\n");
				$counter++;
			}
			else {
				
				echo "<td bgcolor='#00FF00'></td>";
					
			}
			if($row['LongSynopsis']==""||$row['LongSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LongSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Company']==""||$row['Company']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Company\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['CopyRight']==""||$row['CopyRight']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". CopyRight\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['LocalReleaseDate']==""||$row['LocalReleaseDate']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalReleaseDate\n");
				$counter++;
			
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['USGenre']==""||$row['USGenre']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USGenre\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['USRating']==""||$row['USRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
					
			}
			if($row['LocalRating']==""||$row['LocalRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalTitle']==""||$row['LocalTitle']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalTitle\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalSynopsis']==""||$row['LocalSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['Actors']==""||$row['Actors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Actors\n");
				$counter++;
			
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['Composers']==""||$row['Composers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Composers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Directors']==""||$row['Directors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Directors\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Producers']==""||$row['Producers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Producers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Writers']==""||$row['Writers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Writers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			echo "<td>".$row['xmldate'] ."</td></tr>";
	
			fwrite($fh,"<movie> \n");
			fwrite($fh,"<Territory>".$row['Territory']."</Territory> \n");
			fwrite($fh,"<ISOCountry>".$row['ISOCountry']."</ISOCountry> \n");
			fwrite($fh,"<PPC>".$row['PPC']."</PPC> \n");
			fwrite($fh,"<TitleSort>".$row['TitleSort']."</TitleSort> \n");
			fwrite($fh,"<Version>".$row['Version']."</Version> \n");
			fwrite($fh,"<Language>".$row['Language']."</Language> \n");
			fwrite($fh,"<xmldate>".$row['xmldate']."</xmldate> \n");

			if(sizeof($tag)==0) {
				fwrite($fh,"<ReleaseYear>".$row['ReleaseYear']."</ReleaseYear> \n");
				fwrite($fh,"<TitleKind>".$row['TitleKind']."</TitleKind> \n");
				fwrite($fh,"<TitleType>".$row['TitleType']."</TitleType> \n");
				fwrite($fh,"<PUI>".$row['PUI']."</PUI> \n");
				fwrite($fh,"<RT>".$row['RT']."</RT> \n");
				fwrite($fh,"<USRating>".$row['USRating']."</USRating> \n");
				fwrite($fh,"<USRatingReason>".$row['USRatingReason']."</USRatingReason> \n");
				fwrite($fh,"<LocalRating>".$row['LocalRating']."</LocalRating> \n");
				fwrite($fh,"<LocalRatingReason>".$row['LocalRatingReason']."</LocalRatingReason> \n");
				fwrite($fh,"<LocalTitle>".$row['LocalTitle']."</LocalTitle> \n");
				fwrite($fh,"<LocalSynopsis>".$row['LocalSynopsis']."</LocalSynopsis> \n");
				fwrite($fh,"<ShortSynopsis>".$row['ShortSynopsis']."</ShortSynopsis> \n");
				fwrite($fh,"<LongSynopsis>".$row['LongSynopsis']."</LongSynopsis> \n");
				fwrite($fh,"<ISOLanguage>".$row['ISOLanguage']."</ISOLanguage> \n");
				fwrite($fh,"<Company>".$row['Company']."</Company> \n");
				fwrite($fh,"<USGenre>".$row['USGenre']."</USGenre> \n");
				fwrite($fh,"<LocalGenre>".$row['LocalGenre']."</LocalGenre> \n");
				fwrite($fh,"<DTOStart>".$row['DTOStart']."</DTOStart> \n");
				fwrite($fh,"<DTOSolicitationDate>".$row['DTOSolicitationDate']."</DTOSolicitationDate> \n");
				fwrite($fh,"<DTOPrice>".$row['DTOPrice']."</DTOPrice> \n");
				fwrite($fh,"<AppleTier>".$row['AppleTier']."</AppleTier> \n");
				fwrite($fh,"<Actors>".$row['Actors']."</Actors> \n");
				fwrite($fh,"<Composers>".$row['Composers']."</Composers> \n");
				fwrite($fh,"<Directors>".$row['Directors']."</Directors> \n");
				fwrite($fh,"<Producers>".$row['Producers']."</Producers> \n");
				fwrite($fh,"<Writers>".$row['Writers']."</Writers> \n");
				fwrite($fh,"<VODType>".$row['VODType']."</VODType> \n");
				fwrite($fh,"<VODStart>".$row['VODStart']."</VODStart> \n");
				fwrite($fh,"<VODEnd>".$row['VODEnd']."</VODEnd> \n");
				fwrite($fh,"<SVODType>".$row['SVODType']."</SVODType> \n");
				fwrite($fh,"<SVODStart>".$row['SVODStart']."</SVODStart> \n");
				fwrite($fh,"<SVODEnd>".$row['SVODEnd']."</SVODEnd> \n");
				fwrite($fh,"<FVODType>".$row['FVODType']."</FVODType> \n");
				fwrite($fh,"<FVODStart>".$row['FVODStart']."</FVODStart> \n");
				fwrite($fh,"<FVODEnd>".$row['FVODEnd']."</FVODEnd> \n");
				fwrite($fh,"<BoxOffice>".$row['BoxOffice']."</BoxOffice> \n");
				fwrite($fh,"<MusicClear>".$row['MusicClear']."</MusicClear> \n");
				fwrite($fh,"<CopyRight>".$row['CopyRight']."</CopyRight> \n");
				fwrite($fh,"<DTORights>".$row['DTORights']."</DTORights> \n");
				fwrite($fh,"<DTORightsFinal>".$row['DTORightsFinal']."</DTORightsFinal> \n");
				fwrite($fh,"<DTORightsTerritory>".$row['DTORightsTerritory']."</DTORightsTerritory> \n");
				fwrite($fh,"<RatingAgency>".$row['RatingAgency']."</RatingAgency> \n");
				fwrite($fh,"<AppleAgencyCode>".$row['AppleAgencyCode']."</AppleAgencyCode> \n");
				fwrite($fh,"<LocalReleaseDate>".$row['LocalReleaseDate']."</LocalReleaseDate> \n");
				fwrite($fh,"<ProductionCompany>".$row['ProductionCompany']."</ProductionCompany> \n");
				fwrite($fh,"<LocalActors>".$row['LocalActors']."</LocalActors> \n");
				fwrite($fh,"<LocalDirectors>".$row['LocalDirectors']."</LocalDirectors> \n");
				fwrite($fh,"<LocalProducers>".$row['LocalProducers']."</LocalProducers> \n");
				fwrite($fh,"<LocalWriters>".$row['LocalWriters']."</LocalWriters> \n");
				fwrite($fh,"<LocalComposers>".$row['LocalComposers']."</LocalComposers> \n");
				fwrite($fh,"<LocalRatingAgencyName>".$row['LocalRatingAgencyName']."</LocalRatingAgencyName> \n");
				fwrite($fh,"<TheatricalReleaseFlag>".$row['TheatricalReleaseFlag']."</TheatricalReleaseFlag> \n");
				fwrite($fh,"<PhysicalReleaseDate>".$row['PhysicalReleaseDate']."</PhysicalReleaseDate> \n");
				fwrite($fh,"<PhysicalReleaseFlag>".$row['PhysicalReleaseFlag']."</PhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseFlag>".$row['LocalPhysicalReleaseFlag']."</LocalPhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalTheatricalReleaseFlag>".$row['LocalTheatricalReleaseFlag']."</LocalTheatricalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseDate>".$row['LocalPhysicalReleaseDate']."</LocalPhysicalReleaseDate> \n");
				fwrite($fh,"<DigitalReleaseDate>".$row['DigitalReleaseDate']."</DigitalReleaseDate> \n");			
				fwrite($fh,"<DigitalReleaseFlag>".$row['DigitalReleaseFlag']."</DigitalReleaseFlag> \n");
				fwrite($fh,"<LocalDigitalReleaseDate>".$row['LocalDigitalReleaseDate']."</LocalDigitalReleaseDate> \n");		
				fwrite($fh,"<LocalDigitalReleaseFlag>".$row['LocalDigitalReleaseFlag']."</LocalDigitalReleaseFlag> \n");			
				fwrite($fh,"<LocalRatingStatus>".$row['LocalRatingStatus']."</LocalRatingStatus> \n");
			}	
			else {
				foreach($tag as $t) {
					fwrite($fh,"<".$t.">".$row[$t]."</".$t.">");
				}
			}

			fwrite($fh,"</movie> \n");
		}
		fwrite($fh,"</library>");
		$result=array_merge($country,$code);
	}
	if(empty($_POST['isocountry'])&&!empty($_POST['title'])) {
		$title=str_replace("*","%",$_POST['title']);
		if(!empty($_POST['pui'])) {
			$str="SELECT * FROM titlesUpdate WHERE PPC like '$pui' AND TitleSort like '$title'";
			if(!empty($_POST['version'])) {
				$str.=" AND Version like '%$version%'";
			}
		}
		else {
			$str="SELECT * FROM titlesUpdate WHERE TitleSort like '$title'";
			if(!empty($_POST['version'])) {
				$str.=" AND Version like '%$version%'";
				}
		}
		$query=$str;
		$iso=array();
		$result=mysql_query($query);
		fwrite($fh,"<library> \n");
		while ($row=mysql_fetch_array($result)) {
			$counter=1;
			echo "<tr><td>".$row['PPC']."</td>";
			echo "<td>".$row['ISOCountry']."</td>";
			array_push($country,$row['ISOCountry']);
			fwrite($fh2,$row['ISOCountry'].":"."\n");
			if($row['TitleSort']==""||$row['TitleSort']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". TitleSort\n");
				$counter++;
			}
			else {
				
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LongSynopsis']==""||$row['LongSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LongSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Company']==""||$row['Company']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Company\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['CopyRight']==""||$row['CopyRight']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". CopyRight\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['LocalReleaseDate']==""||$row['LocalReleaseDate']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalReleaseDate\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['USGenre']==""||$row['USGenre']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USGenre\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['USRating']==""||$row['USRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalRating']==""||$row['LocalRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalTitle']==""||$row['LocalTitle']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalTitle\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalSynopsis']==""||$row['LocalSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Actors']==""||$row['Actors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Actors\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Composers']==""||$row['Composers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Composers\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Directors']==""||$row['Directors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Directors\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Producers']==""||$row['Producers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Producers\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Writers']==""||$row['Writers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Writers\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			echo "<td>".$row['xmldate'] ."</td></tr>";
			fwrite($fh,"<movie> \n");
			fwrite($fh,"<Territory>".$row['Territory']."</Territory> \n");
			fwrite($fh,"<ISOCountry>".$row['ISOCountry']."</ISOCountry> \n");
			fwrite($fh,"<PPC>".$row['PPC']."</PPC> \n");
			fwrite($fh,"<TitleSort>".$row['TitleSort']."</TitleSort> \n");
			fwrite($fh,"<Version>".$row['Version']."</Version> \n");
			fwrite($fh,"<Language>".$row['Language']."</Language> \n");
			fwrite($fh,"<xmldate>".$row['xmldate']."</xmldate> \n");

			if(sizeof($tag)==0){

				fwrite($fh,"<ReleaseYear>".$row['ReleaseYear']."</ReleaseYear> \n");
				fwrite($fh,"<TitleKind>".$row['TitleKind']."</TitleKind> \n");
				fwrite($fh,"<TitleType>".$row['TitleType']."</TitleType> \n");
				fwrite($fh,"<PUI>".$row['PUI']."</PUI> \n");
				fwrite($fh,"<RT>".$row['RT']."</RT> \n");
				fwrite($fh,"<USRating>".$row['USRating']."</USRating> \n");
				fwrite($fh,"<USRatingReason>".$row['USRatingReason']."</USRatingReason> \n");
				fwrite($fh,"<LocalRating>".$row['LocalRating']."</LocalRating> \n");
				fwrite($fh,"<LocalRatingReason>".$row['LocalRatingReason']."</LocalRatingReason> \n");
				fwrite($fh,"<LocalTitle>".$row['LocalTitle']."</LocalTitle> \n");
				fwrite($fh,"<LocalSynopsis>".$row['LocalSynopsis']."</LocalSynopsis> \n");
				fwrite($fh,"<ShortSynopsis>".$row['ShortSynopsis']."</ShortSynopsis> \n");
				fwrite($fh,"<LongSynopsis>".$row['LongSynopsis']."</LongSynopsis> \n");
				fwrite($fh,"<ISOLanguage>".$row['ISOLanguage']."</ISOLanguage> \n");
				fwrite($fh,"<Company>".$row['Company']."</Company> \n");
				fwrite($fh,"<USGenre>".$row['USGenre']."</USGenre> \n");
				fwrite($fh,"<LocalGenre>".$row['LocalGenre']."</LocalGenre> \n");
				fwrite($fh,"<DTOStart>".$row['DTOStart']."</DTOStart> \n");
				fwrite($fh,"<DTOSolicitationDate>".$row['DTOSolicitationDate']."</DTOSolicitationDate> \n");
				fwrite($fh,"<DTOPrice>".$row['DTOPrice']."</DTOPrice> \n");
				fwrite($fh,"<AppleTier>".$row['AppleTier']."</AppleTier> \n");
				fwrite($fh,"<Actors>".$row['Actors']."</Actors> \n");
				fwrite($fh,"<Composers>".$row['Composers']."</Composers> \n");
				fwrite($fh,"<Directors>".$row['Directors']."</Directors> \n");
				fwrite($fh,"<Producers>".$row['Producers']."</Producers> \n");
				fwrite($fh,"<Writers>".$row['Writers']."</Writers> \n");
				fwrite($fh,"<VODType>".$row['VODType']."</VODType> \n");
				fwrite($fh,"<VODStart>".$row['VODStart']."</VODStart> \n");
				fwrite($fh,"<VODEnd>".$row['VODEnd']."</VODEnd> \n");
				fwrite($fh,"<SVODType>".$row['SVODType']."</SVODType> \n");
				fwrite($fh,"<SVODStart>".$row['SVODStart']."</SVODStart> \n");
				fwrite($fh,"<SVODEnd>".$row['SVODEnd']."</SVODEnd> \n");
				fwrite($fh,"<FVODType>".$row['FVODType']."</FVODType> \n");
				fwrite($fh,"<FVODStart>".$row['FVODStart']."</FVODStart> \n");
				fwrite($fh,"<FVODEnd>".$row['FVODEnd']."</FVODEnd> \n");
				fwrite($fh,"<BoxOffice>".$row['BoxOffice']."</BoxOffice> \n");
				fwrite($fh,"<MusicClear>".$row['MusicClear']."</MusicClear> \n");
				fwrite($fh,"<CopyRight>".$row['CopyRight']."</CopyRight> \n");
				fwrite($fh,"<DTORights>".$row['DTORights']."</DTORights> \n");
				fwrite($fh,"<DTORightsFinal>".$row['DTORightsFinal']."</DTORightsFinal> \n");
				fwrite($fh,"<DTORightsTerritory>".$row['DTORightsTerritory']."</DTORightsTerritory> \n");
				fwrite($fh,"<RatingAgency>".$row['RatingAgency']."</RatingAgency> \n");
				fwrite($fh,"<AppleAgencyCode>".$row['AppleAgencyCode']."</AppleAgencyCode> \n");
				fwrite($fh,"<LocalReleaseDate>".$row['LocalReleaseDate']."</LocalReleaseDate> \n");
			
				fwrite($fh,"<ProductionCompany>".$row['ProductionCompany']."</ProductionCompany> \n");
				fwrite($fh,"<LocalActors>".$row['LocalActors']."</LocalActors> \n");
				fwrite($fh,"<LocalDirectors>".$row['LocalDirectors']."</LocalDirectors> \n");
				fwrite($fh,"<LocalProducers>".$row['LocalProducers']."</LocalProducers> \n");
				fwrite($fh,"<LocalWriters>".$row['LocalWriters']."</LocalWriters> \n");
				fwrite($fh,"<LocalComposers>".$row['LocalComposers']."</LocalComposers> \n");
				fwrite($fh,"<LocalRatingAgencyName>".$row['LocalRatingAgencyName']."</LocalRatingAgencyName> \n");
				fwrite($fh,"<TheatricalReleaseFlag>".$row['TheatricalReleaseFlag']."</TheatricalReleaseFlag> \n");
				fwrite($fh,"<PhysicalReleaseDate>".$row['PhysicalReleaseDate']."</PhysicalReleaseDate> \n");
				fwrite($fh,"<PhysicalReleaseFlag>".$row['PhysicalReleaseFlag']."</PhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseFlag>".$row['LocalPhysicalReleaseFlag']."</LocalPhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalTheatricalReleaseFlag>".$row['LocalTheatricalReleaseFlag']."</LocalTheatricalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseDate>".$row['LocalPhysicalReleaseDate']."</LocalPhysicalReleaseDate> \n");
			 	fwrite($fh,"<DigitalReleaseDate>".$row['DigitalReleaseDate']."</DigitalReleaseDate> \n");
                fwrite($fh,"<DigitalReleaseFlag>".$row['DigitalReleaseFlag']."</DigitalReleaseFlag> \n");
                fwrite($fh,"<LocalDigitalReleaseDate>".$row['LocalDigitalReleaseDate']."</LocalDigitalReleaseDate> \n");
                fwrite($fh,"<LocalDigitalReleaseFlag>".$row['LocalDigitalReleaseFlag']."</LocalDigitalReleaseFlag> \n");
                fwrite($fh,"<LocalRatingStatus>".$row['LocalRatingStatus']."</LocalRatingStatus> \n");
             }
             else {
				foreach($tag as $t) {
					fwrite($fh,"<".$t.">".$row[$t]."</".$t.">");
				}
			}
			fwrite($fh,"</movie> \n");
		}
		fwrite($fh,"</library>");
	}
	if(!empty($_POST['isocountry'])&&empty($_POST['title'])) {
		$iso=str_replace(" ","",$_POST['isocountry']);
		$code=explode(',',$iso);
		$size=sizeof($code);
		if(!empty($_POST['pui'])) {
			$str="SELECT * FROM titlesUpdate WHERE PPC like '$pui' AND ISOCountry IN (";
			$country=array();
			for ($i=0; $i<$size; $i++) {
				$str.="'$code[$i]',";
			}
			$str=substr($str,0,-1);
			$str.=")";
			if(!empty($_POST['version'])) {
				$str.=" AND Version like '%$version%'";
			}
		}
		else {
			$str="SELECT * FROM titlesUpdate WHERE ISOCountry IN (";
			$country=array();
			for ($i=0; $i<$size; $i++) {
				$str.="'$code[$i]',";
			}
			$str=substr($str,0,-1);
			$str.=")";
			if(!empty($_POST['version'])) {
				$str.=" AND Version like '%$version%'";
			}
		}
		$query=$str;
		$iso=array();
		$result=mysql_query($query);
		fwrite($fh,"<library> \n");
		while ($row=mysql_fetch_array($result)) {
			$counter=1;
				echo "<tr><td>".$row['PPC']."</td>";
			echo "<td>".$row['ISOCountry']."</td>";
			array_push($country,$row['ISOCountry']);
			fwrite($fh2,$row['ISOCountry'].":"."\n");
			if($row['TitleSort']==""||$row['TitleSort']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". TitleSort\n");
				$counter++;
			}
			else {
				
				echo "<td bgcolor='#00FF00'></td>";
					
			}
			if($row['LongSynopsis']==""||$row['LongSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LongSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Company']==""||$row['Company']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Company\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['CopyRight']==""||$row['CopyRight']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". CopyRight\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['LocalReleaseDate']==""||$row['LocalReleaseDate']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalReleaseDate\n");
				$counter++;
			
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['USGenre']==""||$row['USGenre']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USGenre\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['USRating']==""||$row['USRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
					
			}
			if($row['LocalRating']==""||$row['LocalRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalTitle']==""||$row['LocalTitle']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalTitle\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalSynopsis']==""||$row['LocalSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['Actors']==""||$row['Actors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Actors\n");
				$counter++;
			
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['Composers']==""||$row['Composers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Composers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Directors']==""||$row['Directors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Directors\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Producers']==""||$row['Producers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Producers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Writers']==""||$row['Writers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Writers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			echo "<td>".$row['xmldate'] ."</td></tr>";
			fwrite($fh,"<movie> \n");
			fwrite($fh,"<Territory>".$row['Territory']."</Territory> \n");
			fwrite($fh,"<ISOCountry>".$row['ISOCountry']."</ISOCountry> \n");
			fwrite($fh,"<PPC>".$row['PPC']."</PPC> \n");
			fwrite($fh,"<TitleSort>".$row['TitleSort']."</TitleSort> \n");
			fwrite($fh,"<Version>".$row['Version']."</Version> \n");
			fwrite($fh,"<Language>".$row['Language']."</Language> \n");
			fwrite($fh,"<xmldate>".$row['xmldate']."</xmldate> \n");

			if(sizeof($tag)==0) {


				fwrite($fh,"<ReleaseYear>".$row['ReleaseYear']."</ReleaseYear> \n");
				fwrite($fh,"<TitleKind>".$row['TitleKind']."</TitleKind> \n");
				fwrite($fh,"<TitleType>".$row['TitleType']."</TitleType> \n");
				fwrite($fh,"<PUI>".$row['PUI']."</PUI> \n");
				fwrite($fh,"<RT>".$row['RT']."</RT> \n");
				fwrite($fh,"<USRating>".$row['USRating']."</USRating> \n");
				fwrite($fh,"<USRatingReason>".$row['USRatingReason']."</USRatingReason> \n");
				fwrite($fh,"<LocalRating>".$row['LocalRating']."</LocalRating> \n");
				fwrite($fh,"<LocalRatingReason>".$row['LocalRatingReason']."</LocalRatingReason> \n");
				fwrite($fh,"<LocalTitle>".$row['LocalTitle']."</LocalTitle> \n");
				fwrite($fh,"<LocalSynopsis>".$row['LocalSynopsis']."</LocalSynopsis> \n");
				fwrite($fh,"<ShortSynopsis>".$row['ShortSynopsis']."</ShortSynopsis> \n");
				fwrite($fh,"<LongSynopsis>".$row['LongSynopsis']."</LongSynopsis> \n");
				fwrite($fh,"<ISOLanguage>".$row['ISOLanguage']."</ISOLanguage> \n");
				fwrite($fh,"<Company>".$row['Company']."</Company> \n");
				fwrite($fh,"<USGenre>".$row['USGenre']."</USGenre> \n");
				fwrite($fh,"<LocalGenre>".$row['LocalGenre']."</LocalGenre> \n");
				fwrite($fh,"<DTOStart>".$row['DTOStart']."</DTOStart> \n");
				fwrite($fh,"<DTOSolicitationDate>".$row['DTOSolicitationDate']."</DTOSolicitationDate> \n");
				fwrite($fh,"<DTOPrice>".$row['DTOPrice']."</DTOPrice> \n");
				fwrite($fh,"<AppleTier>".$row['AppleTier']."</AppleTier> \n");
				fwrite($fh,"<Actors>".$row['Actors']."</Actors> \n");
				fwrite($fh,"<Composers>".$row['Composers']."</Composers> \n");
				fwrite($fh,"<Directors>".$row['Directors']."</Directors> \n");
				fwrite($fh,"<Producers>".$row['Producers']."</Producers> \n");
				fwrite($fh,"<Writers>".$row['Writers']."</Writers> \n");
				fwrite($fh,"<VODType>".$row['VODType']."</VODType> \n");
				fwrite($fh,"<VODStart>".$row['VODStart']."</VODStart> \n");
				fwrite($fh,"<VODEnd>".$row['VODEnd']."</VODEnd> \n");
				fwrite($fh,"<SVODType>".$row['SVODType']."</SVODType> \n");
				fwrite($fh,"<SVODStart>".$row['SVODStart']."</SVODStart> \n");
				fwrite($fh,"<SVODEnd>".$row['SVODEnd']."</SVODEnd> \n");
				fwrite($fh,"<FVODType>".$row['FVODType']."</FVODType> \n");
				fwrite($fh,"<FVODStart>".$row['FVODStart']."</FVODStart> \n");
				fwrite($fh,"<FVODEnd>".$row['FVODEnd']."</FVODEnd> \n");
				fwrite($fh,"<BoxOffice>".$row['BoxOffice']."</BoxOffice> \n");
				fwrite($fh,"<MusicClear>".$row['MusicClear']."</MusicClear> \n");
				fwrite($fh,"<CopyRight>".$row['CopyRight']."</CopyRight> \n");
				fwrite($fh,"<DTORights>".$row['DTORights']."</DTORights> \n");
				fwrite($fh,"<DTORightsFinal>".$row['DTORightsFinal']."</DTORightsFinal> \n");
				fwrite($fh,"<DTORightsTerritory>".$row['DTORightsTerritory']."</DTORightsTerritory> \n");
				fwrite($fh,"<RatingAgency>".$row['RatingAgency']."</RatingAgency> \n");
				fwrite($fh,"<AppleAgencyCode>".$row['AppleAgencyCode']."</AppleAgencyCode> \n");
				fwrite($fh,"<LocalReleaseDate>".$row['LocalReleaseDate']."</LocalReleaseDate> \n");
			
				fwrite($fh,"<ProductionCompany>".$row['ProductionCompany']."</ProductionCompany> \n");
				fwrite($fh,"<LocalActors>".$row['LocalActors']."</LocalActors> \n");
				fwrite($fh,"<LocalDirectors>".$row['LocalDirectors']."</LocalDirectors> \n");
				fwrite($fh,"<LocalProducers>".$row['LocalProducers']."</LocalProducers> \n");
				fwrite($fh,"<LocalWriters>".$row['LocalWriters']."</LocalWriters> \n");
				fwrite($fh,"<LocalComposers>".$row['LocalComposers']."</LocalComposers> \n");
				fwrite($fh,"<LocalRatingAgencyName>".$row['LocalRatingAgencyName']."</LocalRatingAgencyName> \n");
				fwrite($fh,"<TheatricalReleaseFlag>".$row['TheatricalReleaseFlag']."</TheatricalReleaseFlag> \n");
				fwrite($fh,"<PhysicalReleaseDate>".$row['PhysicalReleaseDate']."</PhysicalReleaseDate> \n");
				fwrite($fh,"<PhysicalReleaseFlag>".$row['PhysicalReleaseFlag']."</PhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseFlag>".$row['LocalPhysicalReleaseFlag']."</LocalPhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalTheatricalReleaseFlag>".$row['LocalTheatricalReleaseFlag']."</LocalTheatricalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseDate>".$row['LocalPhysicalReleaseDate']."</LocalPhysicalReleaseDate> \n");
			 	fwrite($fh,"<DigitalReleaseDate>".$row['DigitalReleaseDate']."</DigitalReleaseDate> \n");
                fwrite($fh,"<DigitalReleaseFlag>".$row['DigitalReleaseFlag']."</DigitalReleaseFlag> \n");
               	fwrite($fh,"<LocalDigitalReleaseDate>".$row['LocalDigitalReleaseDate']."</LocalDigitalReleaseDate> \n");
               	fwrite($fh,"<LocalDigitalReleaseFlag>".$row['LocalDigitalReleaseFlag']."</LocalDigitalReleaseFlag> \n");
                fwrite($fh,"<LocalRatingStatus>".$row['LocalRatingStatus']."</LocalRatingStatus> \n");
            }
             else {
				foreach($tag as $t) {
					fwrite($fh,"<".$t.">".$row[$t]."</".$t.">");
				}
			}
			
			fwrite($fh,"</movie> \n");
		}
		fwrite($fh,"</library>");
	}
	if(empty($_POST['title'])&&empty($_POST['isocountry'])) {
		if(!empty($_POST['pui'])) {
			$query="SELECT * FROM titlesUpdate WHERE PPC like '$pui'";
			if(!empty($_POST['version'])) {
				$str.=" AND Version like '%$version%'";
			}
		}
		else {
			//$query="SELECT * FROM titlesUpdate WHERE PPC like '$pui'";
			$query="SELECT * FROM titlesUpdate WHERE Version like '%$pui%'";
		}
		//$query=$str.")";
		//echo "4 ".$query;
		$result=mysql_query($query);
			
			
		fwrite($fh,"<library> \n");
		while ($row=mysql_fetch_array($result)) {
			$counter=1;
				echo "<tr><td>".$row['PPC']."</td>";
			echo "<td>".$row['ISOCountry']."</td>";
			array_push($country,$row['ISOCountry']);
			fwrite($fh2,$row['ISOCountry'].":"."\n");
			if($row['TitleSort']==""||$row['TitleSort']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". TitleSort\n");
				$counter++;
			}
			else {
				
				echo "<td bgcolor='#00FF00'></td>";
					
			}
			if($row['LongSynopsis']==""||$row['LongSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LongSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Company']==""||$row['Company']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Company\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['CopyRight']==""||$row['CopyRight']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". CopyRight\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['LocalReleaseDate']==""||$row['LocalReleaseDate']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalReleaseDate\n");
				$counter++;
			
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['USGenre']==""||$row['USGenre']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USGenre\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['USRating']==""||$row['USRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". USRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
					
			}
			if($row['LocalRating']==""||$row['LocalRating']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalRating\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalTitle']==""||$row['LocalTitle']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalTitle\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['LocalSynopsis']==""||$row['LocalSynopsis']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". LocalSynopsis\n");
				$counter++;
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['Actors']==""||$row['Actors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Actors\n");
				$counter++;
			
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			if($row['Composers']==""||$row['Composers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Composers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Directors']==""||$row['Directors']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Directors\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Producers']==""||$row['Producers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Producers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['Writers']==""||$row['Writers']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2," ".$counter.". Writers\n");
				$counter++;
					
			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			}
			
			echo "<td>".$row['xmldate'] ."</td></tr>";
			fwrite($fh,"<movie> \n");
			fwrite($fh,"<Territory>".$row['Territory']."</Territory> \n");
			fwrite($fh,"<ISOCountry>".$row['ISOCountry']."</ISOCountry> \n");
			fwrite($fh,"<PPC>".$row['PPC']."</PPC> \n");
			fwrite($fh,"<TitleSort>".$row['TitleSort']."</TitleSort> \n");
			fwrite($fh,"<Version>".$row['Version']."</Version> \n");
			fwrite($fh,"<Language>".$row['Language']."</Language> \n");
			fwrite($fh,"<xmldate>".$row['xmldate']."</xmldate> \n");

			if(sizeof($tag)==0) {


				fwrite($fh,"<ReleaseYear>".$row['ReleaseYear']."</ReleaseYear> \n");
				fwrite($fh,"<TitleKind>".$row['TitleKind']."</TitleKind> \n");
				fwrite($fh,"<TitleType>".$row['TitleType']."</TitleType> \n");
				fwrite($fh,"<PUI>".$row['PUI']."</PUI> \n");
				fwrite($fh,"<RT>".$row['RT']."</RT> \n");
				fwrite($fh,"<USRating>".$row['USRating']."</USRating> \n");
				fwrite($fh,"<USRatingReason>".$row['USRatingReason']."</USRatingReason> \n");
				fwrite($fh,"<LocalRating>".$row['LocalRating']."</LocalRating> \n");
				fwrite($fh,"<LocalRatingReason>".$row['LocalRatingReason']."</LocalRatingReason> \n");
				fwrite($fh,"<LocalTitle>".$row['LocalTitle']."</LocalTitle> \n");
				fwrite($fh,"<LocalSynopsis>".$row['LocalSynopsis']."</LocalSynopsis> \n");
				fwrite($fh,"<ShortSynopsis>".$row['ShortSynopsis']."</ShortSynopsis> \n");
				fwrite($fh,"<LongSynopsis>".$row['LongSynopsis']."</LongSynopsis> \n");
				fwrite($fh,"<ISOLanguage>".$row['ISOLanguage']."</ISOLanguage> \n");
				fwrite($fh,"<Company>".$row['Company']."</Company> \n");
				fwrite($fh,"<USGenre>".$row['USGenre']."</USGenre> \n");
				fwrite($fh,"<LocalGenre>".$row['LocalGenre']."</LocalGenre> \n");
				fwrite($fh,"<DTOStart>".$row['DTOStart']."</DTOStart> \n");
				fwrite($fh,"<DTOSolicitationDate>".$row['DTOSolicitationDate']."</DTOSolicitationDate> \n");
				fwrite($fh,"<DTOPrice>".$row['DTOPrice']."</DTOPrice> \n");
				fwrite($fh,"<AppleTier>".$row['AppleTier']."</AppleTier> \n");
				fwrite($fh,"<Actors>".$row['Actors']."</Actors> \n");
				fwrite($fh,"<Composers>".$row['Composers']."</Composers> \n");
				fwrite($fh,"<Directors>".$row['Directors']."</Directors> \n");
				fwrite($fh,"<Producers>".$row['Producers']."</Producers> \n");
				fwrite($fh,"<Writers>".$row['Writers']."</Writers> \n");
				fwrite($fh,"<VODType>".$row['VODType']."</VODType> \n");
				fwrite($fh,"<VODStart>".$row['VODStart']."</VODStart> \n");
				fwrite($fh,"<VODEnd>".$row['VODEnd']."</VODEnd> \n");
				fwrite($fh,"<SVODType>".$row['SVODType']."</SVODType> \n");
				fwrite($fh,"<SVODStart>".$row['SVODStart']."</SVODStart> \n");
				fwrite($fh,"<SVODEnd>".$row['SVODEnd']."</SVODEnd> \n");
				fwrite($fh,"<FVODType>".$row['FVODType']."</FVODType> \n");
				fwrite($fh,"<FVODStart>".$row['FVODStart']."</FVODStart> \n");
				fwrite($fh,"<FVODEnd>".$row['FVODEnd']."</FVODEnd> \n");
				fwrite($fh,"<BoxOffice>".$row['BoxOffice']."</BoxOffice> \n");
				fwrite($fh,"<MusicClear>".$row['MusicClear']."</MusicClear> \n");
				fwrite($fh,"<CopyRight>".$row['CopyRight']."</CopyRight> \n");
				fwrite($fh,"<DTORights>".$row['DTORights']."</DTORights> \n");
				fwrite($fh,"<DTORightsFinal>".$row['DTORightsFinal']."</DTORightsFinal> \n");
				fwrite($fh,"<DTORightsTerritory>".$row['DTORightsTerritory']."</DTORightsTerritory> \n");
				fwrite($fh,"<RatingAgency>".$row['RatingAgency']."</RatingAgency> \n");
				fwrite($fh,"<AppleAgencyCode>".$row['AppleAgencyCode']."</AppleAgencyCode> \n");
				fwrite($fh,"<LocalReleaseDate>".$row['LocalReleaseDate']."</LocalReleaseDate> \n");
			
				fwrite($fh,"<ProductionCompany>".$row['ProductionCompany']."</ProductionCompany> \n");
				fwrite($fh,"<LocalActors>".$row['LocalActors']."</LocalActors> \n");
				fwrite($fh,"<LocalDirectors>".$row['LocalDirectors']."</LocalDirectors> \n");
				fwrite($fh,"<LocalProducers>".$row['LocalProducers']."</LocalProducers> \n");
				fwrite($fh,"<LocalWriters>".$row['LocalWriters']."</LocalWriters> \n");
				fwrite($fh,"<LocalComposers>".$row['LocalComposers']."</LocalComposers> \n");
				fwrite($fh,"<LocalRatingAgencyName>".$row['LocalRatingAgencyName']."</LocalRatingAgencyName> \n");
				fwrite($fh,"<TheatricalReleaseFlag>".$row['TheatricalReleaseFlag']."</TheatricalReleaseFlag> \n");
				fwrite($fh,"<PhysicalReleaseDate>".$row['PhysicalReleaseDate']."</PhysicalReleaseDate> \n");
				fwrite($fh,"<PhysicalReleaseFlag>".$row['PhysicalReleaseFlag']."</PhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseFlag>".$row['LocalPhysicalReleaseFlag']."</LocalPhysicalReleaseFlag> \n");
				fwrite($fh,"<LocalTheatricalReleaseFlag>".$row['LocalTheatricalReleaseFlag']."</LocalTheatricalReleaseFlag> \n");
				fwrite($fh,"<LocalPhysicalReleaseDate>".$row['LocalPhysicalReleaseDate']."</LocalPhysicalReleaseDate> \n");
			 	fwrite($fh,"<DigitalReleaseDate>".$row['DigitalReleaseDate']."</DigitalReleaseDate> \n");
                fwrite($fh,"<DigitalReleaseFlag>".$row['DigitalReleaseFlag']."</DigitalReleaseFlag> \n");
                fwrite($fh,"<LocalDigitalReleaseDate>".$row['LocalDigitalReleaseDate']."</LocalDigitalReleaseDate> \n");
                fwrite($fh,"<LocalDigitalReleaseFlag>".$row['LocalDigitalReleaseFlag']."</LocalDigitalReleaseFlag> \n");
                fwrite($fh,"<LocalRatingStatus>".$row['LocalRatingStatus']."</LocalRatingStatus> \n");
            }
            else {
				foreach($tag as $t) {
					fwrite($fh,"<".$t.">".$row[$t]."</".$t.">");
				}
			}
			
			fwrite($fh,"</movie> \n");
		}
		fwrite($fh,"</library>");
	}
	fclose($fh2);
	fclose($fh);
	foreach(glob($myFile) as $path_to_file) {
    		$file_contents = file_get_contents($path_to_file);
    		$file_contents = str_replace("&",",&amp;",$file_contents);
    		file_put_contents($path_to_file,$file_contents);
	}
	echo "<br>";
	echo "<br>";
	echo "<a href='http://mvf-paramount-web/web/$myFile'>Download XML Report</a>";
	echo "&nbsp;";
	echo "&nbsp;";
	echo "<a href='http://mvf-paramount-web/web/$myFile2'>Download TXT Report</a>";
	echo "<br>";
	echo "<br>";
}
echo "</table>"; mysql_close(); ob_end();
?>