<?php
function DataBaseConnection($query){
    //ini_set('display_errors',1);
    error_reporting(E_ALL);
    $link = mysql_connect("maki72.me", "ph", "ymi8ex");
    mysql_select_db("phoenix",$link);
    mysql_query("SET character_set_client = utf8");
    mysql_query("SET character_set_connection = utf8");
    mysql_query("SET character_set_results = utf8");
    $sql = mysql_query($query) or die(mysql_error());
    mysql_close();
    return $sql;
}
    
function DBConn(){
    $link = mysql_connect("maki72.me", "ph", "ymi8ex");
    mysql_select_db("phoenix",$link);
    mysql_query("SET character_set_client = utf8");
    mysql_query("SET character_set_connection = utf8");
    mysql_query("SET character_set_results = utf8");
    return $link;
}

function DBClos($link){
    mysql_close($link);
}
?>
