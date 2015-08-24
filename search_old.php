<?php
ob_start();
 mysql_connect("mvf-paramount-web:3306", "root", "skunkstripe") or die(mysql_error());
 mysql_select_db("paramountmdl") or die(mysql_error());
 //$europe=array("AUSTRIA","BELGIUM-DUTCH","BELGIUM-FRENCH","BULGARIA","CROATIA","CZECH REPUBLIC","DENMARK","ESTONIA","FINLAND","FRANCE","GERMANY",
 //"GREECE","HUNGARY","ICELAND","IRELAND","ITALY","LATVIA","LITHUANIA","LUXEMBOURG-DUTCH","LUXEMBOURG-FRENCH","LUXEMBOURG-GERMAN","MACEDONIA","MONTENEGRO","NETHERLANDS",
 //"NORWAY","POLAND","PORTUGAL","ROMANIA","SERBIA","SLOVAKIA","SLOVENIA","SPAIN","SPAIN-CATALAN","SWEDEN","SWITZERLAND-FRENCH","SWITZERLAND-GERMAN","SWITZERLAND-ITALIAN","TURKEY","UKRAINE","UNITED KINGDOM");
 $australia=array("AU","NZ");
 $domestic=array("US","CA");
 $europeFigs=array("FR","DE","IT","ES");
 $europeUK=array("GB","IE");
 $latinAmerica=array("AR","BO","BR","CI","CO","CR","DO","EC","SV","GT","HN","MX","NI","PA","PY","PE","VE");
 $panAsian=array("BN","KH","HK","ID","LA","MO","MY","PH","SG","LK","TW","TH","VN");
 $panEurope=array("AT","BE","BG","CY","CZ","DK","EE","FI","GR","HU","LV","LT","LU","MT","NL","NO","PL","RO","SK","SI","SE","CH");
 $chi=array("IT","CH");
 $denl=array("DE","AT","BE","NL","CH","LU");
 $europe=array("FR","ES","BN","KH","HK","ID","LA","MO","MY","PH","SG","LK","TW","TH","VN","BE","BG","CY","CZ","DK","EE","FI","GR","HU","LV","LT","LU","MT","NO","PL","PT","RO","SK","SI","SE","CH","RU");
 $northAmerica=array("AU","NZ","CA","US","IE","GB","AR","BO","BR","CI","CO","CR","DO","EC","SV","GT","HN","MX","NI","PA","PY","PE","VE");

 //$middleEast=array("EGYPT","GULF STATES","ISRAEL","KUWAIT","LEBANON","PAKISTAN","SAUDI ARABIA");
 //$asia=array("BANGLADESH","CAMBODIA","CHINA","HONG KONG","INDIA","INDONESIA","JAPAN","MALAYSIA","NEPAL","PHILIPPINES","RUSSIA","SINGAPORE","SOUTH KOREA","TAIWAN","THAILAND","VIETNAM");
 //$northAmerica=array("CANADA","CANADA-FRENCH","MEXICO","UNITED STATES");
 //$southAmerica=array("VENEZUELA","URUGUAY","PERU","COLOMBIA","CHILE","BRAZIL","ARGENTINA");
 //$centralAmerica=array("CENTRAL AMERICA");
 //$africa=array("SOUTH AFRICA");
 //$australia=array("AUSTRALIA","NEW ZEALAND");
 //$myFile1 = "report".date('m-d-Y-His').".xml";
 $myFile = "report-".date('m-d-Y-His').".xml";
 $date=date('m-d-Y-His');
 $fh = fopen($myFile, 'w');
 
 $myFile2 = "report-".date('m-d-Y-His').".txt";
 $date=date('m-d-Y-His');
 $fh2 = fopen($myFile2, 'w');


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
<SCRIPT SRC="javascript/jquery-1.9.1.js"> </SCRIPT>
<SCRIPT>
$(document).ready(function() {

        $("#showAustralia").click(function() {
		$(".australia").toggle(function() {
			var $x=$(".australia input:checkbox"); 
			$x.prop("checked", true);
		});
			
        });

	 $("#showDomestic").click(function() {
                $(".domestic").toggle(function() {
			var $x=$(".domestic input:checkbox");
                        $x.prop("checked", true);
                });

        });
	 $("#showEuropeFigs").click(function() {
                $(".europeFigs").toggle(function() {
                        var $x=$(".europeFigs input:checkbox");
                        $x.prop("checked", true);
		 });

        });
	$("#showEuropeUK").click(function() {
                $(".europeUK").toggle( function() {
                        var $x=$(".europeUK input:checkbox");
                        $x.prop("checked", true);
                 });

        });
  	$("#showLatinAmerica").click(function() {
                $(".latinAmerica").toggle(function() {
                        var $x=$(".latinAmerica input:checkbox");
                        $x.prop("checked", true);
                 });

        });
	 $("#showPanAsian").click(function() {
                $(".panAsian").toggle(function() {
                        var $x=$(".panAsian input:checkbox");
                        $x.prop("checked", true);
                 });

        });
	 $("#showPanEurope").click(function() {
                $(".panEurope").toggle(function() {
                        var $x=$(".panEurope input:checkbox");
                        $x.prop("checked", true);
                 });

        });

	 $("#showChi").click(function() {
                $(".chi").toggle(function() {
                        var $x=$(".chi input:checkbox");
                        $x.prop("checked", true);
                 });

        });

	 $("#showDenl").click(function() {
                $(".denl").toggle(function() {
                        var $x=$(".denl input:checkbox");
                        $x.prop("checked", true);
                 });

        });
	 $("#showEurope").click(function() {
                $(".europe").toggle(function() {
                        var $x=$(".europe input:checkbox");
                        $x.prop("checked", true);
                 });

        });
	$("#showNorthAmerica").click(function() {
                $(".northAmerica").toggle(function() {
                        var $x=$(".northAmerica input:checkbox");
                        $x.prop("checked", true);
                 });

        });



});

