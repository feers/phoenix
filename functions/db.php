<?php
function DataBaseConnection($query){
    mysql_connect("maki72.me:3372", "ph", "ymi8ex");
    mysql_select_db("phoenix");
    mysql_query("SET character_set_client = utf8");
    mysql_query("SET character_set_connection = utf8");
    mysql_query("SET character_set_results = utf8");
    $sql = mysql_query($query) or die(mysql_error());
    mysql_close();
    return $sql;
}
    
function DBConn(){
    $link = mysql_connect("maki72.me:3372", "ph", "ymi8ex");
    mysql_select_db("phoenix");
    mysql_query("SET character_set_client = utf8");
    mysql_query("SET character_set_connection = utf8");
    mysql_query("SET character_set_results = utf8");
    return $link;
}

function DBClos($link){
    mysql_close($link);
}
?>