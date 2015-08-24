#!/usr/bin/php
<?php


include('Net/SSH2.php');
$ssh = new Net_SSH2('172.25.129.3');
$ssh->login('root', 'cqxddBrm') or die("Login failed");
$cmd="/usr/local/itms/bin/iTMSTransporter -m status -u modern_dicentia@mvf.com -p 2ac172d8 -s nordiskfilmdistributionmovies -vendor_id NF_1721 -v eXtreme >> /home/kevin/output.txt";
$ssh->exec($cmd);
$ssh->exec('logout');
$ssh->disconnect();


?>
