<?php
include_once('connect.php');

if(empty($_SESSION['UserID']) || !$_SESSION['UserID'])
	die('<meta http-equiv="refresh" content="0;url=index.php">');

if(!empty($_GET['SwitchTrade']))
{
	mysql_query("Update MoviesUsers SET Tradeable=" . $_GET['SwitchType'] . " WHERE UserID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['SwitchTrade']);
}

if(!empty($_GET['DeleteID']))
{
	mysql_query("DELETE FROM MoviesUsers WHERE UserID=" . $_SESSION['UserID'] . " AND MovieID=" . $_GET['DeleteID']);
}


include('nav.php');
echo '<BR>';
if($r = mysql_query("SELECT Movies.id AS MovieID, Genres.Genre, Movies.Title, Movies.Year, Directors.Director, Directors.id AS DirectorID, Movies.Image, MoviesUsers.Tradeable FROM Movies, Genres, Directors, MoviesUsers WHERE MoviesUsers.UserID=" . $_SESSION['UserID'] . " AND Movies.id=MoviesUsers.MovieID AND Movies.GenreID=Genres.id AND Directors.id=Movies.DirectorID ORDER BY Tradeable DESC, Title, Year"))
{
	echo '<TABLE cellpadding=5 style="font-family:Arial">
<TR><TD colspan=7 bgcolor=#DDEEDD>Up for Trade</TD></TR>
<TR bgcolor=#DDDDDD><TD>Image</TD><TD>Title</TD><TD>Genre</TD><TD>Year</TD><TD>Director</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
';
	$TT=false;
	while($row = mysql_fetch_array($r))
	{
		if(!$TT && !$row['Tradeable'])
		{
			$TT=true;
			echo '<TR><TD colspan=7>&nbsp;</TD></TR><TR><TD colspan=7 bgcolor=#EEDDDD>Not Up for Trade</TD></TR>
			<TR bgcolor=#DDDDDD><TD>Image</TD><TD>Movie Title</TD><TD>Genre</TD><TD>Movie Year</TD><TD>Director</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>';
		}
		echo '<TR><TD><a href="' . $row['Image'] . '"><img border=0 src="' . $row['Image'] . '" width=250></a></TD>
		<TD>' . $row['Title'] . '</TD><TD>' . $row['Genre'] . '</TD><TD>' . $row['Year'] . '</TD><TD><a href="Search.php?DirectorID=' . $row['DirectorID'] . '">' . $row['Director'] . '</a></TD>
		<TD align=center><input type=button value="' . ($row['Tradeable'] ? 'Not-' : '') . 'Tradeable" onClick="window.location.href=\'MyMovies.php?SwitchType=' . ($row['Tradeable'] ? 0 : 1) . '&SwitchTrade=' . $row['MovieID'] . '\'"></TD>
		<TD><a href="MyMovies.php?DeleteID=' . $row['MovieID'] . '"><font size=2 color=red>Remove</font></a></TD></TR>';
	}
	echo '</TABLE>';
}

?>