<?php
/**
 * C4Masterserver loadup script
 *
 * @package C4Masterserver
 * @version 1.1.5_ex-en
 * @author  Benedict Etzel <b.etzel@live.de>, Tobias Zwick <newton@westnordost.de>
 * @license http://creativecommons.org/licenses/by/3.0/ CC-BY 3.0
 */

$dir = dirname(__FILE__) . '/';
 
require_once($dir . 'include/C4Masterserver.php');
require_once($dir . 'include/C4Network.php');
require_once($dir . 'include/FloodProtection.php');
require_once($dir . 'include/ParseINI.php');

$config = file_get_contents($dir . 'include/config.ini');
$link = mysql_connect(
        ParseINI::ParseValue('mysql_host', $config),
        ParseINI::ParseValue('mysql_user', $config),
        ParseINI::ParseValue('mysql_password', $config)); //connect to MySQL
$db = mysql_selectdb(ParseINI::ParseValue('mysql_db', $config), $link); //select the database
$games = '';
$count = 0;
$flood = false;

if($link && $db) {
    $server = new C4Masterserver($link, ParseINI::ParseValue('mysql_prefix', $config));
    $server->SetTimeoutgames(intval(ParseINI::ParseValue('c4ms_timeoutgames', $config)));
    $server->SetDeletegames(intval(ParseINI::ParseValue('c4ms_deletegames', $config)));
    $server->SetMaxgames(intval(ParseINI::ParseValue('c4ms_maxgames', $config)));
    $protect = new FloodProtection($link, ParseINI::ParseValue('mysql_prefix', $config));
    $protect->SetMaxflood(intval(ParseINI::ParseValue('flood_maxrequests', $config)));
	
    if($protect->CheckRequest($_SERVER['REMOTE_ADDR'])) { //flood protection
		$flood = true;
    }
	else {
		$flood = false;
	
		$list = $server->GetReferenceArray(true);
		$players = '';
		
		foreach($list as $reference) {
			if($reference['valid']) {
				$games .= '<tr>';
				$games .= '<td>'.htmlspecialchars(ParseINI::ParseValue('Title', $reference['data'])).'</td>';
				$games .= '<td>'.htmlspecialchars(ParseINI::ParseValue('State', $reference['data'])).'</td>';
				$games .= '<td>'.date("Y-m-d H:i", $reference['start']).'</td>';
				$players = '';
				$player_list = ParseINI::ParseValuesByCategory('Name', 'Player', $reference['data']);
				foreach($player_list as $player) {
					if(!empty($players)) $players .= ', ';
					$players .= $player;
				}
				$games .= '<td>'.htmlspecialchars($players).'</td>';
			}
			if((ParseINI::ParseValue('State', $reference['data']) == 'Running') && $reference['time'] >= time() - 60*60*24) {
				$count++;
			}
		}
		$games = C4Network::CleanString($games);
		$server->CleanUp();
	}
}

/**
 * Shows all currently running games in a table
 */
function showRunningGames() {
	global $flood, $link, $db;
	global $games;

	if($flood || !$link || !$db) return;
	
	if(!empty($games)) {
		echo '<p class="runninggames">Running games:</p>';
		echo '<table class="runninggames">';
		echo '<tr><th>Round</th><th>State</th><th>Begin</th><th>Players</th></tr>';
		echo $games;
		echo '</table>';
	}
	else {
		echo '<p class="runninggames">Currently no games are running.</p>';
	}
}

/**
 * Shows how many games there were in the last 24 hours
 */
function showGameCount() {
	global $flood, $link, $db;
	global $count;
	
	if($flood || !$link || !$db) return;

	if($count > 0) {
		if($count > 1) {
			echo '<p class="gamecount">'.$count.' games in the last 24 hours.</p>';
		}
		else {
			echo '<p class="gamecount">One game in the last 24 hours.</p>';
		}
	}
	else {
		echo '<p class="gamecount">No games in the last 24 hours.</p>';
	}
}

/**
 * Shows the C4Masterserver footer
 */
function showMasterserverFooter() {
	echo "<p class='footer'>Powered by C4Masterserver v" . C4Masterserver::GetVersion() . " &bull; Coded by Benedict Etzel</p>";
}

/**
 * Shows the error message if there occured an error
 */
function showMasterserverErrorMessage() {
	global $flood, $link, $db;
	
	if(!$link || !$db)
		echo "<p class='error'>Error: Could not connect to database specified in config.</p>";
	elseif($flood)
		echo "<p class='error'>Error: Flood protection.</p>";
}

/**
 * Shows the address of the masterserver
 */
function showMasterserverAddress() {
	$dirname = dirname($_SERVER['SCRIPT_NAME']);
	$path = '';
	if($dirname != '/') {
		$path .= '/';
	}
	$dirname .= $path.'server/';
	$server_link = strtolower($_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$dirname);
	$engine = '';
	$engine_string = ParseINI::ParseValue('c4ms_engine', $config);
	if(!empty($engine_string)) {
		$engine = '('.$engine_string.' only)';
	}

	echo $server_link;
	if($engine) {
		echo ' '.$engine;
	}
}

?>