</SCRIPT>

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

</head>
<body>
<table width="800" border="1" align="center" cellpadding="0"
                cellspacing="1" >
<tr>
<form name="form1" id="form1" method="post" onsubmit="return checkIt();" action="<?php echo $PHP_SELF; ?>">
	<td>    
	    <table name="search" align="center" width="100%" border="0" cellpadding="7" cellspacing="0">
        	<tr><td colspan="5" align="center"><h3>MetaData Search</h3></td></tr>


<tr><td colspan="2">PPC: <input name="pui" id="pui" type="text"> </td> <td colspan="2">ISOCountry: <input name="isocountry" id="isocuntry" type="text" value="US"></td>
<tr>
<tr><td width="13%">Australia: <input type="checkbox" name="australia" id="showAustralia"></td> <td width="12%">Domestic-US/CA: <input type="checkbox" name="domestic" id="showDomestic"></td> 
<td width="12%">Europe-Figs: <input type="checkbox" name="europeFigs" id="showEuropeFigs"> </td><td width="12%"> Europe-UK/IE: <input type="checkbox" name="europeUK" id="showEuropeUK"> </td></tr>
<tr><td width="13%">Latin America: <input type="checkbox" name="latinAmerica" id="showLatinAmerica"> </td><td width="12%"> Pan Asia: <input type="checkbox" name="panAsian" id="showPanAsian"></td>
<td width="12%"> Pan Europe: <input type="checkbox" name="panEurope" id="showPanEurope"> </td><td width="12%">CHI: <input type="checkbox" name="chi" value="Australia" id="showChi"></td></tr>

<tr><td width="13%">DENL: <input type="checkbox" name="denl" id="showDenl"> </td><td width="12%"> EU: <input type="checkbox" name="europe" id="showEurope"></td>
<td width="12%"> NA: <input type="checkbox" name="northAmerica" id="showNorthAmerica"> </td></tr>

<tr><td colspan="4"></td></tr>
<tr><td colspan="4"></td></tr>
<tr><td colspan="4"></td></tr>
<tr><td colspan="4"></td></tr>
<?php
	//$result=mysql_query("SELECT DISTINCT ISOCountry from titles") or die(mysql_error());

	foreach($australia as $a) {
		echo "<tr class='australia' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
	}
	foreach($domestic as $a) {
                echo "<tr class='domestic' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
       }

	foreach($europeFigs as $a) {
                echo "<tr class='europeFigs' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";    
        }
	foreach($europeUK as $a) {        
               echo "<tr class='europeUK' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
               
        }
	foreach($latinAmerica as $a) {
               echo "<tr class='latinAmerica' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
         }

        foreach($panAsian as $a) {
              echo "<tr class='panAsian' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
               
        }

        foreach($panEurope as $a) {
              echo "<tr class='panEurope' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
        }

        foreach($chi as $a) {
              echo "<tr class='chi' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
               
        }
        foreach($denl as $a) {
                  
              echo "<tr class='denl' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
                  
        }
        foreach($europe as $a) {
                     
             echo "<tr class='europe' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
                   
        }
        foreach($northAmerica as $a) {
                     
             echo "<tr class='northAmerica' style='display: none;'><td>".$a."<input type='checkbox' name='territory[]' value='$a'></td>";
                    
        }

	

