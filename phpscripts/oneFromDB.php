<?php
require "../db.php";
function getDB($id, $link, $table = 'users')
{
	$user = mysqli_query($link, "SELECT * FROM $table WHERE id = '$id' LIMIT 1");
	$row = mysqli_fetch_array($user);
	return $row;
}
?>