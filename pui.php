<?php
mysql_connect("mvf-paramount-web:3306", "root", "skunkstripe") or die(mysql_error());
mysql_select_db("paramountmdl") or die(mysql_error());

if ( !isset($_REQUEST['term']) )
	exit;

$rs=mysql_query("select PPC from titlesUpdate where PPC like '".mysql_real_escape_string($_REQUEST['term'])."%' group by PPC") or die(mysql_error());

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
	while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
	{
		$data[] = array(
			'label' => $row['PPC'] ,
			'value' => $row['PPC']
		);
	}
}
 
// jQuery wants JSON data
echo json_encode($data);
flush();

?>
