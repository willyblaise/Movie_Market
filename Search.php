<?php
include('connect.php');
include('nav.php');


if(empty($_SESSION['UserID']) || !$_SESSION['UserID'])
	die('<meta http-equiv="refresh" content="0;url=index.php">');


if(!empty($_GET['RequestUser']) && !empty($_GET['RequestMovie']))
{
	if($_GET['RequestUser']==$_SESSION['UserID'])
		echo '<font color=red>You cannot request a movie from yourself.</font>';
	else if(!mysql_query("INSERT INTO Requests SET RequesterID=" . $_SESSION['UserID'] . ", RequesteeID=" . $_GET['RequestUser'] . ", MovieID=" . $_GET['RequestMovie']))
	{
		echo '<font color=red>You already have a request for that Movie from that User</font>';
	}
	else
	{
		echo 'That user has been informed of your request.';
	}
}


if(!empty($_GET['DirectorID']))
{
	$_POST['Button']=1;
	$_POST['DirectorID'] = $_GET['DirectorID'];
}
if(!empty($_GET['UserID']))
{
	$_POST['Button']=1;
	$_POST['UserID'] = $_GET['UserID'];
}

if(!empty($_POST['Button']))
{
	$WhereClause = "";
	if(!empty($_POST['Title']))
		$WhereClause = " AND Title='" . $_POST['Title'] . "'";
	if(!empty($_POST['Year']))
		$WhereClause .= " AND Year='" . $_POST['Year'] . "'";
	if(!empty($_POST['Genre']) && $_POST['Genre'])
		$WhereClause .= " AND Genre.id=" . $_POST['Genre'];
	if(!empty($_POST['DirectorID']) && $_POST['DirectorID'])
		$WhereClause .= " AND Directors.id=" . $_POST['DirectorID'];
	if(!empty($_POST['UserID']) && $_POST['UserID'])
		$WhereClause .= " AND Users.id=" . $_POST['UserID'];

	if(empty($_POST['OrderBy']))
		$_POST['OrderBy'] = "Title";

	$query = "SELECT Movies.Image, Genres.Genre, Users.id AS UserID, Users.FirstName, Users.LastName, Movies.id AS MovieID, Movies.Title, Movies.Year, Directors.Director FROM Users, Movies, Directors, Genres, MoviesUsers WHERE MoviesUsers.MovieID=Movies.id AND Movies.DirectorID=Directors.id AND MoviesUsers.UserID=Users.id AND Movies.GenreID=Genres.id AND Users.id<>" . $_SESSION['UserID'] . " AND MoviesUsers.tradeable=1 " . $WhereClause . " ORDER BY " . $_POST['OrderBy'];
	if($r = mysql_query($query))
	{
		echo '<TABLE><TR bgcolor=#DDDDDD><TD>Image</TD><TD>Title</TD><TD>Genre</TD><TD>Year</TD><TD>Director</TD><TD>Owner</TD><TD>&nbsp;</TD></TR>';
		while($row = mysql_fetch_array($r))
		{
			echo '<TR><TD><a href="' . $row['Image'] . '"><img border=0 src="' . $row['Image'] . '" width=250></a></TD>
			<TD>' . $row['Title'] . '</TD><TD>' . $row['Genre'] . '</TD><TD>' . $row['Year'] . '</TD><TD>' . $row['Director'] . '</TD><TD><a href="Search.php?UserID=' . $row['UserID'] . '">' . $row['LastName'] . ', ' . $row['FirstName'] . '</a></TD><TD><input type=button value="Request" onClick="window.location.href=\'Search.php?RequestMovie=' . $row['MovieID'] . '&RequestUser=' . $row['UserID'] . '\'"></TR>';
		}
		echo '</TABLE>';
	}

}

?>

<BR>
<BR>
<form action="Search.php" method=POST>
<TABLE>
<TR><TD colspan=2 align=center bgcolor=#EEEEDD>Search</TD></TR>
<TR bgcolor=#DDDDDD><TD>Title</TD><TD><input type=text name="Title" value="<?php if(!empty($_POST['Title'])) { echo $_POST['Title']; }?>"></TD></TR>
<TR bgcolor=#EEEEEE><TD>Year</TD><TD><input type=text name="Year" size=4 value="<?php if(!empty($_POST['Year'])) { echo $_POST['Year']; }?>"></TD></TR>
<TR bgcolor=#DDDDDD><TD>Genre</TD><TD>
<select name="Genre">
<option value=0>All</option>
<?php
if($r = mysql_query("SELECT id, Genre FROM Genres ORDER BY Genre"))
{
	while($row = mysql_fetch_array($r))
	{
		$selected=false;
		if(!empty($_POST['Genre']) && $_POST['Genre']==$row['id'])
			$selected=true;
		echo '<option value=' . $row['id'] . ($selected ? ' selected=selected' : '') . '>' . $row['Genre'] . '</option>';
	}
}
?>
</select>
</TD></TR>
<TR bgcolor=#EEEEEE><TD>Director</TD><TD>
<select name="DirectorID">
<option value=0>All</option>
<?php

if($r = mysql_query("SELECT id, Director FROM Directors ORDER BY Director"))
{
	while($row = mysql_fetch_array($r))
	{
		$selected=false;
		if(!empty($_POST['DirectorID']) && $_POST['DirectorID']==$row['id'])
			$selected=true;
		echo '<option value=' . $row['id'] . ($selected ? ' selected=selected' : '') . '>' . $row['Director'] . '</option>';
	}
}

?>
</select>
</TD></TR>
<TR bgcolor=#DDDDDD><TD>User</TD><TD>
<select name="UserID">
<option value=0>All</option>
<?php

if($r = mysql_query("SELECT id, FirstName, LastName FROM Users ORDER BY LastName, FirstName"))
{
	while($row = mysql_fetch_array($r))
	{
		$selected=false;
		if(!empty($_POST['UserID']) && $_POST['UserID']==$row['id'])
			$selected=true;
		echo '<option value=' . $row['id'] . ($selected ? ' selected=selected' : '') . '>' . $row['LastName'] . ', ' . $row['FirstName'] . '</option>';
	}
}

?>
</select>
</TD></TR>
<TR bgcolor=#DDDDDD><TD>Order By</TD><TD>
<select name="OrderBy">
<option value="Title">Title</option>
<option value="Genre, Title">Genre, Title</option>
<option value="Year">Year</option>
<option value="Director, Title">Director, Title</option>
<option value="LastName, Title">LastName, Title</option>
</select>
</TD></TR>
<TR><TD bgcolor=#EEDDDD></TD><TD bgcolor=#EEEEEE><input name="Button" type=submit value="Search"></TD></TR>
</TABLE>
</form>