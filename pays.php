<?php
require_once 'functions/logic_start.php';
$role = UserRole();
//print_r($arr);
//Кнопки для перехода либо к контраагентам либо к платежам
if(!isset($_GET['contragents']) && !isset($_GET['pays'])){
    $xtpl_body->parse('body.buttons');
}

//КонтрАгенты, обработчик того что с ним связано
//Список КонтрАгентов с Сылками для более подробной информации
$contragent_id = htmlspecialchars($_GET['contragents']);
if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0 && isset($_GET['contragents']) && $_GET['contragents'] == NULL){
    if($role['delete_contragent'] == 'yes'){
        $xtpl_body->parse('body.delete_js');         
    }
    $xtpl_body->parse('body.contragents_can');    
    if(isset($_GET['check_id'])){
        $xtpl_body->assign('check_id',$_GET['check_id']);            
        $xtpl_body->parse('body.contragent_inn'); 
    }
    $query = "SELECT * FROM  `ph_contragents`
    LEFT JOIN `ph_contragents_buildings` ON (ph_contragents.id = ph_contragents_buildings.id_contragent)
    LEFT JOIN `ph_buildings` ON (ph_contragents_buildings.id_building = ph_buildings.id) WHERE ph_buildings.id = '{$_SESSION['user_building']}'";
    $sql = DataBaseConnection($query);
    while($row = mysql_fetch_assoc($sql)){
        //print_r($_GET['contragents']);
        $xtpl_body->assign('contragent_link',$row['id_contragent']);            
        $xtpl_body->assign('contragent_name',$row['name']); 
        if($role['change_contragent'] == 'yes'){
            $xtpl_body->assign('button_name','Изменить');            
            $xtpl_body->assign('form_name','change_contragent');            
            $xtpl_body->parse('body.contragents.tsj_change');
        }
        if($role['delete_contragent'] == 'yes'){
            $xtpl_body->assign('button_name','Удалить');            
            $xtpl_body->assign('form_name','delete_contragent');            
            $xtpl_body->parse('body.contragents.tsj_delete');
        }
    $xtpl_body->parse('body.contragents');
    }
    //Кнопка для добавления КонтрАгента ----------------------------------- начало
    if($role['create_contragent'] == 'yes'){
        $xtpl_body->assign('form_name','add_contragent');            
        $xtpl_body->assign('button_name','Добавить Контрагента');            
        $xtpl_body->parse('body.contragents_add_can');    
    }
    //Кнопка для добавления КонтрАгента ----------------------------------- конец
//Конкретный КонтрАгент
}elseif(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0 && $_GET['contragents'] != NULL){     
    $query = "SELECT * FROM  `ph_contragents`
    LEFT JOIN `ph_contragents_buildings` ON (ph_contragents.id = ph_contragents_buildings.id_contragent)
    LEFT JOIN `ph_buildings` ON (ph_contragents_buildings.id_building = ph_buildings.id) 
    WHERE ph_buildings.id = '{$_SESSION['user_building']}' AND ph_contragents.id = '{$contragent_id}'";
    $sql = DataBaseConnection($query);    
    while($row = mysql_fetch_assoc($sql)){
        //print_r($_GET['contragents']);
        $xtpl_body->assign('contr_name',$row['name']);
        $xtpl_body->parse('body.contragent_can');    
        switch ($row['type']) {
            case '1':
                $xtpl_body->assign('contr_type','Коммунальное предприятие');                
                break;
            case '2':
                $xtpl_body->assign('contr_type','Не коммунальное предприятие');                
                break;
        }
        $xtpl_body->assign('contr_inn',$row['inn']);            
        $xtpl_body->assign('contr_kpp',$row['kpp']);            
        $xtpl_body->assign('contr_yur',$row['yur_adres']);            
        $xtpl_body->assign('contr_fact',$row['fact_adres']);            
        $xtpl_body->assign('contr_email',$row['email']);            
        $xtpl_body->assign('contr_account',$row['account_number']);            
        $xtpl_body->assign('contr_bank',$row['bank_name']);            
        $xtpl_body->assign('contr_bikbank',$row['bank_bik']);            
        $xtpl_body->assign('contr_korbank',$row['bank_kor']);            
        $xtpl_body->assign('contr_inf',$row['information']);            
        $xtpl_body->parse('body.contragent');
    }
    
    //Подключение контактов
    $query = "SELECT * FROM  `ph_contragents_contacts` WHERE `id_contragent` = '{$contragent_id}' AND `id_building` = '{$_SESSION['user_building']}'";
    $sql = DataBaseConnection($query);
    if(mysql_num_rows($sql)==0){
        $xtpl_body->assign('contact_null','Пока отсутствуют контакты');                
        $xtpl_body->parse('body.contact_can');
    }else{
        $xtpl_body->parse('body.contact_can');        
    }
    if($role['change_contragent'] == 'yes'){
    //Кнопка формочки изменения контрагента внутри формы ----------------------------------- начало
        $xtpl_body->assign('contragent_link',$_GET['contragents']);
        $xtpl_body->assign('button_name','Изменить Контрагента');            
        $xtpl_body->assign('form_name','change_contragent');            
        $xtpl_body->parse('body.tsj_change');
    //Кнопка формочки изменения контрагента внутри формы ----------------------------------- конец
    }
    if($role['create_contragent'] == 'yes'){
    //Кнопка формочки добавления контакта ----------------------------------- начало
        $xtpl_body->assign('contragent_id',$contragent_id); 
        $xtpl_body->assign('form_name','add_contact'); 
        $xtpl_body->assign('button_name','Добавить Контакт'); 
        $xtpl_body->parse('body.contact_add'); 
    //Кнопка формочки добавления контакта ----------------------------------- конец
    }
    while($row = mysql_fetch_assoc($sql)){
        $xtpl_body->assign('contact_post',$row['post']);        
        $xtpl_body->assign('contact_fio',$row['fio']);        
        $xtpl_body->assign('contact_phone1',$row['phone1']);        
        $xtpl_body->assign('contact_phone2',$row['phone2']);        
        $xtpl_body->assign('contact_fax',$row['fax']);        
        $xtpl_body->assign('contact_email',$row['email']);        
        $xtpl_body->assign('contact_id',$row['id']); 
        if($role['change_contragent'] == 'yes'){
            $xtpl_body->assign('contragent_id',$_GET['contragents']);        
            $xtpl_body->assign('form_name','change_contact');        
            $xtpl_body->assign('button_name','Изменить');        
            $xtpl_body->parse('body.contact.contact_change');
        }        
        $xtpl_body->parse('body.contact');
    }
    
    //Подключение документов    
    if($role['create_contragent'] == 'yes'){
        $xtpl_body->assign('form_name','add_document');            
        $xtpl_body->assign('contragent_id',$_GET['contragents']);            
        $xtpl_body->assign('button_name','Добавить Документ');            
        $xtpl_body->parse('body.document_add');    
    }
    $query = "SELECT * FROM  `ph_contragents_attachments` WHERE `id_contragent` = '{$contragent_id}' AND `id_building` = '{$_SESSION['user_building']}'";
    $sql = DataBaseConnection($query);
    if(mysql_num_rows($sql)==0){
        $xtpl_body->assign('docs_null','Пока отсутствуют прикрепленные документы');                
        $xtpl_body->parse('body.docs_can');
    }else{
        $xtpl_body->parse('body.docs_can');        
    }
    while($row = mysql_fetch_assoc($sql)){
        $xtpl_body->assign('doc_path',$row['path']);        
        $xtpl_body->assign('doc_filename',$row['filename']);        
        $xtpl_body->assign('doc_name',$row['name']);     
        //кнопка изменения документа
        if($role['change_contragent'] == 'yes'){
            $xtpl_body->assign('form_name','change_document');        
            $xtpl_body->assign('document_id',$row['id']);        
            $xtpl_body->assign('contragent_id',$_GET['contragents']);        
            $xtpl_body->assign('button_name','Изменить');        
            $xtpl_body->parse('body.docs.document_change');
        }
        $xtpl_body->parse('body.docs');
    }
}

