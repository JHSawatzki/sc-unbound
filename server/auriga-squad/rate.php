<?php
ob_start(); //Catch any error messages (don't show sensitive variables!).
include_once '_settings.php'; //Load settings (Database Connection, Communication, ...)
ob_end_clean(); //Delete anything that got caught.
dBErrorCheck(0); //Check for Database errors.

if(!isset($_GET['id']) || !isset($_POST['fun']) || !isset($_POST['difficulty']))
{
	sendMessage(array('error' => 'Server request is missing some important variables!'));
}

$Key = htmlentities($_GET['id'], ENT_QUOTES);

if(mysql_result(mysql_query("SELECT COUNT(*) FROM sc_maps
	WHERE id='".mysql_real_escape_string($Key)."'"), 0) !== '1' || mysql_errno() !== 0)
{
	dBErrorCheck(1, 0);
}

$Deltas = array(0, 0, 0, 0, 0, 0);
$Fun = htmlentities($_POST['fun'], ENT_QUOTES);
$Diff = htmlentities($_POST['difficulty'], ENT_QUOTES);

switch($Fun)
{
	case 'good':
	{
		$Deltas[0] = 1;
		break;
	}
	case 'ok':
	{
		$Deltas[1] = 1;
		break;
	}
	case 'bad':
	{
		$Deltas[2] = 1;
		break;
	}
}

if($Fun != 'bad')
{
	switch($Diff)
	{
		case 'hard':
		{
			$Deltas[3] = 1;
			break;
		}
		case 'medium':
		{
			$Deltas[4] = 1;
			break;
		}
		case 'easy':
		{
			$Deltas[5] = 1;
			break;
		}
	}
}

mysql_query("UPDATE sc_maps SET good=good+{$Deltas[0]}, ok=ok+{$Deltas[1]}, bad=bad+{$Deltas[2]},
	hard=hard+{$Deltas[3]}, medium=medium+{$Deltas[4]}, easy=easy+{$Deltas[5]}
	WHERE id='".mysql_real_escape_string($Key)."'");
dBErrorCheck();
updateMapRating($Key);
dBErrorCheck();
sendMessage(array('result' => 'confirm'));
?>