<?php
include('connect.php');

if(!empty($_POST['Username']) && !empty($_POST['Password']))
{
	if($r = mysql_query("SELECT id FROM Users WHERE Username='" . $_POST['Username'] . "' AND Password='" . md5($_POST['Password']) . "'"))
	{
		if(mysql_num_rows($r))
		{
			$row = mysql_fetch_array($r);
			$_SESSION['UserID'] = $row['id'];
		}
		else
		{
			echo '<font color=red>Username and Password match not found.</font>';
		}
	}
}

if(!empty($_SESSION['UserID']) && $_SESSION['UserID'])
{
	include('MyMovies.php');
	die('');
}

?>

<form action="index.php" method=POST>
<TABLE><TR><TD>Username</TD><TD><input type=text name="Username" value="<?php if(!empty($_POST['Username'])) { echo $_POST['Username']; }?>"></TD></TR>
<TR><TD>Password</TD><TD><input type=password name="Password"></TD></TR>
<TR><TD></TD><TD><input type=submit value="Login"></TR></TABLE>
</form>
<a href="SignUp.php">Sign-Up</a>