#!/usr/bin/php

<?php
$ldaphost = "172.25.216.1";
$ldapport = 389;

$ds = ldap_connect($ldaphost,$ldapport)
 or die("Could not connect to $ldaphost");


$username = "kdiep";
 $upasswd = "Hellfire#1982";

 $binddn = "$username@modern.mvfinc.com";
$ldapbind = ldap_bind($ds, $binddn, $upasswd);


if($ldapbind) {

        $result = ldap_search($ds, "OU=Policy Groups,DC=modern,DC=mvfinc,DC=com","(samaccountname=$username)");
	$entry = ldap_first_entry($ds, $result);

        $attrs = ldap_get_attributes($ds, $entry);
        for ($i=0; $i < $attrs["memberOf"]["count"]; $i++) {
		//echo $attrs["memberOf"][$i]."\n";
                $testStr=explode(",",$attrs["memberOf"][$i]);
		if($testStr[0]=="CN=netflix-wf-admin") {
			echo "yes";
		}

        }

}

?>
