<?php
if($_SERVER['SERVER_NAME'] != "localhost") //If set up on a server (live)...
{
	error_reporting(0); //... don't show errors.
}
else
{
	error_reporting(-1); //... otherwise show all errors.
}

$DBHost = "";
$DBUser = "";
$DBPass = "";
$DBName = "";

if(!(@mysql_connect($DBHost, $DBUser, $DBPass) && mysql_select_db($DBName) && mysql_set_charset("utf8")))
{
	return;
}

function dBErrorCheck($dbstage = -1, $mapcount = 1)
{
	if(mysql_errno() !== 0 || $mapcount !== 1)
	{
		switch($dbstage)
		{
			case 0: //Error while connecting to Database.
			{
				sendMessage(array('error' => 'Could not connect to the Database. Error Number: '.mysql_errno()));
				break;
			}
			case 1: //Map not found in Database.
			{
				sendMessage(array('error' => 'Could not find map. Error Number: '.mysql_errno()));
				break;
			}
			case -1: //Unspecific Database error.
			default:
			{
				sendMessage(array('error' => 'Database returned error. Error Number: '.mysql_errno()));
				break;
			}
		}
	}
}

function encodeMessage($message)
{
	$EncodedMessage = '';

	if(is_array($message))
	{
		foreach($message as $key => $value)
		{
			$EncodedMessage = $EncodedMessage.($EncodedMessage != '' ? '&' : '').
				urlencode($key).'='.urlencode($value);
		}
	}
	else
	{
		$EncodedMessage = urlencode($message);
	}

	return $EncodedMessage;
}

function sendMessage($message)
{
	echo encodeMessage($message);
	exit;
}

function updateMapRating($key)
{
	mysql_query("UPDATE sc_maps SET funScore=good-bad, hardScore=hard-easy,
		mediumScore=medium-(hard/2)-(easy/2), easyScore=easy-hard
		WHERE id='".mysql_real_escape_string($key)."'");
}

function createDBTableSet()
{
	mysql_query("CREATE TABLE IF NOT EXISTS `sc_maps` (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`name` varchar(100) NOT NULL,
		`text` text NOT NULL,
		`created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`good` int(11) NOT NULL DEFAULT '0',
		`ok` int(11) NOT NULL DEFAULT '0',
		`bad` int(11) NOT NULL DEFAULT '0',
		`funScore` int(11) NOT NULL DEFAULT '0',
		`hard` int(11) NOT NULL DEFAULT '0',
		`medium` int(11) NOT NULL DEFAULT '0',
		`easy` int(11) NOT NULL DEFAULT '0',
		`hardScore` int(11) NOT NULL DEFAULT '0',
		`mediumScore` int(11) NOT NULL DEFAULT '0',
		`easyScore` int(11) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");
}
?>