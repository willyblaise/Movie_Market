<?php
include_once('connect.php');

if(empty($_SESSION['UserID']) || !$_SESSION['UserID'])
	die('<meta http-equiv="refresh" content="0;url=index.php">');


if(!empty($_GET['AcceptUser']))
{
	mysql_query("DELETE FROM Requests WHERE RequesterID=" . $_GET['AcceptUser'] . " AND RequesteeID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['AcceptMovie']);
	mysql_query("INSERT INTO Accepts SET RequesterID=" . $_GET['AcceptUser'] . ", RequesteeID=" . $_SESSION['UserID'] . ", MovieID=" . $_GET['AcceptMovie']);
}
if(!empty($_GET['DeclineUser']))
{
	mysql_query("DELETE FROM Requests WHERE RequesterID=" . $_GET['DeclineUser'] . " AND RequesteeID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['DeclineMovie']);
	mysql_query("INSERT INTO Denials SET RequesterID=" . $_GET['DeclineUser'] . ", RequesteeID=" . $_SESSION['UserID'] . ", MovieID=" . $_GET['DeclineMovie']);
}
if(!empty($_GET['DelRequestUser']))
{
	mysql_query("DELETE FROM Requests WHERE RequesteeID=" . $_GET['DelRequestUser'] . " AND RequesterID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['DelRequestMovie']);
}
if(!empty($_GET['DelDeclineUser']))
{
	mysql_query("DELETE FROM Denials WHERE RequesteeID=" . $_GET['DelDeclineUser'] . " AND RequesterID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['DelDeclineMovie']);
}
if(!empty($_GET['Complete1User']))
{
	mysql_query("DELETE FROM Accepts WHERE RequesteeID=" . $_GET['Complete1User'] . " AND RequesterID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['Complete1Movie']);
	mysql_query("DELETE FROM MoviesUsers WHERE UserID=" . $_GET['Complete1User'] . " AND MovieID=" . $_GET['Complete1Movie']);
	mysql_query("INSERT INTO MoviesUsers SET UserID=" . $_SESSION['UserID'] . ", MovieID=" . $_GET['Complete1Movie']);
}
if(!empty($_GET['Complete2User']))
{
	mysql_query("DELETE FROM Accepts WHERE RequesterID=" . $_GET['Complete2User'] . " AND RequesteeID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['Complete2Movie']);
	mysql_query("DELETE FROM MoviesUsers WHERE UserID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['Complete2Movie']);
	mysql_query("INSERT INTO MoviesUsers SET UserID=" . $_GET['Complete2User'] . ", MovieID=" . $_GET['Complete2Movie']);
}
if(!empty($_GET['Cancel1User']))
{
	mysql_query("DELETE FROM Accepts WHERE RequesteeID=" . $_GET['Cancel1User'] . " AND RequesterID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['Cancel1Movie']);
}
if(!empty($_GET['Cancel2User']))
{
	mysql_query("DELETE FROM Accepts WHERE RequesterID=" . $_GET['Cancel2User'] . " AND RequesteeID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['Cancel2Movie']);
	mysql_query("INSERT INTO Denials SET RequesterID=" . $_GET['Cancel2User'] . ", RequesteeID=" . $_SESSION['UserID'] . ", MovieID=" . $_GET['Cancel2Movie']);
}




include('nav.php');



if(($r = mysql_query("SELECT Movies.id AS MovieID, Movies.Title, Movies.Image, Users.FirstName, Users.LastName, Users.id AS UserID FROM Users,Movies,Requests WHERE Requests.RequesterID=Users.id AND Movies.id=Requests.MovieID AND Requests.RequesteeID=" . $_SESSION['UserID'])) && mysql_num_rows($r))
{
	echo '<font size=5>Incoming Requests:</font> <TABLE cellpadding=3><TR bgcolor=#DDDDDD><TD width=150>Movie</TD><TD>Requester</TD><TD>&nbsp;</TD><TD>&nbsp</TD></TR>';
	while($row = mysql_fetch_array($r))
	{
		echo '<TR><TD align=center><img src="' . $row['Image'] . '" width=150><BR>' . $row['Title'] . '</TD><TD>' . $row['LastName'] . ', ' . $row['FirstName'] . '</TD><TD><input type=button value="Accept" onClick="window.location.href=\'Requests.php?AcceptUser=' . $row['UserID'] .  '&AcceptMovie=' . $row['MovieID'] . '\'"></TD><TD><input type=button value="Decline" onClick="window.location.href=\'Requests.php?DeclineUser=' . $row['UserID'] .  '&DeclineMovie=' . $row['MovieID'] . '\'"></TD></TR>';
	}
	echo '</TABLE>';
}
echo '<BR><BR>';
if(($r = mysql_query("SELECT Movies.id AS MovieID, Movies.Title, Movies.Image, Users.FirstName, Users.LastName, Users.id AS UserID FROM Users,Movies,Requests WHERE Requests.RequesteeID=Users.id AND Movies.id=Requests.MovieID AND Requests.RequesterID=" . $_SESSION['UserID'])) && mysql_num_rows($r))
{
	echo '<font size=5>Outgoing Requests:</font> <TABLE cellpadding=3><TR bgcolor=#DDDDDD><TD width=150>Movie</TD><TD>Owner</TD><TD>&nbsp</TD></TR>';
	while($row = mysql_fetch_array($r))
	{
		echo '<TR><TD align=center><img src="' . $row['Image'] . '" width=150><BR>' . $row['Title'] . '</TD><TD>' . $row['LastName'] . ', ' . $row['FirstName'] . '</TD><TD><input type=button value="Delete" onClick="window.location.href=\'Requests.php?DelRequestUser=' . $row['UserID'] .  '&DelRequestMovie=' . $row['MovieID'] . '\'"></TD></TR>';
	}
	echo '</TABLE>';
}
echo '<BR><BR>';
if(($r = mysql_query("SELECT Movies.id AS MovieID, Movies.Title, Movies.Image, Users.FirstName, Users.LastName, Users.id AS UserID FROM Users,Movies,Denials WHERE Denials.RequesteeID=Users.id AND Movies.id=Denials.MovieID AND Denials.RequesterID=" . $_SESSION['UserID'])) && mysql_num_rows($r))
{
	echo '<font size=5>You got Declined:</font> <TABLE cellpadding=3><TR bgcolor=#DDDDDD><TD width=150>Movie</TD><TD>Requester</TD><TD>&nbsp</TD></TR>';
	while($row = mysql_fetch_array($r))
	{
		echo '<TR><TD align=center><img src="' . $row['Image'] . '" width=150><BR>' . $row['Title'] . '</TD><TD>' . $row['LastName'] . ', ' . $row['FirstName'] . '</TD><TD><input type=button value="Remove" onClick="window.location.href=\'Requests.php?DelDeclineUser=' . $row['UserID'] .  '&DelDeclineMovie=' . $row['MovieID'] . '\'"></TD></TR>';
	}
	echo '</TABLE>';
}
echo '<BR><BR>';
if(($r = mysql_query("SELECT Movies.id AS MovieID, Movies.Title, Movies.Image, Users.FirstName, Users.LastName, Users.id AS UserID, Users.PhoneNumber, Users.Email FROM Users,Movies,Accepts WHERE Accepts.RequesteeID=Users.id AND Movies.id=Accepts.MovieID AND Accepts.RequesterID=" . $_SESSION['UserID'])) && mysql_num_rows($r))
{
	echo '<font size=5>You need to Pickup:</font> <TABLE cellpadding=3><TR bgcolor=#DDDDDD><TD width=150>Movie</TD><TD>Current Owner</TD><TD>Phone Number</TD><TD>Email</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>';
	while($row = mysql_fetch_array($r))
	{
		echo '<TR><TD align=center><img src="' . $row['Image'] . '" width=150><BR>' . $row['Title'] . '</TD><TD>' . $row['LastName'] . ', ' . $row['FirstName'] . '</TD><TD>' . $row['PhoneNumber'] . '</TD><TD>' . $row['Email'] . '</TD><TD><input type=button value="Complete" onClick="window.location.href=\'Requests.php?Complete1User=' . $row['UserID'] .  '&Complete1Movie=' . $row['MovieID'] . '\'"></TD><TD><input type=button value="Cancel" onClick="window.location.href=\'Requests.php?Cancel1User=' . $row['UserID'] .  '&Cancel1Movie=' . $row['MovieID'] . '\'"></TD></TR>';
	}
	echo '</TABLE>';
}
echo '<BR><BR>';
if(($r = mysql_query("SELECT Movies.id AS MovieID, Movies.Title, Movies.Image, Users.FirstName, Users.LastName, Users.id AS UserID, Users.PhoneNumber, Users.Email FROM Users,Movies,Accepts WHERE Accepts.RequesterID=Users.id AND Movies.id=Accepts.MovieID AND Accepts.RequesteeID=" . $_SESSION['UserID'])) && mysql_num_rows($r))
{
	echo '<font size=5>Waiting on Pickup:</font> <TABLE cellpadding=3><TR bgcolor=#DDDDDD><TD width=150>Movie</TD><TD>Future Owner</TD><TD>Phone Number</TD><TD>Email</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>';
	while($row = mysql_fetch_array($r))
	{
		echo '<TR><TD align=center><img src="' . $row['Image'] . '" width=150><BR>' . $row['Title'] . '</TD><TD>' . $row['LastName'] . ', ' . $row['FirstName'] . '</TD><TD>' . $row['PhoneNumber'] . '</TD><TD>' . $row['Email'] . '</TD><TD><input type=button value="Complete" onClick="window.location.href=\'Requests.php?Complete2User=' . $row['UserID'] .  '&Complete2Movie=' . $row['MovieID'] . '\'"></TD><TD><input type=button value="Cancel" onClick="window.location.href=\'Requests.php?Cancel2User=' . $row['UserID'] .  '&Cancel2Movie=' . $row['MovieID'] . '\'"></TD></TR>';
	}
	echo '</TABLE>';
}

?>