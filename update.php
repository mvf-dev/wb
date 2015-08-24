#!/usr/bin/php
<?php
mysql_connect ( "172.25.209.23:3306", "root", "skunkstripe" ) or die ( mysql_error () );
mysql_select_db ( "paramountmdl" ) or die ( mysql_error () );
function replace_file($path, $string, $replace) {
	set_time_limit ( 0 );
	
	if (is_file ( $path ) === true) {
		$file = fopen ( $path, 'r' );
		$temp = tempnam ( './', 'tmp' );
		
		if (is_resource ( $file ) === true) {
			while ( feof ( $file ) === false ) {
				file_put_contents ( $temp, str_replace ( $string, $replace, fgets ( $file ) ), FILE_APPEND );
			}
			
			fclose ( $file );
		}
		
		unlink ( $path );
	}
	
	return rename ( $temp, $path );
}

foreach ( glob ( "*xml" ) as $filename ) {
	replace_file ( $filename, 'mstns:', '' );
	replace_file ( $filename, '<?xml version="1.0" encoding="UTF-8" standalone="no"?>', '' );
	replace_file ( $filename, '<NewDataSet xmlns:mstns="AscentMetadataReport" xsi:schemaLocation="AscentMetadataReport tgt_crm_ascent_titleMetadata_xml_file_v1.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">', '' );
	replace_file ( $filename, '</NewDataSet>', '' );
}

$myFile = "log.txt";
$fh = fopen ( $myFile, 'w' ) or die ( "can't open file" );

date_default_timezone_set ( 'America/Los_Angeles' );
$date = date ( 'Ymd', time () );

