<?php
ob_start(); //Catch any error messages (don't show sensitive variables!).
include_once '_settings.php';
ob_end_clean(); //Delete anything that got caught.
dBErrorCheck(0); //Check for Database errors.

if(!isset($_GET['id']))
{
	sendMessage(array('error' => 'Server request is missing some important variables!'));
}

$Key = htmlentities($_GET['id'], ENT_QUOTES);
$Request = mysql_query("SELECT text FROM sc_maps WHERE id='".mysql_real_escape_string($Key)."'");
dBErrorCheck(1, mysql_num_rows($Request));
sendMessage(array('result' => html_entity_decode(mysql_result($Request, 0), ENT_QUOTES), 'key' => $Key));
?>