#!/usr/bin/php
<?php
mysql_connect("xml-db:3306", "root", "password123") or die(mysql_error());
mysql_select_db("project_xml") or die(mysql_error());
mysql_query("set names 'utf8'");
error_reporting(E_ERROR | E_PARSE);
foreach(glob("/mnt/dm-qc/ingest/transporter-logs/*_transport_logs/metadataFiles/*.xml") as $filename) {

	$data=simplexml_load_file($filename);
	$tmp=substr($filename,0,-4)."\n";
	$tmp1=explode("/",$tmp);
	$name=$tmp1[count($tmp1)-1];
	$query=mysql_query("SELECT id from package where file_name like '$name'") or die(mysql_error());
	if(mysql_num_rows($query)==0) {
		//echo $name."\n";
		$media_type="";
		if(substr($data['version'],0,1)=="f") {
			$media_type=substr($data['version'],0,4);
		}
		else if (substr($data['version'],0,1)=="t") {
			$media_type=substr($data['version'],0,2);
		}

		$query=mysql_query("INSERT INTO package(file_name, package_name, date, provider, language, returning, media_type, encode_house) VALUES ('$name','$data->packagename','$data->date','$data->provider','$data->language','$data->returning','$media_type','$data->encode_house')") or die(mysql_error());

		foreach($data->video as $val) {

			$p_id=mysql_query("SELECT id from package where file_name like '$name'") or die(mysql_error());
			while ($row= mysql_fetch_array( $p_id )) {
				$pack_id=$row['id'];
			}

			$title=str_replace("'","''",$val->title);
			$syn=str_replace("'","''",$val->synopsis);
			$co=str_replace("'","''",$val->copyright_cline);
			$pc=str_replace("'","''",$val->production_company);
			$next=mysql_query("INSERT INTO video(package_id,type,sub_type,vendor_id,country,original_spoken_locale,title,synopsis,production_company,copyright_cline,theatrical_release_date) VALUES ('$pack_id','$val->type','$val->subtype','$val->vendor_id','$val->country','$val->original_spoken_locale','$title','$syn','$pc','$co','$val->theatrical_release_date')") or die(mysql_error()) ;

			foreach($val->locales->locale as $loc) {
				$loc_name=$loc['name'];
				$sy=str_replace("'","''",$loc->synopsis);
				$titl=str_replace("'","''",$$loc->title);
				$next1=mysql_query("INSERT INTO locales(package_id, name, title, synopsis) VALUES ('$pack_id','$loc_name','$titl','$sy')") or die(mysql_error());
					
			}


			foreach($val->regions->region as $reg){
				$rcp=str_replace("'","''",$reg->copyright_cline);
				$next2=mysql_query("INSERT INTO regions(package_id,territory,copyright_cline,theatrical_release_date) VALUES ('$pack_id','$reg->territory','$rcp','$reg->theatrical_release_date')") or die(mysql_error());
					
			}
			foreach($val->cast->cast_member as $cast){
				if($cast->locales->locale['name'] !="") {
					foreach($cast->locales->locale as $loc) {
						$lname=$loc['name'];
						$cdn=str_replace("'","''",$cast->display_name);
						$ccn=str_replace("'","''",$cast->character_name);
						if(!empty($loc->display_name)) {
							$ldn=str_replace("'","''",$loc->display_name);
						}
						else {
							$ldn="";
						}
						if(!empty($loc->character_name)) {
							$lcn=str_replace("'","''",$loc->character_name);
						}
						else {
							$lcn="";
						}
						//echo $lcn." ".$ldn;
						$next5=mysql_query("INSERT INTO `cast`(`package_id`, `display_name`, `apple_id`, `character_name`, `locale_name`, `locale_display_name`, `locale_character_name`)
								VALUES ('$pack_id','$cdn','$cast->apple_id','$ccn','$lname','$ldn','$lcn')") or die(mysql_error());
						//echo $next5;
							
							
					}
				}
				else {
					$cdn=str_replace("'","''",$cast->display_name);
					$ccn=str_replace("'","''",$cast->character_name);
					$next5=mysql_query("INSERT INTO `cast`(`package_id`, `display_name`, `apple_id`, `character_name`,`locale_name`, `locale_display_name`, `locale_character_name`) VALUES ('$pack_id','$cdn','$cast->apple_id','$ccn','','','')") or die(mysql_error());

				}
			}
			foreach($val->genres->genre as $gen){
				$g=$gen['code'];
				$next3=mysql_query("INSERT INTO genres(package_id, genre, code) VALUES ('$pack_id','$g','$gen')") or die(mysql_error());
			}
			foreach($val->ratings->rating as $rate){
				$sys=$rate['system'];
				$reason=str_replace("'","''",$rate['reason']);
				$code=$rate['code'];
				$next4=mysql_query("INSERT INTO ratings(package_id, rating_system, rating_reason, rating_code, rating_value) VALUES ('$pack_id','$sys','$reason','$code','$rate')") or die(mysql_error());
					
			}
			foreach($val->crew->crew_member as $cre){
				$loname=$cre->locales->locale['name'];
				$dname=str_replace("'","''",$cre->locales->locale->display_name);
				$ro=$cre->roles->role;
				$dn=str_replace("'","''",$cre->display_name);
				$next=mysql_query("INSERT INTO crews(package_id, display_name, apple_id, locale_name, locale_display_name, role) VALUES ('$pack_id','$dn','$cre->apple_id','$loname','$dname','$ro')") or die(mysql_error());
					
			}
			foreach($val->chapters as $chap){
				$next=mysql_query("INSERT INTO chapters(package_id, timecode_format) VALUES ('$pack_id','$chap->timecode_format')") or die(mysql_error());
					
				$c_id=mysql_query("SELECT id FROM chapters ORDER BY id DESC LIMIT 1") or die(mysql_error());
				while ($row= mysql_fetch_array( $c_id)) {
					$chap_id=$row['id'];
				}


				foreach($chap->chapter as $val1) {

					$fname=$val1->artwork_file->file_name;
					$check=$val1->artwork_file->checksum;
					$size=$val1->artwork_file->size;
					$next=mysql_query("INSERT INTO chapter(start_time, file_name, chapter_checksum, size,chapters_id) VALUES ('$val1->start_time','$fname','$check','$size','$chap_id')") or die(mysql_error());

					$query=mysql_query("SELECT id from chapter where file_name like '$fname'") or die(mysql_error());
				 while ($row= mysql_fetch_array( $query)) {
				 	$ch_id=$row['id'];
				 }

				 foreach($val1->titles->title as $titl) {
				 	$titl1=str_replace("'","''",$titl);
				 	$next=mysql_query("INSERT into chapter_titles(title,chapter_id) VALUES ('$titl1','$ch_id')") or die(mysql_error()) ;
				 		
				 }
				 foreach($val1->attribute as $a => $b) {
				 	$next=mysql_query("INSERT into chapter_attributes(attribute,chapter_id) VALUES ('$b','$ch_id')") or die(mysql_error());
				 }
				}
			}
			foreach($val->accessibility_info->accessibility as $info) {
				$r=$info['role'];
				$ava=$info['available'];
				$next=mysql_query("INSERT INTO accessibility_info(package_id, role, available) VALUES ('$pack_id','$r','$ava')") or die(mysql_error());
					

			}
			foreach($val->assets->asset as $asse){
				if($asse['type']=="full") {
					$next=mysql_query("INSERT INTO assets(package_id,asset_type) VALUES ('$pack_id','full')") or die(mysql_error());

					$a_id=mysql_query("SELECT id FROM assets ORDER BY id DESC LIMIT 1") or die(mysql_error());

					while ($row= mysql_fetch_array( $a_id)) {
						$as_id=$row['id'];
					}

					foreach($asse->data_file as $val1) {
						$rol= $val1['role']."\n";
						$local= $val1->locale['name']."\n";

						$next=mysql_query("INSERT INTO asset(role,locale_name,file_name,size,asset_checksum,assets_id) VALUES ('$rol','$local','$val1->file_name','$val1->size','$val1->checksum','$as_id')") or die(mysql_error());
							
						foreach($val1->attribute as $a => $b) {
							$n=$b['name'];
							$next=mysql_query("INSERT into asset_attributes(assets_id,attribute,name) VALUES ('$as_id','$b','$n')") or die(mysql_error());
						}
					}
				}
				else if($asse['type']=="preview") {
					$next=mysql_query("INSERT INTO assets(package_id,asset_type) VALUES ('$pack_id','preview')") or die(mysql_error());

					$a_id=mysql_query("SELECT id FROM assets ORDER BY id DESC LIMIT 1") or die(mysql_error()) ;
					while ($row= mysql_fetch_array( $a_id)) {
						$as_id=$row['id'];
					}

					foreach($asse->data_file as $val1) {
						$r= $val1['role']."\n";
						$l= $val1->locale['name']."\n";

						$next=mysql_query("INSERT INTO asset(role,locale_name,file_name,size,asset_checksum,assets_id) VALUES ('$r','$l','$val1->file_name','$val1->size','$val1->checksum','$as_id')") or die(mysql_error());

						foreach($val1->attribute as $a => $b) {
							$n=$b['name'];
							$next=mysql_query("INSERT into asset_attributes(assets_id,attribute,name) VALUES ('$as_id','$b','$n')") or die(mysql_error());
						}
					}
					foreach($asse->territories->territory as $ter) {
						$next=mysql_query("INSERT INTO asset_territory(assets_id,territory) VALUES ('$as_id','$ter')") or die(mysql_error());
							
					}

				}
				else if($asse['type']=="artwork") {
					$next=mysql_query("INSERT INTO assets(package_id,asset_type) VALUES ('$pack_id','artwork')") or die(mysql_error());

					$a_id=mysql_query("SELECT id FROM assets ORDER BY id DESC LIMIT 1") or die(mysql_error());
					while ($row= mysql_fetch_array( $a_id)) {
						$as_id=$row['id'];
					}
					foreach($asse->territories->territory as $ter) {
						$next=mysql_query("INSERT INTO asset_territory(assets_id,territory) VALUES ('$as_id','$ter')") or die(mysql_error());
					}
					foreach($asse->data_file as $val1) {
						$r=$val1['role']."\n";
						$l= $val1->locale['name']."\n";
						$next=mysql_query("INSERT INTO asset(role,locale_name,file_name,size,asset_checksum,assets_id) VALUES ('$r','$l','$val1->file_name','$val1->size','$val1->checksum','$as_id')") or die(mysql_error());
					}

				}
			}

			foreach($val->products->product as $prod) {
				$next=mysql_query("INSERT INTO products (package_id,territory,cleared_for_sale,cleared_for_hd_sale,wholesale_price_tier,hd_wholesale_price_tier,preorder_sales_start_date,sales_start_date,sales_end_date,cleared_for_vod,vod_type,available_for_vod_date,physical_release_date,unavailable_for_vod_date,hd_physical_release_date,cleared_for_hd_vod) VALUES ('$pack_id','$prod->territory','$prod->cleared_for_sale','$prod->cleared_for_hd_sale','$prod->wholesale_price_tier','$prod->hd_wholesale_price_tier','$prod->preorder_sales_start_date','$prod->sales_start_date','$prod->sales_end_date','$prod->cleared_for_vod','$prod->vod_type','$prod->available_for_vod_date','$prod->physical_release_date','$prod->unavailable_for_vod_date','$prod->hd_physical_release_date','$prod->cleared_for_hd_vod')") or die(mysql_error()) ;

			}

		}
	}
}

mysql_close();

echo "UPDATE DONE\n";
?>
