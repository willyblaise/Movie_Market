<?php
include('connect.php');

$i=1;
if(!mysql_query("CREATE TABLE Movies (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Title TEXT, Year INT, DirectorID INT, Image TEXT )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE Directors (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Director TEXT )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE Users (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Username TEXT, Password TEXT, FirstName TEXT, LastName TEXT, Email TEXT, PhoneNumber TEXT )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE MoviesUsers (MovieID INT NOT NULL, UserID INT NOT NULL, Tradeable INT, PRIMARY KEY (MovieID,UserID) )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE Requests (RequesterID INT NOT NULL, RequesteeID INT NOT NULL, MovieID INT NOT NULL, PRIMARY KEY (RequesterID,RequesteeID,MovieID) )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE FavUsers (FavorerID INT NOT NULL, FavoredID INT NOT NULL, PRIMARY KEY (FavorerID,FavoredID) )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("DROP TABLE Genres"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE Genres (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Genre TEXT)"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE Denials (RequesterID INT NOT NULL, RequesteeID INT NOT NULL, MovieID INT NOT NULL, PRIMARY KEY (RequesterID,RequesteeID,MovieID) )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("CREATE TABLE Accepts (RequesterID INT NOT NULL, RequesteeID INT NOT NULL, MovieID INT NOT NULL, PRIMARY KEY (RequesterID,RequesteeID,MovieID) )"))
	echo 'Query ' . $i++ . ' Failed';
if(!mysql_query("INSERT INTO Genres (Genre) VALUES ('Action'),('Adventure'),('Comdedy'),('Crime'),('Documentary'),('Drama'),('Family'),('Fantasy'),('Horror'),('Musical'),('Mystery'),('Romance'),('Science Fiction'),('Sport'),('Thriller'),('War'),('Western')"))
	echo 'Query ' . $i++ . ' Failed';


?>