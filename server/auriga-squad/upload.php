<?php
ob_start(); //Catch any errors (don't show sensitive variables!).
include_once '_settings.php'; //Load settings (Database Connection, Communication, ...)
ob_end_clean(); //Delete anything that got caught.
dBErrorCheck(0); //Check for Database errors.

$Secret = 'men judge generally more by the eye than by the hand, because it belongs to everybody to see you, to few to come in touch with you.';

if(!isset($_POST['map']) || !isset($_POST['name']) || !isset($_POST['verify']))
{
	sendMessage(array('error' => 'Server request is missing some important variables!'));
}

$MapText = htmlentities($_POST['map'], ENT_QUOTES);
$NameText = htmlentities($_POST['name'], ENT_QUOTES);
$VerifyGiven = htmlentities($_POST['verify'], ENT_QUOTES);

if($VerifyGiven != md5($Secret.$_POST['map']))
{
	sendMessage(array('error' => 'Map was corrupted in transit.'));
}

mysql_query("INSERT INTO sc_maps SET name='".mysql_real_escape_string($NameText)."',
	text='".mysql_real_escape_string($MapText)."',
	ip='".md5($_SERVER['REMOTE_ADDR'])."'");
dBErrorCheck();
$Key = mysql_insert_id();
dBErrorCheck();
sendMessage(array('result' => 'http://'.$_SERVER['HTTP_HOST'].
	preg_replace('%upload/?$%i', 'get/', $_SERVER['REQUEST_URI']).$Key,	'key' => $Key));
?>