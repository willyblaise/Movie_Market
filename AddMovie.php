<?php
include('connect.php');
include('nav.php');


if(empty($_SESSION['UserID']) || !$_SESSION['UserID'])
	die('<meta http-equiv="refresh" content="0;url=index.php">');

if(!empty($_POST['MovieID']))
{
	if(mysql_query('INSERT INTO MoviesUsers SET MovieID=' . $_POST['MovieID'] . ', UserID=' . $_SESSION['UserID'] . ', Tradeable=0'))
		echo 'Movie Added.';
	else
		echo '<font color=red>You already have that movie in your collection.</font>';
}
if(!empty($_POST['Title']))
{
	if(empty($_POST['Year']))
		echo '<font color=red>Year must be specified.</font>';
	else if(!is_numeric($_POST['Year']) || $_POST['Year']<1000 || $_POST['Year']>9999)
		echo '<font color=red>Year must be a 4 digit number.</font>';
	else if($_POST['DirectorRadio']==2 && empty($_POST['Director']))
		echo '<font color=red>You did not enter a director\'s name.</font>';
	else
	{
		if($_POST['DirectorRadio']==1)
			$DirectorID = $_POST['DirectorID'];
		else
		{
			mysql_query("INSERT INTO Directors SET Director='" . str_replace("'","\\'",$_POST['Director']) . "'");
			$r = mysql_query("SELECT LAST_INSERT_ID()");
			$row = mysql_fetch_array($r);
			$DirectorID = $row[0];
		}
		mysql_query("INSERT INTO Movies SET Title='" . str_replace("'","\\'",$_POST['Title']) . "', Year=" . $_POST['Year'] . ", DirectorID=" . $DirectorID . ", GenreID=" . $_POST['Genre'] . ", Image='" . $_POST['Image'] . "'");
		$r = mysql_query("SELECT LAST_INSERT_ID()");
		$row = mysql_fetch_array($r);
		$MovieID = $row[0];
		mysql_query("INSERT INTO MoviesUsers SET MovieID=" . $MovieID . ", UserID=" . $_SESSION['UserID'] . ", Tradeable=0");
		echo 'Added Movie to your collection.';

	}
}



echo '<TABLE><TR><TD width=350 bgcolor=#DDDDDD align=center><form action="AddMovie.php" method=POST>
<select name="MovieID">';

if($r = mysql_query("SELECT id, Title, Year FROM Movies ORDER BY Title, Year"))
{
	while($row = mysql_fetch_array($r))
	{
		echo '<option value=' . $row['id'] . '>' . $row['Title'] . ' (' . $row['Year'] . ')</option>';
	}
}

echo '</select><BR>
<input type=submit value="Add This Movie">
</form></TD></TR></TABLE>';

?>

<BR>
<BR>

<form action="AddMovie.php" method=POST>
<TABLE>
<TR><TD colspan=2 align=center bgcolor=#EEEEDD>New Movie</TD></TR>
<TR bgcolor=#DDDDDD><TD>Title *</TD><TD><input type=text name="Title" value="<?php if(!empty($_POST['Title'])) { echo $_POST['Title']; }?>"></TD></TR>
<TR bgcolor=#EEEEEE><TD>Year *</TD><TD><input type=text name="Year" size=4 value="<?php if(!empty($_POST['Year'])) { echo $_POST['Year']; }?>"></TD></TR>
<TR bgcolor=#DDDDDD><TD>Genre *</TD><TD>
<select name="Genre">
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
<TR bgcolor=#EEEEEE><TD>Director *</TD><TD>
<input type=radio name="DirectorRadio" value=1 <?php if(empty($_POST['DirectorRadio']) || $_POST['DirectorRadio']==1) {echo 'checked=checked';}?>>
<select name="DirectorID" onClick="this.form.DirectorRadio[0].checked='checked'">
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
<input type=radio name="DirectorRadio" value=2 <?php if(!empty($_POST['DirectorRadio']) && $_POST['DirectorRadio']==2) {echo 'checked=checked';}?>><input type=text name="Director" onClick="this.form.DirectorRadio[1].checked='checked'"></TD></TR>
<TR bgcolor=#DDDDDD><TD>Image URL</TD><TD><input type=text name="Image" value="<?php if(!empty($_POST['Image'])) { echo $_POST['Image']; }?>"></TD></TR>
<TR><TD bgcolor=#EEDDDD></TD><TD bgcolor=#EEEEEE><input type=submit value="Add to Collection"></TD></TR>
</TABLE>
</form>