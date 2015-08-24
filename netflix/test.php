#!/usr/bin/php

<?php

include('Net/SSH2.php');
$ssh = new Net_SSH2('glen-mover-2.mvfinc.com');
$ssh->login('root', 'nafp123') or die("Login failed");


$cmd="php /home/kevin/netflix_staging.php netflix_staging_12-18-2014-144350.txt > /dev/null &";
$ssh->exec($cmd);

$ssh->exec('logout');
$ssh->disconnect();

?>
