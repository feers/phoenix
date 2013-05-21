<?php
/**
 * C4Masterserver main frontend
 *
 * @package C4Masterserver
 * @version 1.1.5_ex-en
 * @author  Benedict Etzel <b.etzel@live.de>, Tobias Zwick <newton@westnordost.de>
 * @license http://creativecommons.org/licenses/by/3.0/ CC-BY 3.0
 */

//error_reporting(E_NONE); //suppress errors

require_once('server/frontend.php');

?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
    <head>
        <title>C4Masterserver</title>
        <meta http-equiv='content-type' content='text/html; charset=utf-8'>
        <meta http-equiv='content-style-type' content='text/css'>
        <link rel='stylesheet' href='masterserver.css' type='text/css'>
    </head>
    <body>
        <div id="masterserver">
            <h1>Masterserver</h1>
            <?php 
			
			echo '<p>Masterserver address: <strong>';
			showMasterserverAddress();
			echo '</strong></p>';
			showMasterserverErrorMessage();
			showRunningGames();
			showGameCount();
			showMasterserverFooter();

            ?>
        </div>
    </body>
</html>