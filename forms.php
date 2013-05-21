<?php
//Файл заполнения форм контролируемой информацией
require_once 'functions/logic_start.php';
$role = UserRole();
//Проверка условия может ли данный пользователь изменять данные
if(isset($_SESSION['user_id']) && $role['change_contragent'] == 'yes'){
    switch ($_POST['form_name']) {
        //Форма изменения контрагента
        case 'change_contragent':
            //Заполнение формы изменения для удобства ТСЖ
            $xtpl_body->assign('form_name','contragent_change');
            $xtpl_body->assign('button_name','Изменить');
            $contragent_id = mysql_escape_string($_POST['contragent']);
            $query = "SELECT * FROM `ph_contragents` WHERE `id` = '{$contragent_id}' LIMIT 1";
            $sql = DataBaseConnection($query);    
            $row = mysql_fetch_assoc($sql);
            $xtpl_body->assign('contr_id',$row['id']);
            $xtpl_body->assign('contr_name',$row['name']);
            $xtpl_body->assign('button_to_control','contragent_change_to_control');
            switch ($row['type']) {
                case 1:
                    $xtpl_body->assign('checked1','checked');
                    break;
                case 2:
                    $xtpl_body->assign('checked2','checked');
                    break;
            }
            $xtpl_body->assign('contr_inn',$row['inn']);
            $xtpl_body->assign('contr_kpp',$row['kpp']);
            $xtpl_body->assign('contr_yur',$row['yur_adres']);
            $xtpl_body->assign('contr_fact',$row['fact_adres']);
            $xtpl_body->assign('contr_email',$row['email']);
            $xtpl_body->assign('contr_account',$row['account_number']);
            $xtpl_body->assign('contr_bankname',$row['bank_name']);
            $xtpl_body->assign('contr_bik',$row['bank_bik']);
            $xtpl_body->assign('contr_kor',$row['bank_kor']);
            $xtpl_body->assign('contr_information',$row['information']);
            break;               
        case 'change_contact':
            $contact_id = mysql_escape_string($_POST['contact_id']);
            $query = "SELECT * FROM `ph_contragents_contacts` WHERE `id` = '{$contact_id}' LIMIT 1";
            $sql = DataBaseConnection($query);
            $row = mysql_fetch_assoc($sql);
            $xtpl_body->assign('contr_id',$_POST['contragent_id']);
            $xtpl_body->assign('contact_id',$contact_id);
            $xtpl_body->assign('contact_post',$row['post']);
            $xtpl_body->assign('contact_fio',$row['fio']);
            $xtpl_body->assign('contact_phone1',$row['phone1']);
            $xtpl_body->assign('contact_phone2',$row['phone2']);
            $xtpl_body->assign('contact_fax',$row['fax']);
            $xtpl_body->assign('contact_email',$row['email']);
            $xtpl_body->assign('button_name','Изменить');
            $xtpl_body->assign('button_to_control','contact_change_to_control');
            break;        
        case 'change_document':
            $document_id = htmlspecialchars($_POST['document_id']);
            $query = "SELECT * FROM `ph_contragents_attachments` WHERE `id` = '{$document_id}' LIMIT 1";
            $sql = DataBaseConnection($query);
            $row = mysql_fetch_assoc($sql);
            $xtpl_body->assign('contr_id',$_POST['contragent_id']);
            $xtpl_body->assign('file_dest',$row['path'].$row['filename']);
            $xtpl_body->assign('document_id',$document_id);
            $xtpl_body->assign('document_name',$row['name']);
            $xtpl_body->assign('button_name','Изменить');
            $xtpl_body->assign('button_to_control','document_change_to_control');
            break;        
    }
}

if(isset($_SESSION['user_id']) && $role['create_contragent'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_contragent':
            $xtpl_body->assign('form_name','contragent_add');
            $xtpl_body->assign('building_id',$_SESSION['user_building']);
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','contragent_add_to_control');
            $xtpl_body->assign('checked1','checked');
            break;
        case 'add_contact':
            $xtpl_body->assign('contr_id',$_POST['contragent_id']);
            $xtpl_body->assign('form_name','contact_add');
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','contact_add_to_control');
            break;
        case 'add_document':
            $xtpl_body->assign('contr_id',$_POST['contragent_id']);
            $xtpl_body->assign('form_name','add_document');
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','document_add_to_control');
            break;
        //Связать существующий Контрагент с домом
        case 'inn_connection_to_building':
            require_once 'functions/reg.php';
            CreateContrAgentConnectionToBuilding($_SESSION['user_building'], $_POST['contragent']);
            header('Location: pays.php?contragents&change');
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_outgoing'] == 'yes'){
//    print_r($_POST);
    switch ($_POST['form_name']) {
        case 'add_outgoing':
            $xtpl_body->assign('form_name','outgoing_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','outgoing_add_to_control');
            $xtpl_body->assign('contragent_name','outgoing_add_to_control');
            $query = "SELECT c.*,cb.*,c.`id` as `contr_id` FROM `ph_contragents` as c 
                    LEFT JOIN `ph_contragents_buildings` as cb ON c.id = cb.id_contragent
                    WHERE cb.id_building = '{$_SESSION['user_building']}'";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('contragent_name',$row['name']);                
                $xtpl_body->assign('contragent_id',$row['contr_id']);                
                $xtpl_body->parse('body.contragent');                
            }
            $query = "SELECT * FROM `ph_drawings` WHERE `id_building` = '{$_SESSION['user_building']}'";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('drawing_name',$row['name']);                
                $xtpl_body->assign('drawing_id',$row['id']);                
                $xtpl_body->parse('body.drawing');                
            }
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_drawing'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_drawing':
            $xtpl_body->assign('form_name','outgoing_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','drawing_add_to_control');
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_incoming'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_incoming':
            $xtpl_body->assign('form_name','incoming_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','incoming_add_to_control');
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_street'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_street':
            $xtpl_body->assign('form_name','street_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','street_add_to_control');
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_news'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_news':
            $xtpl_body->assign('form_name','news_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','news_add_to_control');
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_building_att'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_building_att':
            $xtpl_body->assign('form_name','building_att_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','building_att_add_to_control');
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_phone'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_phone':
            $xtpl_body->assign('form_name','phone_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','phone_add_to_control');
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_building'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_building':
            $xtpl_body->assign('form_name','building_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','building_add_to_control');
            $query = "SELECT `name`,`id` FROM `ph_streets`";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('street_id',$row['id']);
                $xtpl_body->assign('street_name',$row['name']);
                $xtpl_body->parse('body.option_street');
            }
            break;
    }
}

if(isset($_SESSION['user_id']) && $role['create_user'] == 'yes'){
    switch ($_POST['form_name']) {
        case 'add_user':
            $xtpl_body->assign('form_name','user_add');            
            $xtpl_body->assign('button_name','Добавить');
            $xtpl_body->assign('button_to_control','user_add_to_control');
            $query = "SELECT `name`,`id` FROM `ph_streets`";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('street_id',$row['id']);
                $xtpl_body->assign('street_name',$row['name']);
                $xtpl_body->parse('body.street');
            }
            break;
    }
}
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 0){
    switch ($_POST['form_name']) {
        case 'enter_building':
            $xtpl_body->assign('form_name','enter_building');            
            $xtpl_body->assign('button_name','Войти');
            $xtpl_body->assign('button_to_control','enter_building');
            $query = "SELECT `name`,`id` FROM `ph_streets`";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('street_id',$row['id']);
                $xtpl_body->assign('street_name',$row['name']);
                $xtpl_body->parse('body.street');
            }
            break;
    }
}

require_once 'functions/logic_end.php';
?>