foreach ( glob ( "*.xml" ) as $filename ) {
	$data = simplexml_load_file ( $filename );
	$str = explode ( '_', $filename );
	$rep = explode ( '.', $str [4] );
	$up = 0;
	$in = 0;
	$xmldate = substr ( $rep [0], 0, 8 );
	echo $xmldate;
	if ($date == $xmldate) {
		foreach ( $data->Metadata->Detail_Collection->Detail as $entry ) {
			// $LocalRatingAgency=str_replace("'","''",$entry->LocalRatingAgency);
			$RatingAgency = str_replace ( "'", "''", $entry->RatingAgency );
			$DTORightsTerritory = str_replace ( "'", "''", $entry->DTORightsTerritory );
			$DTORightsFinal = str_replace ( "'", "''", $entry->DTORightsFinal );
			$DTORights = str_replace ( "'", "''", $entry->DTORights );
			$CopyRight = str_replace ( "'", "''", $entry->CopyRight );
			$MusicClear = str_replace ( "'", "''", $entry->MusicClear );
			$BoxOffice = str_replace ( "'", "''", $entry->BoxOffice );
			$FVODEnd = str_replace ( "'", "''", $entry->FVODEnd );
			$FVODStart = str_replace ( "'", "''", $entry->FVODStart );
			$FVODType = str_replace ( "'", "''", $entry->FVODType );
			$SVODEnd = str_replace ( "'", "''", $entry->SVODEnd );
			$SVODStart = str_replace ( "'", "''", $entry->SVODStart );
			$SVODType = str_replace ( "'", "''", $entry->SVODType );
			$VODEnd = str_replace ( "'", "''", $entry->VODEnd );
			$VODStart = str_replace ( "'", "''", $entry->VODStart );
			$VODType = str_replace ( "'", "''", $entry->VODType );
			$Writers = str_replace ( "'", "''", $entry->Writers );
			$Producers = str_replace ( "'", "''", $entry->Producers );
			$Directors = str_replace ( "'", "''", $entry->Directors );
			$Composers = str_replace ( "'", "''", $entry->Composers );
			$Actors = str_replace ( "'", "''", $entry->Actors );
			$AppleTier = str_replace ( "'", "''", $entry->AppleTier );
			$DTOPrice = str_replace ( "'", "''", $entry->DTOPrice );
			$DTOSolicitationDate = str_replace ( "'", "''", $entry->DTOSolicitationDate );
			$DTOStart = str_replace ( "'", "''", $entry->DTOStart );
			$LocalGenre = str_replace ( "'", "''", $entry->LocalGenre );
			$USGenre = str_replace ( "'", "''", $entry->USGenre );
			$Company = str_replace ( "'", "''", $entry->Company );
			$ISOLanguage = str_replace ( "'", "''", $entry->ISOLanguage );
			$Language = str_replace ( "'", "''", $entry->Language );
			$LongSynopsis = str_replace ( "'", "''", $entry->LongSynopsis );
			$ShortSynopsis = str_replace ( "'", "''", $entry->ShortSynopsis );
			$LocalSynopsis = str_replace ( "'", "''", $entry->LocalSynopsis );
			$LocalTitle = str_replace ( "'", "''", $entry->LocalTitle );
			$LocalRatingReason = str_replace ( "'", "''", $entry->LocalRatingReason );
			$LocalRating = str_replace ( "'", "''", $entry->LocalRating );
			$USRatingReason = str_replace ( "'", "''", $entry->USRatingReason );
			$USRating = str_replace ( "'", "''", $entry->USRating );
			$Version = str_replace ( "'", "''", $entry->Version );
			$RT = str_replace ( "'", "''", $entry->RT );
			$PUI = str_replace ( "'", "''", $entry->PUI );
			$TitleType = str_replace ( "'", "''", $entry->TitleType );
			$TitleKind = str_replace ( "'", "''", $entry->TitleKind );
			$ReleaseYear = str_replace ( "'", "''", $entry->ReleaseYear );
			$TitleSort = str_replace ( "'", "''", $entry->TitleSort );
			$PPC = str_replace ( "'", "''", $entry->PPC );
			$ISOCountry = str_replace ( "'", "''", $entry->ISOCountry );
			$Territory = str_replace ( "'", "''", $entry->Territory );
			$AppleAgencyCode = str_replace ( "'", "''", $entry->AppleAgencyCode );
			$LocalReleaseDate = str_replace ( "'", "''", $entry->LocalReleaseDate );
			$ProductionCompany = str_replace ( "'", "''", $entry->ProductionCompany );
			$LocalActors = str_replace ( "'", "''", $entry->LocalActors );
			$LocalDirectors = str_replace ( "'", "''", $entry->LocalDirectors );
			$LocalProducers = str_replace ( "'", "''", $entry->LocalProducers );
			$LocalWriters = str_replace ( "'", "''", $entry->LocalWriters );
			$LocalComposers = str_replace ( "'", "''", $entry->LocalComposers );
			$LocalRatingAgencyName = str_replace ( "'", "''", $entry->LocalRatingAgencyName );
			$TheatricalReleaseFlag = str_replace ( "'", "''", $entry->TheatricalReleaseFlag );
			$PhysicalReleaseDate = str_replace ( "'", "''", $entry->PhysicalReleaseDate );
			$PhysicalReleaseFlag = str_replace ( "'", "''", $entry->PhysicalReleaseFlag );
			$LocalPhysicalReleaseFlag = str_replace ( "'", "''", $entry->LocalPhysicalReleaseFlag );
			$LocalTheatricalReleaseFlag = str_replace ( "'", "''", $entry->LocalTheatricalReleaseFlag );
			$LocalPhysicalReleaseDate = str_replace ( "'", "''", $entry->LocalPhysicalReleaseDate );
			$DigitalReleaseDate = str_replace ( "'", "''", $entry->DigitalReleaseDate );
			$DigitalReleaseFlag = str_replace ( "'", "''", $entry->DigitalReleaseFlag );
			$LocalDigitalReleaseDate = str_replace ( "'", "''", $entry->LocalDigitalReleaseDate );
			$LocalDigitalReleaseFlag = str_replace ( "'", "''", $entry->LocalDigitalReleaseFlag );
			$LocalRatingStatus = str_replace ( "'", "''", $entry->LocalRatingStatus );
			
			$xmldate = substr ( $rep [0], 0, 8 );
			
			$query = mysql_query ( "SELECT id FROM titlesUpdate where PPC like '$PPC' AND Territory like '$Territory' AND PUI like '$PUI' AND Language like '$Language'" ) or die ( mysql_error () );
			if (mysql_num_rows ( $query ) > 0) {
				$str = mysql_query ( "UPDATE titlesUpdate SET RatingAgency='$RatingAgency', DTORightsTerritory='$DTORightsTerritory', DTORightsFinal='$DTORightsFinal', DTORights='$DTORights', 
						CopyRight='$CopyRight',MusicClear='$MusicClear', BoxOffice='$BoxOffice', FVODEnd='$FVODEnd', FVODStart='$FVODStart', FVODType='$FVODType', SVODEnd='$SVODEnd', SVODStart='$SVODStart', 
						SVODType='$SVODType', VODEnd='$VODEnd', VODStart='$VODStart', VODType='$VODType', Writers='$Writers', Producers='$Producers', Directors='$Directors', Composers='$Composers', Actors='$Actors', 
						AppleTier='$AppleTier', DTOPrice='$DTOPrice', DTOSolicitationDate='$DTOSolicitationDate', DTOStart='$DTOStart', LocalGenre='$LocalGenre', USGenre='$USGenre', Company='$Company', 
						ISOLanguage='$ISOLanguage', Language='$Language', LongSynopsis='$LongSynopsis', ShortSynopsis='$ShortSynopsis', LocalSynopsis='$LocalSynopsis', LocalTitle='$LocalTitle', 
						LocalRatingReason='$LocalRatingReason', LocalRating='$LocalRating', USRatingReason='$USRatingReason', USRating='$USRating', Version='$Version', RT='$RT', TitleType='$TitleType', 
						TitleKind='$TitleKind', ReleaseYear='$ReleaseYear', TitleSort='$TitleSort', ISOCountry='$ISOCountry', AppleAgencyCode='$AppleAgencyCode', LocalReleaseDate='$LocalReleaseDate', 
						ProductionCompany='$ProductionCompany',LocalActors='$LocalActors', LocalDirectors='$LocalDirectors', LocalProducers='$LocalProducers', LocalWriters='$LocalWriters', LocalComposers='$LocalComposers', 
						LocalRatingAgencyName='$LocalRatingAgencyName',TheatricalReleaseFlag='$TheatricalReleaseFlag',PhysicalReleaseDate='$PhysicalReleaseDate',PhysicalReleaseFlag='$PhysicalReleaseFlag',
						LocalPhysicalReleaseFlag='$LocalPhysicalReleaseFlag',LocalTheatricalReleaseFlag='$LocalTheatricalReleaseFlag',LocalPhysicalReleaseDate='$LocalPhysicalReleaseDate',
						DigitalReleaseDate='$DigitalReleaseDate',DigitalReleaseFlag='$DigitalReleaseFlag',LocalDigitalReleaseDate='$LocalDigitalReleaseDate',LocalDigitalReleaseFlag='$LocalDigitalReleaseFlag',
						LocalRatingStatus='$LocalRatingStatus',xmldate='$xmldate' where PPC like '$PPC' AND Territory like '$Territory' AND PUI like '$PUI' AND Language like '$Language'" ) or die ( mysql_error () );
				$up ++;
			} else {
				$master = mysql_query ( "INSERT INTO titlesUpdate(Territory,ISOCountry,PPC,TitleSort,ReleaseYear,TitleKind,TitleType,PUI,RT,Version,USRating,USRatingReason,LocalRating,LocalRatingReason,LocalTitle,LocalSynopsis,
						ShortSynopsis,LongSynopsis,Language,ISOLanguage,Company,USGenre,LocalGenre,DTOStart,DTOSolicitationDate,DTOPrice,AppleTier,Actors,Composers,Directors,Producers,Writers,VODType,VODStart,VODEnd,
						SVODType,SVODStart,SVODEnd,FVODType,FVODStart,FVODEnd,BoxOffice,MusicClear,CopyRight,DTORights,DTORightsFinal,DTORightsTerritory,RatingAgency,AppleAgencyCode,LocalReleaseDate,ProductionCompany,
						LocalActors,LocalDirectors,LocalProducers,LocalWriters,LocalComposers,LocalRatingAgencyName,TheatricalReleaseFlag,PhysicalReleaseDate,PhysicalReleaseFlag,LocalPhysicalReleaseFlag,
						LocalTheatricalReleaseFlag,LocalPhysicalReleaseDate,DigitalReleaseDate, DigitalReleaseFlag,LocalDigitalReleaseDate,LocalDigitalReleaseFlag,LocalRatingStatus,xmldate)
						VALUES ('$Territory','$ISOCountry','$PPC','$TitleSort','$ReleaseYear','$TitleKind','$TitleType','$PUI','$RT','$Version','$USRating','$USRatingReason',
						'$LocalRating','$LocalRatingReason','$LocalTitle','$LocalSynopsis','$ShortSynopsis','$LongSynopsis','$Language','$ISOLanguage','$Company','$USGenre','$LocalGenre','$DTOStart','$DTOSolicitationDate',
						'$DTOPrice','$AppleTier','$Actors','$Composers','$Directors','$Producers','$Writers','$VODType','$VODStart','$VODEnd','$SVODType','$SVODStart','$SVODEnd','$FVODType','$FVODStart','$FVODEnd','$BoxOffice','$MusicClear',
						'$CopyRight','$DTORights','$DTORightsFinal','$DTORightsTerritory','$RatingAgency','$AppleAgencyCode','$LocalReleaseDate','$ProductionCompany','$LocalActors','$LocalDirectors','$LocalProducers','$LocalWriters','$LocalComposers',
						'$LocalRatingAgencyName','$TheatricalReleaseFlag','$PhysicalReleaseDate','$PhysicalReleaseFlag','$LocalPhysicalReleaseFlag','$LocalTheatricalReleaseFlag','$LocalPhysicalReleaseDate', 
						'$DigitalReleaseDate','$DigitalReleaseFlag','$LocalDigitalReleaseDate','$LocalDigitalReleaseFlag','$LocalRatingStatus','$xmldate')" ) or die ( mysql_error () );
				$in ++;
			}
		}
		// fwrite($fh, "For ".$filename."\n".$up." records got updated and ".$in." record got inserted \n");
	}
	// echo "UPDATE SUCCESSFUL\n";
	fclose ( $fh );
	mysql_close ();
	$output = shell_exec ( 'rm -f *.xml' );
}
?>


