<?php
require_once 'functions/logic_start.php';
$role = UserRole();
if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0){
    $query = "SELECT * FROM `ph_buildings` as b LEFT JOIN `ph_streets` as s ON b.id_street = s.id 
    WHERE b.id = '{$_SESSION['user_building']}' LIMIT 1";
    $sql = DataBaseConnection($query);
    $row = mysql_fetch_assoc($sql);   
    $xtpl_body->assign('title','Информация о доме');
    $xtpl_body->assign('street_name',$row['name']);
    $xtpl_body->assign('building_number',$row['number']);
    $xtpl_body->assign('building_appartments',$row['app_count']);
    $query = "SELECT * FROM `ph_users` WHERE `id_role` = 2 AND `id_building` = '{$_SESSION['user_building']}'";
    $sql = DataBaseConnection($query);
    if(mysql_num_rows($sql) > 0){
        $xtpl_body->assign('title_tsj','Дом Управляющие');        
        $xtpl_body->parse('body.tsj_admin_can');
        while($row = mysql_fetch_assoc($sql)){
            $xtpl_body->assign('user_last',$row['last_name']);        
            $xtpl_body->assign('user_first',$row['first_name']);        
            $xtpl_body->assign('user_third',$row['third_name']);        
            $xtpl_body->assign('post',$row['post']);        
            $xtpl_body->assign('appartment_number',$row['app_number']);        
            $xtpl_body->assign('user_phone1',$row['phone1']);        
            $xtpl_body->assign('user_phone2',$row['phone2']);        
            $xtpl_body->parse('body.tsj_admin');
        }
    }
    $query = "SELECT * FROM `ph_buildings_attachments` WHERE `id_building` = '{$_SESSION['user_building']}'";
    $sql = DataBaseConnection($query);
    if(mysql_num_rows($sql) > 0){
        $xtpl_body->assign('title_documents','Документы');        
        $xtpl_body->parse('body.documents_can');
        while($row = mysql_fetch_assoc($sql)){
            $xtpl_body->assign('doc_name',$row['name']);                    
            $xtpl_body->assign('doc_path',$row['path']);                    
            $xtpl_body->assign('doc_filename',$row['filename']);                    
            $xtpl_body->parse('body.documents');
        }        
    }
    if($role['create_building_att'] == 'yes'){
            $xtpl_body->assign('button_name','Добавить Документ');            
            $xtpl_body->assign('form_name','add_building_att');            
            $xtpl_body->parse('body.create');
    }
    if($role['create_phone'] == 'yes'){
            $xtpl_body->assign('button_name','Добавить Телефон');            
            $xtpl_body->assign('form_name','add_phone');            
            $xtpl_body->parse('body.create');
    }
}
require_once 'functions/logic_end.php';
?>