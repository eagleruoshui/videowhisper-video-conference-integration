<?php

include("../../../../wp-config.php");

$options = get_option('VWvideoConferenceOptions');
$rtmp_server = $options['rtmp_server'];
$rtmp_amf = $options['rtmp_amf'];
$userName =  $options['userName']; if (!$userName) $userName='user_nicename';
$canAccess = $options['canAccess'];
$accessList = $options['accessList'];

global $current_user;
get_currentuserinfo();

//username
if ($current_user->$userName) $username=urlencode($current_user->$userName);
$username=preg_replace("/[^0-9a-zA-Z]/","-",$username);

$loggedin=0;
$msg="";

//access permissions
function inList($item, $data)
{
	$list=explode(",",$data);
	foreach ($list as $listing) if ($item==trim($listing)) return 1;
	return 0;
}

switch ($canAccess)
{	
	case "all":
	$loggedin=1;
	if (!$username) 
	{
		$username="Guest".base_convert((time()-1224350000).rand(0,10),10,36);
		$visitor=1; //ask for username
	}
	break;
	case "members":
		if ($username) $loggedin=1;
		else $msg=urlencode("<a href=\"/\">Please login first or register an account if you don't have one! Click here to return to website.</a>");
	break;
	case "list";
		if ($username)
			if (inList($username, $accessList)) $loggedin=1;
			else $msg=urlencode("<a href=\"/\">$username, you are not in the video conference access list.</a>");
		else $msg=urlencode("<a href=\"/\">Please login first or register an account if you don't have one! Click here to return to website.</a>");
	break;
}

//configure a picture to show when this user is clicked
$userPicture=urlencode("defaultpicture.png");
$userLink=urlencode("http://www.videowhisper.com/");

//replace bad words or expression
$filterRegex=urlencode("(?i)(fuck|cunt)(?-i)");
$filterReplace=urlencode(" ** ");

//fill your layout code between <<<layoutEND and layoutEND;
$layoutCode=<<<layoutEND
layoutEND;

if ($_GET['room_name']) $room = $_GET['room_name'];
if (!$room) $room="Lobby";

if (!$welcome) $welcome="Welcome to $room! <BR><font color=\"#3CA2DE\">&#187;</font> Click top left preview panel for more options including selecting different camera and microphone. <BR><font color=\"#3CA2DE\">&#187;</font> Click any participant from users list for more options including extra video panels. <BR><font color=\"#3CA2DE\">&#187;</font> Try pasting urls, youtube movie urls, picture urls, emails, twitter accounts as @videowhisper in your text chat. <BR><font color=\"#3CA2DE\">&#187;</font> Download daily chat logs from file list.";

?>firstParam=fix&server=<?=$rtmp_server?>&serverAMF=<?=$rtmp_amf?>&username=<?=urlencode($username)?>&loggedin=<?=$loggedin?>&userType=<?=$userType?>&administrator=<?=$admin?>&room=<?=urlencode($room)?>&welcome=<?=urlencode($welcome)?>&userPicture=<?=$userPicture?>&userLink=<?=$userLink?>&webserver=&msg=<?=urlencode($msg)?>&tutorial=0&room_delete=0&room_create=0&file_upload=1&file_delete=1&panelFiles=1&showTimer=1&showCredit=1&disconnectOnTimeout=0&camWidth=320&camHeight=240&camFPS=15&micRate=11&camBandwidth=32768&bufferLive=0.5&bufferFull=0.5&bufferLivePlayback=0.2&bufferFullPlayback=0.5&showCamSettings=1&advancedCamSettings=1&camMaxBandwidth=81920&configureSource=0&disableVideo=0&disableSound=0&background_url=&autoViewCams=1&layoutCode=<?=urlencode($layoutCode)?>&fillWindow=0&filterRegex=<?=$filterRegex?>&filterReplace=<?=$filterReplace?>&loadstatus=1