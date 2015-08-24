<?php
session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Netflix Work Flow Login</title>
<meta charset="UTF-8" />

<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/structure.css">
</head>

<body>

<form class="box login" method="post" action="<?php echo $PHP_SELF; ?>">

	<fieldset class="boxBody">
	  <label>Username</label>
	  <input type="text" tabindex="1" name="username" id="username" placeholder="username" required>
	  <label>Password</label>
	  <input type="password" tabindex="2" name="password" id="password" placeholder="password" required>
	</fieldset>
	<footer>
	  
	  <input type="submit" name="submit" class="btnLogin" value="Login" tabindex="4">
	</footer>
</form>

</body>
</html>
<?php
if(isset($_POST['submit'])) {
	$ldaphost = "172.25.216.1";
	$ldapport = 389;

	$ds = ldap_connect($ldaphost,$ldapport) or die("Could not connect to $ldaphost");


	$username = $_POST['username'];
 	$upasswd = $_POST['password'];
 	$binddn = "$username@modern.mvfinc.com";
	$ldapbind = ldap_bind($ds, $binddn, $upasswd);


	if($ldapbind) {

        	$result = ldap_search($ds, "OU=Policy Groups,DC=modern,DC=mvfinc,DC=com","(samaccountname=$username)");
        	$entry = ldap_first_entry($ds, $result);

        	$attrs = ldap_get_attributes($ds, $entry);
        	for ($i=0; $i < $attrs["memberOf"]["count"]; $i++) {
                	$testStr=explode(",",$attrs["memberOf"][$i]);
 	               if($testStr[0]=="CN=netflix-wf-admin") {
				$_SESSION['group']="admin";
				$_SESSION['start']=time();
				$_SESSION['expire']=$_SESSION['start']+(30*60);
                	        header( 'Location: job.php' ) ;
                	}
			else if($testStr[0]=="CN=netflix-wf-user") {
				$_SESSION['group']="user";
				$_SESSION['start']=time();
                                $_SESSION['expire']=$_SESSION['start']+(30*60);

				header( 'Location: job.php' ) ;
			}

        	}

	}
	else {
                echo "<h2 align='center'>Please enter a valid username and password</h2>";
           }

}
?>