/*	while ($row= mysql_fetch_array($result)){
		$code=$row['ISOCountry'];
		foreach($australia as $a) {
			if($code==$a) {
				echo "<tr class='australia' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'></td>";
			}
		}
		foreach($domestic as $a) {
                        if($code==$a) {
                                echo "<tr class='domestic' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }

		foreach($europeFigs as $a) {
                        if($code==$a) {
                                echo "<tr class='europeFigs' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }

		foreach($europeUK as $a) {
                        if($code==$a) {
                                echo "<tr class='europeUK' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }
		foreach($latinAmerica as $a) {
                        if($code==$a) {
                                echo "<tr class='latinAmerica' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }

		foreach($panAsian as $a) {
                        if($code==$a) {
                                echo "<tr class='panAsian' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }

		foreach($panEurope as $a) {
                        if($code==$a) {
                                echo "<tr class='panEurope' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }

		foreach($chi as $a) {
                        if($code==$a) {
                                echo "<tr class='chi' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }
		 foreach($denl as $a) {
                        if($code==$a) {
                                echo "<tr class='denl' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }
		 foreach($europe as $a) {
                        if($code==$a) {
                                echo "<tr class='europe' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }
		 foreach($northAmerica as $a) {
                        if($code==$a) {
                                echo "<tr class='northAmerica' style='display: none;'><td>".$code."<input type='checkbox' name='territory[]' value='$code'>";
                        }
                }





		
	}*/

?>
			<tr><td colspan="4" align="center"><input type="submit" name="submit" value="Submit"></td></tr>
			</table>
		</td>
	</form>
	</tr>
</table>
</body>

