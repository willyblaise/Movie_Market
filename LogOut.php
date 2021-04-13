<?php
session_start();
if(!empty($_SESSION['UserID']))
	unset($_SESSION['UserID']);
?>
<meta http-equiv="refresh" content="0;url=index.php">