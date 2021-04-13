<?php
include('connect.php');

if((empty($_SESSION['UserID']) || !$_SESSION['UserID']) && !empty($_POST['Username']))
{
	if(empty($_POST['Password1']) || empty($_POST['Password2']) || empty($_POST['FirstName']) || empty($_POST['LastName']) || empty($_POST['Email']))
	{
		echo 'You are missing one or more required fields.';
	}
	else if($_POST['Password1']!=$_POST['Password2'])
	{
		echo 'Passwords did not match';
	}
	else
	{
		if(empty($_POST['Phone']))
			$_POST['Phone'] = "";

		if(mysql_query("INSERT INTO Users SET Username='" . $_POST['Username'] . "', Password='" . md5($_POST['Password1']) . "', FirstName='" . $_POST['FirstName'] . "', LastName='" . $_POST['LastName'] . "', Email='" . $_POST['Email'] . "', PhoneNumber='" . $_POST['Phone'] . "'"))
		{
			$r = mysql_query("SELECT LAST_INSERT_ID();");
			$a = mysql_fetch_array($r);
			$_SESSION['UserID'] = $a[0];
			include('MyMovies.php');
			die('');
		}
		else
		{
			echo '<font color=red>There was an error inserting the data</font>';
		}
	}
}
?>

<form action="SignUp.php" method=POST>
<TABLE>
<TR><TD>Username *</TD><TD><input type=text name="Username" value="<?php if(!empty($_POST['Username'])) { echo $_POST['Username']; }?>"></TD></TR>
<TR><TD>Password *</TD><TD><input type=password name="Password1"></TD></TR>
<TR><TD>Repeat Password *</TD><TD><input type=password name="Password2"></TD></TR>
<TR><TD>First Name *</TD><TD><input type=text name="FirstName" value="<?php if(!empty($_POST['FirstName'])) { echo $_POST['FirstName']; }?>"></TD></TR>
<TR><TD>Last Name *</TD><TD><input type=text name="LastName" value="<?php if(!empty($_POST['LastName'])) { echo $_POST['LastName']; }?>"></TD></TR>
<TR><TD>Email *</TD><TD><input type=text name="Email" value="<?php if(!empty($_POST['Email'])) { echo $_POST['Email']; }?>"></TD></TR>
<TR><TD>Phone</TD><TD><input type=text name="Phone" value="<?php if(!empty($_POST['Phone'])) { echo $_POST['PhoneNumber']; }?>"></TD></TR>
<TR><TD></TD><TD><input type=submit value="Create My Account"></TD></TR>
</TABLE>
</form>