<?php
if(isset($_POST['submit'])) {
	$pui=$_POST['pui'];
	$check="";
	echo "<br>";
        echo "<table width='60%' id='myTable' border='1' align='center' cellpadding='0'
                        cellspacing='1' col='12'>";
        echo "<tr>";
        echo "<th>ISOCountry</th>";
	echo "<th>TitleSort</th>";
	echo "<th>LongSynopsis</th>";
	echo "<th>Company</th>";
	echo "<th>CopyRight</th>";
	echo "<th>USGenre</th>";
	echo "<th>RatingAgency</th>";
	echo "<th>USRating</th>";
	echo "<th>LocalRatingAgency</th>";
	echo "<th>LocalRating</th>";
	echo "<th>ISOLanguage</th>";
	 echo "<th>LocalTitle</th>";
	echo "<th>LocalSynopsis</th>";
	echo "</tr>";
	
	if(!empty($_POST['isocountry'])) {
		$iso=str_replace(" ","",$_POST['isocountry']);
		$code=explode(',',$iso);
	        $size=sizeof($code);
        	$str="SELECT * FROM titles WHERE PPC like '$pui' AND ISOCountry IN (";
		$country=array();
		for ($i=0; $i<$size; $i++) {
			$str.="'$code[$i]',";
		}
		$str=substr($str,0,-1);
		
		if(!empty($_POST['territory'])) {
			$str.=") OR PPC like '$pui' AND ISOCountry IN (";
			foreach($_POST['territory'] as $territory) {
				$str.="'$territory',";
			}	
		$str=substr($str,0,-1); 		
		}
		$query=$str.")";
		$result=mysql_query($query);
		 if(mysql_num_rows($result)==0) {
			 foreach($_POST['territory'] as $territory) {
                         	echo $territory." ISOCountry doesn't exist";
                                echo "<br>";
                        }

		}
		
		
		fwrite($fh,"<library> \n");
		while ($row=mysql_fetch_array($result)) {
			$counter=1;
		
			echo "<tr><td>".$row['ISOCountry']."</td>";
			array_push($country,$row['ISOCountry']);
			fwrite($fh2,$row['ISOCountry'].":"."\n");
			if($row['TitleSort']==""||$row['TitleSort']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". TitleSort\n");
                                $counter++;

			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			
			}
			if($row['LongSynopsis']==""||$row['LongSynopsis']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
                        	fwrite($fh2,"   ".$counter.". LongSynopsis\n");
                                $counter++;

			}
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['Company']==""||$row['Company']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". Company\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			if($row['CopyRight']==""||$row['CopyRight']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". CopyRight\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			 if($row['USGenre']==""||$row['USGenre']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				 fwrite($fh2,"   ".$counter.". USGenre\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
	
			}
			if($row['RatingAgency']==""||$row['RatingAgency']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
        			fwrite($fh2,"   ".$counter.". RatingAgency\n");
                                $counter++;
 
	               }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['USRating']==""||$row['USRating']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". USRating\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
			
                        }
			if($row['LocalRatingAgency']==""||$row['LocalRatingAgency']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalRatingAgency\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			if($row['LocalRating']==""||$row['LocalRating']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalRating\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['ISOLanguage']==""||$row['ISOLanguage']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". ISOLanguage\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['LocalTitle']==""||$row['LocalTitle']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";	
				fwrite($fh2,"   ".$counter.". LocalTitle\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['LocalSynopsis']==""||$row['LocalSynopsis']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalSynopsis\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td></tr>";
                        }
			 
                       

				fwrite($fh,"<movie> \n");

                	        fwrite($fh,"<Territory>".$row['Territory']."</Territory> \n");
                                fwrite($fh,"<ISOCountry>".$row['ISOCountry']."</ISOCountry> \n");
                                fwrite($fh,"<PPC>".$row['PPC']."</PPC> \n");
                                fwrite($fh,"<TitleSort>".$row['TitleSort']."</TitleSort> \n");
                                fwrite($fh,"<ReleaseYear>".$row['ReleaseYear']."</ReleaseYear> \n");
                                fwrite($fh,"<TitleKind>".$row['TitleKind']."</TitleKind> \n");
                                fwrite($fh,"<TitleType>".$row['TitleType']."</TitleType> \n");
                                fwrite($fh,"<PUI>".$row['PUI']."</PUI> \n");
                                fwrite($fh,"<RT>".$row['RT']."</RT> \n");
                                fwrite($fh,"<Version>".$row['Version']."</Version> \n");
                                fwrite($fh,"<USRating>".$row['USRating']."</USRating> \n");
                                fwrite($fh,"<USRatingReason>".$row['USRatingReason']."</USRatingReason> \n");
                                fwrite($fh,"<LocalRating>".$row['LocalRating']."</LocalRating> \n");
                                fwrite($fh,"<LocalRatingReason>".$row['LocalRatingReason']."</LocalRatingReason> \n");
                                fwrite($fh,"<LocaleTitle>".$row['LocalTitle']."</LocaleTitle> \n");
                                fwrite($fh,"<LocalSynopsis>".$row['LocalSynopsis']."</LocalSynopsis> \n");
                                fwrite($fh,"<ShortSynopsis>".$row['ShortSynopsis']."</ShortSynopsis> \n");
                                fwrite($fh,"<LongSynopsis>".$row['LongSynopsis']."</LongSynopsis> \n");
                                fwrite($fh,"<Language>".$row['Language']."</Language> \n");
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
                                fwrite($fh,"<LocalRatingAgency>".$row['LocalRatingAgency']."</LocalRatingAgency> \n");
                                fwrite($fh,"<xmldate>".$row['xmldate']."</xmldate> \n");

			fwrite($fh,"</movie> \n");
		}
		fwrite($fh,"</library>");
		$result=array_merge($country,$code);
		foreach($_POST['territory'] as $territory) {
                                if(!in_array($territory,$result)) {
                                        echo $territory." ISOCountry code doesn't exist";
                                        echo "<br>";
                                }
                        }
		
	}
	else {
		
		 if(!empty($_POST['territory'])) {
			$str="SELECT * FROM titles WHERE PPC like '$pui' AND ISOCountry In (";
                        foreach($_POST['territory'] as $territory) {
                                $str.="'$territory',";
                        }
                	$str=substr($str,0,-1);
			$query=$str.")";
			$iso=array();
			$result=mysql_query($query);
			if(mysql_num_rows($result)==0) {
				foreach($_POST['territory'] as $territory) {
                                	echo $territory." ISOCountry doesn't exist";
					echo "<br>";  
                                }
			}	
	                fwrite($fh,"<library> \n");
			while ($row=mysql_fetch_array($result)) {
				$counter=1;
				echo "<tr><td>".$row['ISOCountry']."</td>";
			array_push($iso,$row['ISOCountry']);
			fwrite($fh2,$row['ISOCountry'].":"."\n");
			if($row['TitleSort']==""||$row['TitleSort']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". TitleSort\n");
                                $counter++;

			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			
			}
			if($row['LongSynopsis']==""||$row['LongSynopsis']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
                        	fwrite($fh2,"   ".$counter.". LongSynopsis\n");
                                $counter++;

			}
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['Company']==""||$row['Company']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". Company\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			if($row['CopyRight']==""||$row['CopyRight']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". CopyRight\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			 if($row['USGenre']==""||$row['USGenre']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				 fwrite($fh2,"   ".$counter.". USGenre\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
	
			}
			if($row['RatingAgency']==""||$row['RatingAgency']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
        			fwrite($fh2,"   ".$counter.". RatingAgency\n");
                                $counter++;
 
	               }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['USRating']==""||$row['USRating']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". USRating\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
			
                        }
			if($row['LocalRatingAgency']==""||$row['LocalRatingAgency']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalRatingAgency\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			if($row['LocalRating']==""||$row['LocalRating']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalRating\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['ISOLanguage']==""||$row['ISOLanguage']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". ISOLanguage\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['LocalTitle']==""||$row['LocalTitle']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";	
				fwrite($fh2,"   ".$counter.". LocalTitle\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['LocalSynopsis']==""||$row['LocalSynopsis']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalSynopsis\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td></tr>";
                        }	

			
           


				fwrite($fh,"<movie> \n");
                        	fwrite($fh,"<Territory>".$row['Territory']."</Territory> \n");
                        	fwrite($fh,"<ISOCountry>".$row['ISOCountry']."</ISOCountry> \n");
                        	fwrite($fh,"<PPC>".$row['PPC']."</PPC> \n");
                        	fwrite($fh,"<TitleSort>".$row['TitleSort']."</TitleSort> \n");
                        	fwrite($fh,"<ReleaseYear>".$row['ReleaseYear']."</ReleaseYear> \n");
                        	fwrite($fh,"<TitleKind>".$row['TitleKind']."</TitleKind> \n");
                        	fwrite($fh,"<TitleType>".$row['TitleType']."</TitleType> \n");
                        	fwrite($fh,"<PUI>".$row['PUI']."</PUI> \n");
                        	fwrite($fh,"<RT>".$row['RT']."</RT> \n");
                        	fwrite($fh,"<Version>".$row['Version']."</Version> \n");
                        	fwrite($fh,"<USRating>".$row['USRating']."</USRating> \n");
                        	fwrite($fh,"<USRatingReason>".$row['USRatingReason']."</USRatingReason> \n");
                        	fwrite($fh,"<LocalRating>".$row['LocalRating']."</LocalRating> \n");
                        	fwrite($fh,"<LocalRatingReason>".$row['LocalRatingReason']."</LocalRatingReason> \n");
                        	fwrite($fh,"<LocaleTitle>".$row['LocalTitle']."</LocaleTitle> \n");
                        	fwrite($fh,"<LocalSynopsis>".$row['LocalSynopsis']."</LocalSynopsis> \n");
                        	fwrite($fh,"<ShortSynopsis>".$row['ShortSynopsis']."</ShortSynopsis> \n");
                        	fwrite($fh,"<LongSynopsis>".$row['LongSynopsis']."</LongSynopsis> \n");
                        	fwrite($fh,"<Language>".$row['Language']."</Language> \n");
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
                        	fwrite($fh,"<LocalRatingAgency>".$row['LocalRatingAgency']."</LocalRatingAgency> \n");
                        	fwrite($fh,"<xmldate>".$row['xmldate']."</xmldate> \n");

                        	fwrite($fh,"</movie> \n");
	
                	}
                	fwrite($fh,"</library>");

			foreach($_POST['territory'] as $territory) {
				if(!in_array($territory,$iso)) {
					echo $territory." ISOCountry code doesn't exist";
					echo "<br>";
				}
			}	

		}
		else {
			$query="SELECT * FROM titles WHERE PPC like '$pui'";
			$result=mysql_query($query);
                        fwrite($fh,"<library> \n");
			while ($row=mysql_fetch_array($result)) {
				$counter=1;
				echo "<tr><td>".$row['ISOCountry']."</td>";
				fwrite($fh2,$row['ISOCountry'].":"."\n");
			if($row['TitleSort']==""||$row['TitleSort']=="N/A") {
				echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". TitleSort\n");
                                $counter++;

			}
			else {
				echo "<td bgcolor='#00FF00'></td>";
			
			}
			if($row['LongSynopsis']==""||$row['LongSynopsis']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
                        	fwrite($fh2,"   ".$counter.". LongSynopsis\n");
                                $counter++;

			}
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['Company']==""||$row['Company']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". Company\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			if($row['CopyRight']==""||$row['CopyRight']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". CopyRight\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			 if($row['USGenre']==""||$row['USGenre']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				 fwrite($fh2,"   ".$counter.". USGenre\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
	
			}
			if($row['RatingAgency']==""||$row['RatingAgency']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
        			fwrite($fh2,"   ".$counter.". RatingAgency\n");
                                $counter++;
 
	               }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
			}
			if($row['USRating']==""||$row['USRating']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". USRating\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
			
                        }
			if($row['LocalRatingAgency']==""||$row['LocalRatingAgency']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalRatingAgency\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
                        }
			if($row['LocalRating']==""||$row['LocalRating']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalRating\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['ISOLanguage']==""||$row['ISOLanguage']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". ISOLanguage\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['LocalTitle']==""||$row['LocalTitle']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";	
				fwrite($fh2,"   ".$counter.". LocalTitle\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td>";
				
                        }
			if($row['LocalSynopsis']==""||$row['LocalSynopsis']=="N/A") {
                                echo "<td bgcolor='#FE2E2E'></td>";
				fwrite($fh2,"   ".$counter.". LocalSynopsis\n");
                                $counter++;

                        }
                        else {
                                echo "<td bgcolor='#00FF00'></td></tr>";
                        }

				fwrite($fh,"<movie> \n");
                        	fwrite($fh,"<Territory>".$row['Territory']."</Territory> \n");
                        	fwrite($fh,"<ISOCountry>".$row['ISOCountry']."</ISOCountry> \n");
                        	fwrite($fh,"<PPC>".$row['PPC']."</PPC> \n");
                        	fwrite($fh,"<TitleSort>".$row['TitleSort']."</TitleSort> \n");
                        	fwrite($fh,"<ReleaseYear>".$row['ReleaseYear']."</ReleaseYear> \n");
                        	fwrite($fh,"<TitleKind>".$row['TitleKind']."</TitleKind> \n");
                        	fwrite($fh,"<TitleType>".$row['TitleType']."</TitleType> \n");
                        	fwrite($fh,"<PUI>".$row['PUI']."</PUI> \n");
                        	fwrite($fh,"<RT>".$row['RT']."</RT> \n");
                        	fwrite($fh,"<Version>".$row['Version']."</Version> \n");
                        	fwrite($fh,"<USRating>".$row['USRating']."</USRating> \n");
                        	fwrite($fh,"<USRatingReason>".$row['USRatingReason']."</USRatingReason> \n");
                        	fwrite($fh,"<LocalRating>".$row['LocalRating']."</LocalRating> \n");
                        	fwrite($fh,"<LocalRatingReason>".$row['LocalRatingReason']."</LocalRatingReason> \n");
                        	fwrite($fh,"<LocaleTitle>".$row['LocalTitle']."</LocaleTitle> \n");
                        	fwrite($fh,"<LocalSynopsis>".$row['LocalSynopsis']."</LocalSynopsis> \n");
                        	fwrite($fh,"<ShortSynopsis>".$row['ShortSynopsis']."</ShortSynopsis> \n");
                        	fwrite($fh,"<LongSynopsis>".$row['LongSynopsis']."</LongSynopsis> \n");
                        	fwrite($fh,"<Language>".$row['Language']."</Language> \n");
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
                        	fwrite($fh,"<LocalRatingAgency>".$row['LocalRatingAgency']."</LocalRatingAgency> \n");
                        	fwrite($fh,"<xmldate>".$row['xmldate']."</xmldate> \n");

                        	fwrite($fh,"</movie> \n");

			}
                	fwrite($fh,"</library>");

		
		}
		
	}
	echo "</table>";
	//echo $check;
        //echo $query;
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
if(!empty($pui)) {
	echo "<a href='http://mvf-paramount-web/web/$myFile'>Download XML Report</a>";
	echo "&nbsp;";
	echo "&nbsp;";
	echo "<a href='http://mvf-paramount-web/web/$myFile2'>Download TXT Report</a>";
}
mysql_close();
ob_end();
?>
