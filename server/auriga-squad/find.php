<?php
ob_start(); //Catch any error messages (don't show sensitive variables!).
include_once '_settings.php'; //Load settings (Database Connection, Communication, ...)
ob_end_clean(); //Delete anything that got caught.
dBErrorCheck(0); //Check for Database errors.

$Type = isset($_POST['type']) ? htmlentities($_POST['type'], ENT_QUOTES) : '';
$Start = isset($_POST['start']) ? htmlentities($_POST['start'], ENT_QUOTES) : 0;
$Count = isset($_POST['count']) ? htmlentities($_POST['count'], ENT_QUOTES) : 5;

switch($Type)
{
	case 'fun':
	{
		$QueryOrder = 'funScore';
		break;
	}
	case 'hard':
	{
		$QueryOrder = 'hardScore';
		break;
	}
	case 'medium':
	{
		$QueryOrder = 'mediumScore';
		break;
	}
	case 'easy':
	{
		$QueryOrder = 'easyScore';
		break;
	}
	case 'recent':
	{
		$QueryOrder = 'created';
		break;
	}
	default:
	{
		$QueryOrder = 'name';
		break;
	}
}

$Records = mysql_query("SELECT name, id FROM sc_maps ".($QueryOrder == 'created' ? 'WHERE good=0 AND bad=0
	AND ok=0' : '')." ORDER BY $QueryOrder DESC LIMIT ".mysql_real_escape_string($Start).", ".
	mysql_real_escape_string($Count));
dBErrorCheck();
$Current = 0;
$Result = array();

while($Record = mysql_fetch_row($Records))
{
	$Result['name'.$Current] = $Record[0];
	$Result['key'.$Current] = $Record[1];
	$Current++;
}

$Result['size'] = $Current;
sendMessage(array('result' => encodeMessage($Result)));
?>