if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0 && isset($_GET['pays'])){
    if(!isset($_GET['outgoings']) && !isset($_GET['drawings'])){
        $xtpl_body->parse('body.buttons');
    }
    //Список всех платежей и приходов
    if(isset($_GET['outgoings']) && $_GET['outgoings'] == NULL && $_GET['incomings'] == NULL){        
        //Список коммунальных платежей
        $xtpl_body->assign('title','Список Коммунальных Платежей');
        $text_from = "";
        $text_to = "";
        if(isset($_GET['from']) && $_GET['from'] != null){
            $from_date = date("Y-m-d H:i:s",strtotime($_GET['from']));
            $text_from = " AND DATE(`outgoing_date`) >= DATE('{$from_date}') ";
            $xtpl_body->assign('from_date',date("d.m.Y",strtotime($_GET['from'])));
        }
        if(isset($_GET['to']) && $_GET['to'] != null){
            $to_date = date("Y-m-d H:i:s",strtotime($_GET['to']));  
            $text_to = " AND DATE(`outgoing_date`) <= DATE('{$to_date}') ";
            $xtpl_body->assign('to_date',date("d.m.Y",strtotime($_GET['to'])));
        }
        $xtpl_body->parse('body.sort');
        $query = "SELECT o.*,c.*,o.`id` as `out_id`  FROM  `ph_outgoings` as o LEFT JOIN `ph_contragents` as c ON o.id_contragent = c.id 
        WHERE id_building = {$_SESSION['user_building']} AND c.type = 1".$text_from.$text_to." ORDER BY `outgoing_date` DESC";
//        print_r($query);
        $sql = DataBaseConnection($query);
        $total = 0;
        while($row = mysql_fetch_assoc($sql)){
            $total += $row['amount'];
            $xtpl_body->assign('contragent_id',$row['id_contragent']);
            $xtpl_body->assign('contragent_name',$row['name']);
            $xtpl_body->assign('outgoing_id',$row['out_id']);
            $xtpl_body->assign('outgoing_osnovanie',$row['osnovanie']);
            $xtpl_body->assign('outgoing_date',date("d.m.Y",strtotime($row['outgoing_date'])));
            $xtpl_body->assign('outgoing_amount',$row['amount']);
            $xtpl_body->parse('body.outgoings.table_data');
//            print_r($row);
        }
        $xtpl_body->assign('outgoing_total',$total);
        $xtpl_body->parse('body.outgoings.can');
        $xtpl_body->parse('body.outgoings.table_start');
        $xtpl_body->parse('body.outgoings.table_end');          
        $xtpl_body->parse('body.outgoings');       
        
        //Список не коммунальных платежей
        $xtpl_body->assign('title','Список Не Коммунальных Платежей');
        if(isset($_GET['from']) && $_GET['from'] != null){
            $from_date = date("Y-m-d H:i:s",strtotime($_GET['from']));
            $text_from = " AND DATE(`outgoing_date`) >= DATE('{$from_date}') ";
        }
        if(isset($_GET['to']) && $_GET['to'] != null){
            $to_date = date("Y-m-d H:i:s",strtotime($_GET['to']));  
            $text_to = " AND DATE(`outgoing_date`) <= DATE('{$to_date}') ";
        }
        $query = "SELECT o.*,c.*,o.`id` as `out_id`  FROM  `ph_outgoings` as o LEFT JOIN `ph_contragents` as c ON o.id_contragent = c.id 
        WHERE id_building = {$_SESSION['user_building']} AND c.type = 2".$text_from.$text_to." ORDER BY `outgoing_date` DESC";
        $sql = DataBaseConnection($query);
        $total = 0;
        while($row = mysql_fetch_assoc($sql)){
            $total += $row['amount'];
            $xtpl_body->assign('contragent_id',$row['id_contragent']);
            $xtpl_body->assign('contragent_name',$row['name']);
            $xtpl_body->assign('outgoing_id',$row['out_id']);
            $xtpl_body->assign('outgoing_osnovanie',$row['osnovanie']);
            $xtpl_body->assign('outgoing_date',date("d.m.Y",strtotime($row['outgoing_date'])));
            $xtpl_body->assign('outgoing_amount',$row['amount']);
            $xtpl_body->parse('body.outgoings.table_data');
//            print_r($row);
        }
        $xtpl_body->assign('outgoing_total',$total);
        $xtpl_body->parse('body.outgoings.can');
        $xtpl_body->parse('body.outgoings.table_start');
        $xtpl_body->parse('body.outgoings.table_end');          
        $xtpl_body->parse('body.outgoings');  
        
        if($role['create_outgoing'] == 'yes'){
            $xtpl_body->assign('form_name','add_outgoing');
            $xtpl_body->assign('button_name','Добавить Платеж');
            $xtpl_body->parse('body.create');
        }
        //Вывод всех приходов
        $xtpl_body->assign('title','Список Приходов');
        if(isset($_GET['from']) && $_GET['from'] != null){
            $from_date = date("Y-m-d H:i:s",strtotime($_GET['from']));
            $text_from = " AND DATE(`income_date`) >= DATE('{$from_date}') ";
        }
        if(isset($_GET['to']) && $_GET['to'] != null){
            $to_date = date("Y-m-d H:i:s",strtotime($_GET['to']));  
            $text_to = " AND DATE(`income_date`) <= DATE('{$to_date}') ";
        }
        $query = "SELECT * FROM `ph_incomings` WHERE `id_building` = {$_SESSION['user_building']}".$text_from.$text_to." ORDER BY `income_date` DESC";
        $sql = DataBaseConnection($query);
        $total = 0;
        while($row = mysql_fetch_assoc($sql)){
            $total += $row['amount'];
            $xtpl_body->assign('incoming_id',$row['id']);
            $xtpl_body->assign('incoming_type',$row['name']);
            $xtpl_body->assign('incoming_date',date("d.m.Y",strtotime($row['income_date'])));
            $xtpl_body->assign('incoming_amount',$row['amount']);
            $xtpl_body->parse('body.incomings.table_data');
//            print_r($row);
        }
        $xtpl_body->assign('incoming_total',$total);
        $xtpl_body->parse('body.incomings.can');
        $xtpl_body->parse('body.incomings.table_start');
        $xtpl_body->parse('body.incomings.table_end');     
        if($role['create_incoming'] == 'yes'){
            $xtpl_body->assign('form_name','add_incoming');
            $xtpl_body->assign('button_name','Добавить Приход');
            $xtpl_body->parse('body.incomings.create');
        }
        $xtpl_body->parse('body.incomings');  
        
        //Вывод отдельного прихода
    }elseif(isset($_GET['outgoings']) && $_GET['incomings'] != NULL){    
        $id = htmlspecialchars($_GET['incomings']);
        $query = "SELECT i.*,d.*,i.`name` as `incomimg_name`,d.`name` as `drawing_name`,d.`id` as `drawing_id` 
        FROM `ph_incomings` as i LEFT JOIN `ph_drawings` as d ON i.id_drawing = d.id 
        WHERE i.id_building = {$_SESSION['user_building']} AND i.id = $id LIMIT 1";
        $sql = DataBaseConnection($query);
        $row = mysql_fetch_assoc($sql);
        $xtpl_body->assign('incoming_type',$row['incomimg_name']);
        $xtpl_body->assign('incoming_date',date("d.m.Y",strtotime($row['income_date'])));
        $xtpl_body->assign('incoming_amount',$row['amount']);
        $xtpl_body->assign('drawing_id',$row['drawing_id']);
        $xtpl_body->assign('drawing_name',$row['drawing_name']);
        $xtpl_body->parse('body.incoming');    
    ////Вывод отдельного платежа
    }elseif(isset($_GET['outgoings']) && $_GET['outgoings'] != NULL){
        $id = htmlspecialchars($_GET['outgoings']);
        $query = "SELECT og.*,contr.*,contrat.*,draw.*,outat.*,og.`reg_date` as `out_reg_date`,
            og.`change_date` as `out_change_date`,draw.`id` as `draw_id`,draw.`name` as `draw_name`,
            contr.`name` as `contr_name`,contr.`id` as `contr_id`,contrat.`name` as `att_name`,
            contrat.`path` as `att_path`,contrat.`filename` as `att_filename`,outat.`name` as `out_name`,
            outat.`filename` as `out_filename`, outat.`path` as `out_path`
            FROM `ph_outgoings` as og 
            LEFT JOIN `ph_contragents` as contr on og.id_contragent = contr.id 
            LEFT JOIN `ph_contragents_attachments` as contrat on og.id_contragent_doc = contrat.id 
            LEFT JOIN `ph_drawings` as draw on og.id_drawing = draw.id 
            LEFT JOIN `ph_outgoings_attachments` as outat on og.id = outat.id_outgoing
            WHERE og.id = '{$id}' LIMIT 1";
        $sql = DataBaseConnection($query);
        $row = mysql_fetch_assoc($sql);
        $xtpl_body->assign('outgoing_date',date("d.m.Y",strtotime($row['outgoing_date'])));
        $xtpl_body->assign('outgoing_osnovanie',$row['osnovanie']);
        $xtpl_body->assign('outgoing_amount',$row['amount']);
        $xtpl_body->assign('outgoing_reg_date',date("d.m.Y - H:m",  strtotime($row['out_reg_date'])));
        $xtpl_body->assign('outgoing_change_date',date("d.m.Y - H:m",  strtotime($row['out_change_date'])));
        $xtpl_body->assign('drawing_id',$row['draw_id']);
        $xtpl_body->assign('drawing_name',$row['draw_name']);
        $xtpl_body->assign('contr_id',$row['contr_id']);
        $xtpl_body->assign('contr_name',$row['contr_name']);
        $xtpl_body->assign('doc_name',$row['att_name']);
        $xtpl_body->assign('doc_path',$row['att_path']);
        $xtpl_body->assign('doc_filename',$row['att_filename']);
        $xtpl_body->assign('out_name',$row['out_name']);
        $xtpl_body->assign('out_path',$row['out_path']);
        $xtpl_body->assign('out_filename',$row['out_filename']);
        $xtpl_body->parse('body.outgoing');        
//        print_r($row);
    }
    
    //Списиок всех выписок из Банка
    if(isset($_GET['drawings']) && $_GET['drawings'] == NULL){ 
        $query = "SELECT * FROM `ph_drawings` WHERE `id_building` = '{$_SESSION['user_building']}'";
        $sql = DataBaseConnection($query);
        while($row = mysql_fetch_assoc($sql)){
            $xtpl_body->assign('drawing_id',$row['id']);
            $xtpl_body->assign('drawing_name',$row['name']);
            $xtpl_body->assign('drawing_start_date',date("d.m.Y",strtotime($row['start_date'])));
            $xtpl_body->assign('drawing_end_date',date("d.m.Y",strtotime($row['end_date'])));
            $xtpl_body->parse('body.drawings.table_data');
        }
        if($role['create_drawing'] == 'yes'){
            $xtpl_body->assign('form_name','add_drawing');
            $xtpl_body->assign('button_name','Добавить Выписку');
            $xtpl_body->parse('body.drawings.create');
        }
            $xtpl_body->parse('body.drawings.can');
            $xtpl_body->parse('body.drawings.table_start');
            $xtpl_body->parse('body.drawings');
    }
    elseif(isset($_GET['drawings']) && $_GET['drawings'] != NULL){
        $id = htmlspecialchars($_GET['drawings']);
        $query = "SELECT * FROM `ph_drawings` WHERE `id` = '{$id}' LIMIT 1";
        $sql = DataBaseConnection($query);
        while($row = mysql_fetch_assoc($sql)){
            $xtpl_body->assign('drawing_name',$row['name']);
            $xtpl_body->assign('drawing_start_date',date("d.m.Y",strtotime($row['start_date'])));
            $xtpl_body->assign('drawing_end_date',date("d.m.Y",strtotime($row['end_date'])));
            $xtpl_body->parse('body.drawing.drawing');
        }
        $query = "SELECT * FROM `ph_drawings_attachments` WHERE `id_drawing` = '{$id}'";
        $sql = DataBaseConnection($query);
        while($row = mysql_fetch_assoc($sql)){
//           print_r($row);
            $xtpl_body->assign('doc_name',$row['name']);
            $xtpl_body->assign('doc_path',$row['path']);
            $xtpl_body->assign('doc_filename',$row['filename']);
            $xtpl_body->parse('body.drawing.documents');
        }
            $xtpl_body->parse('body.drawing');
    }
}

require_once 'functions/logic_end.php';
?>
