<?php
session_start();
if(!mysql_connect("127.0.0.1","root","password"))
	die('<BR>Cannot connect to MySQL Server');
if(!mysql_selectdb("Movies"))
	die('<BR>Cannot Find Database');
?>