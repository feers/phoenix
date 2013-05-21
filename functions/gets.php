<?php
//Заголовок страницы
//session_start();
function GetTitle(){
    $query = "SELECT `pr_value` FROM `ph_params` WHERE `pr_name` = 'title' LIMIT 1";
    $sql = DataBaseConnection($query);
    $result = mysql_fetch_assoc($sql);
    return $result['pr_value'];    
}
?>
