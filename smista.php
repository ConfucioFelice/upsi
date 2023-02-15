<?php
$ldapDomain = "@unior.it"; 			
$ldapHost = "ldap://193.205.137.2"; 
$ldapPort = "389"; 					
$ldapUser  = "administrator"; 		
$ldapPassword = "resame"; 			
$successMessage = "";
$errorMessage = "";

$ldapConnection = ldap_connect($ldapHost, $ldapPort) 
	or die("Impossibile connettersi al server LDAP.");

if (isset($_POST["ldapLogin"])){

	if ($ldapConnection) {
		
		if (isset($_POST["user"]) && $_POST["user"] != "") 
			$ldapUser = addslashes(trim($_POST["user"]));
		else 
			$errorMessage = "Username errato.";
		
		if (isset($_POST["password"]) && $_POST["password"] != "") 
			$ldapPassword = addslashes(trim($_POST["password"]));
		else 
			$errorMessage = "Password errata.";
		
		if ($errorMessage == ""){
			// binding to ldap server
			ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Impossibile stabilire la versione di LDAP.');
			ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
			$ldapbind = @ldap_bind($ldapConnection, $ldapUser . $ldapDomain, $ldapPassword);

			// verify binding
			if ($ldapbind){
				ldap_close($ldapConnection);	// close ldap connection
				session_start();
				$_SESSION['username']=$ldapUser;
				header("location: redirectafterlogin.php?user=".$ldapUser);
				exit;
			} 
			else 
				$errorMessage = "Credenziali invalide.";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zeyada&display=swap" rel="stylesheet">
    <title>Smistamento</title>
    <style>
        body {
            background-color: #f0f0f2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 100px 0 0 0;
            font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .container{
            width: 800px;
            height: 300px;
            margin: 5rem;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            background-color: #fdfdff;
            border-radius: 0.5em;
            box-shadow: 2px 3px 7px 2px rgba(0,0,0,0.02);
        }
        .card:hover{
            box-shadow: 2px 3px 7px 2px rgba(246, 73, 73, 0.883);
        }
        .card{
            background-color: rgb(120, 230, 230);
            width: 300px;
            height: 200px;
            margin: 5em auto;
            padding: 2em;
            border-radius: 0.5em;
            box-shadow: 2px 3px 7px 2px rgba(0,0,0,0.02);
            justify-content: center;
            align-items: center;
            flex-direction: column;
            display: flex;
        }
        #footer {
            width: 800px;
            height: 300px;
        }
        .card p{
            font-size: 20px;
        }
        span{
            font-weight: bold;
            font-family: 'Zeyada', cursive;
            font-size: 30px;
        }
    </style>
</head>
<body>
    <?php		
			if ($errorMessage != "") echo "<h3 style='color:red;'>$errorMessage</h3>";
			if ($successMessage != "") echo "<h3 style='color:blue;'>$successMessage</h3>";
		?>  
<div class="form">
        <form action="smista.php" method="post" style="display:inline-block;">
			<table style="display:inline-block;">
				<tr>
					<td>User</td>
					<td><input type="text" name="user" value="" maxlength="50"></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="password" value="" maxlength="50"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="ldapLogin" value="Entra"></td>
				</tr>
			</table>
		    </form>
        </div>
    <div class="container">
        
        <div class="card">
            <p>Demo Intranet con <span>Wordpress</span></p>
        </div>
        <div class="card">
            <p>Demo Intranet in <span>PHP</span></p>
        </div>
        <hr>

    </div>

    <hr>
    <p id="footer"><span>ConfucioFelice</span> created 🐼 - 2023 - <a href="#">GITHUB.</a></p>
</body>
</html>