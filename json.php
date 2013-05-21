<?php
session_start();
require_once 'functions/db.php';
if(isset($_REQUEST['contragent_attachment_list'])){
    $id = htmlspecialchars($_REQUEST['contragent_attachment_list']);
    $query = "SELECT * FROM `ph_contragents_attachments` WHERE `id_contragent` = '{$id}' AND `id_building` = '{$_SESSION['user_building']}'";
    $sql = DataBaseConnection($query);
    $i = 0;
    $attachments = array();
    while($row = mysql_fetch_assoc($sql)){
        $attachments[$i]['id'] = $row['id'];
        $attachments[$i]['name'] = $row['name'];
        $attachments[$i]['filename'] = $row['filename'];
        $attachments[$i]['path'] = $row['path'];
        $i++;
    }
    echo json_encode($attachments);
}
if(isset($_REQUEST['buildings_list'])){
    $id = htmlspecialchars($_REQUEST['buildings_list']);
    $query = "SELECT * FROM `ph_buildings` WHERE `id_street` = '{$id}'";
    $sql = DataBaseConnection($query);
    $i = 0;
    $attachments = array();
    while($row = mysql_fetch_assoc($sql)){
        $attachments[$i]['id'] = $row['id'];
        $attachments[$i]['name'] = $row['number'];
        $i++;
    }
    echo json_encode($attachments);
}
?>
