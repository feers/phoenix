<?php
session_start();
require_once 'reg.php';
require_once 'functions.php';
$role = UserRole();
//Обработчики пришедших данных с формp
    //print_r($_POST);
//Обработка формы изменения контрагнета
if(isset($_SESSION['user_id']) && $role['change_contragent'] == 'yes' && isset($_POST['contragent_change_to_control'])){
    $id = htmlspecialchars($_POST['contragent_id']);
    $name = htmlspecialchars($_POST['contragent_name']);
    $type = htmlspecialchars($_POST['contragent_type']);
    $inn = htmlspecialchars($_POST['contragent_inn']);
    $kpp = htmlspecialchars($_POST['contragent_kpp']);
    $yur = $_POST['contragent_yur'];
    $fact = $_POST['contragent_fact'];
    $email = htmlspecialchars($_POST['contragent_email']);
    $account = htmlspecialchars($_POST['contragent_account']);
    $bankname = htmlspecialchars($_POST['contragent_bankname']);
    $bik = htmlspecialchars($_POST['contragent_bik']);
    $kor = htmlspecialchars($_POST['contragent_kor']);
    $information = $_POST['contragent_information'];
    UpdateContrAgent($id,$name,$type,$inn,$kpp,$yur,$fact,$email,$account,$bankname,$kor,$information);
    header('Location: ../pays.php?contragents&change');
}
//Обработка формы добавления контрагнета
if(isset($_SESSION['user_id']) && $role['create_contragent'] == 'yes' && isset($_POST['contragent_add_to_control'])){
    $name = htmlspecialchars($_POST['contragent_name']);
    $type = htmlspecialchars($_POST['contragent_type']);
    $inn = htmlspecialchars($_POST['contragent_inn']);
    $check_id = CheckContrAgentINN($inn);
    if(CheckContrAgentINN($inn) != null){
        header("Location: ../pays.php?contragents&change&check_id=$check_id");  
        break;
    }
    $kpp = htmlspecialchars($_POST['contragent_kpp']);
    $yur = $_POST['contragent_yur'];
    $fact = $_POST['contragent_fact'];
    $email = htmlspecialchars($_POST['contragent_email']);
    $account = htmlspecialchars($_POST['contragent_account']);
    $bankname = htmlspecialchars($_POST['contragent_bankname']);
    $bik = htmlspecialchars($_POST['contragent_bik']);
    $kor = htmlspecialchars($_POST['contragent_kor']);
    $information = $_POST['contragent_information'];
    CreateContrAgent($name,$type,$inn,$kpp,$yur,$fact,$email,$account,$bankname,$bik,$kor,$information);
    //CreateContrAgentConnectionToBuilding($id,  mysql_insert_id());
    header('Location: ../pays.php?contragents&change');
}
//Обработка формы добавления контакта
if(isset($_SESSION['user_id']) && $role['create_contragent'] == 'yes' && isset($_POST['contact_add_to_control'])){
    $contragent_id = htmlspecialchars($_POST['contragent_id']);
    $id_building = $_SESSION['user_building'];
    $fio = htmlspecialchars($_POST['contact_fio']);
    $post = htmlspecialchars($_POST['contact_post']);
    $phone1 = htmlspecialchars($_POST['contact_phone1']);
    $phone2 = htmlspecialchars($_POST['contact_phone2']);
    $fax = htmlspecialchars($_POST['contact_fax']);
    $email = htmlspecialchars($_POST['contact_email']);
    CreateContrAgentContact($contragent_id, $id_building, $fio, $post, $phone1, $phone2, $fax, $email);
    //CreateContrAgentConnectionToBuilding($id,  mysql_insert_id());
    header("Location: ../pays.php?contragents=$contragent_id");
}
//Обработка формы изменения контакта
if(isset($_SESSION['user_id']) && $role['change_contragent'] == 'yes' && isset($_POST['contact_change_to_control'])){
    $contragent_id = htmlspecialchars($_POST['contragent_id']);
    $contact_id = htmlspecialchars($_POST['contact_id']);
    $fio = htmlspecialchars($_POST['contact_fio']);
    $post = htmlspecialchars($_POST['contact_post']);
    $phone1 = htmlspecialchars($_POST['contact_phone1']);
    $phone2 = htmlspecialchars($_POST['contact_phone2']);
    $fax = htmlspecialchars($_POST['contact_fax']);
    $email = htmlspecialchars($_POST['contact_email']);
    UpdateContrAgentContact($contact_id, $fio, $post, $phone1, $phone2, $fax, $email);
    //CreateContrAgentConnectionToBuilding($id,  mysql_insert_id());
    header("Location: ../pays.php?contragents=$contragent_id");
}
//Обработка формы добавления документа
if(isset($_SESSION['user_id']) && $role['create_contragent'] == 'yes' && isset($_POST['document_add_to_control'])){    
    $contragent_id = htmlspecialchars($_POST['contragent_id']);
    $id_building = $_SESSION['user_building'];
    $name = htmlspecialchars($_POST['document_name']);
    $fname =  	$_FILES['document']['name'];
    $type = 	$_FILES['document']['type'];
    $tmp_name = $_FILES['document']['tmp_name'];
    if (!is_uploaded_file($tmp_name)) {
        header("Location: ../pays.php?contragents=$contragent_id&err=filenotselected");        
        break;
    }
    if ($type == "image/jpeg"){        
        $file_ext = substr($fname, 1 + strrpos($name, "."));
        $file_name = substr($fname, 0, strrpos($name, "."));
        $filepath = 'docs/contragents';
        $real_path = realpath('../'.$filepath);
        $temp_file_name = tempnam($real_path, "DOCSCONTRAGENT");
        $file_name = $temp_file_name."orig".date("Y_m_d_H_m_s").'.jpg';
        if (move_uploaded_file($tmp_name, $file_name)) {
            require_once '../libs/image_resize.php';
            $imagedata = getimagesize($file_name);
            if($imagedata[0]>$imagedata[1]){
                img_resize($file_name, $file_name, 1024,  768, 0xFFFFF0, 0);
            }else{
                img_resize($file_name, $file_name, 768,  1024, 0xFFFFF0, 0);                
            }
            unlink($temp_file_name); // удаляем временный файл
            CreateContrAgentAttachment($contragent_id, $id_building, $name, basename($file_name), $filepath.'/', filesize($file_name));
            header("Location: ../pays.php?contragents=$contragent_id");
            
        }else {
            echo "Что-то пошло не так :-[";
            continue;
        }       
    }else{
        header("Location: ../pays.php?contragents=$contragent_id&err=filetypeerror");        
    }
}
//Обработка формы изменения документа
if(isset($_SESSION['user_id']) && $role['change_contragent'] == 'yes' && isset($_POST['document_change_to_control'])){    
    $contragent_id = htmlspecialchars($_POST['contragent_id']);
    $document_id = htmlspecialchars($_POST['document_id']);
    $name = htmlspecialchars($_POST['document_name']);
    $fname =  	$_FILES['document']['name'];
    $type = 	$_FILES['document']['type'];
    $tmp_name = $_FILES['document']['tmp_name'];
    if (!is_uploaded_file($tmp_name)) {
        UpdateContrAgentDocument($document_id,$name,null,null,null);
        header("Location: ../pays.php?contragents=$contragent_id");        
        break;
    }
    if ($type == "image/jpeg"){
        $oldfile = $_POST['file_dest'];
        $file_ext = substr($fname, 1 + strrpos($name, "."));
        $file_name = substr($fname, 0, strrpos($name, "."));
        $filepath = 'docs/contragents';
        $real_path = realpath('../'.$filepath);
        $temp_file_name = tempnam($real_path, "DOCSCONTRAGENT");
        $file_name = $temp_file_name."orig".date("Y_m_d_H_m_s").'.jpg';;
        if (move_uploaded_file($tmp_name, $file_name)) {
            require_once '../libs/image_resize.php';
            $imagedata = getimagesize($file_name);
            if($imagedata[0]>$imagedata[1]){
                img_resize($file_name, $file_name, 1024,  768, 0xFFFFF0, 0);
            }else{
                img_resize($file_name, $file_name, 768,  1024, 0xFFFFF0, 0);                
            }
            unlink('../'.$oldfile); // удаляем старый файл
            unlink($temp_file_name); // удаляем временный файл
            UpdateContrAgentDocument($document_id,$name,basename($file_name),$filepath.'/',filesize($file_name));
            header("Location: ../pays.php?contragents=$contragent_id");
            
        }else {
            echo "Что-то пошло не так :-[";
            continue;
        }       
    }else{
        header("Location: ../pays.php?contragents=$contragent_id&err=filetypeerror");        
    }
}
//Обработка формы добавления платежа
if(isset($_SESSION['user_id']) && $role['create_outgoing'] == 'yes' && isset($_POST['outgoing_add_to_control'])){  
    $contragent_id = htmlspecialchars($_POST['contragent']);
    $contragent_attachment_id = htmlspecialchars($_POST['contragent_attachment']);
    if($contragent_attachment_id == null){
        header("Location: ../pays.php?contragents=$contragent_id&err=normativdocument"); 
        break;
    }
    $outgoing_amount = htmlspecialchars($_POST['outgoing_amount']);
    $outgoing_date = htmlspecialchars($_POST['outgoing_date']);
    $outgoing_date = date("Y-m-d H:i:s",  strtotime($outgoing_date));
    $drawing_id = htmlspecialchars($_POST['drawing']);
    $outgoing_osnovanie = htmlspecialchars($_POST['outgoing_osnovanie']);
    $fname =  	$_FILES['osnovanie']['name'];
    $type = 	$_FILES['osnovanie']['type'];
    $tmp_name = $_FILES['osnovanie']['tmp_name'];    
    if (!is_uploaded_file($tmp_name)) {
        header("Location: ../pays.php?pays&outgoings&err=filenotselected");        
        break;
    }
    if ($type == "image/jpeg"){        
        $file_ext = substr($fname, 1 + strrpos($name, "."));
        $file_name = substr($fname, 0, strrpos($name, "."));
        $filepath = 'docs/outgoings';
        $real_path = realpath('../'.$filepath);
        $temp_file_name = tempnam($real_path, "OUTGOINGS");
        $file_name = $temp_file_name."orig".date("Y_m_d_H_m_s").'.jpg';;
        if (move_uploaded_file($tmp_name, $file_name)) {
            require_once '../libs/image_resize.php';
            $imagedata = getimagesize($file_name);
            if($imagedata[0]>$imagedata[1]){
                img_resize($file_name, $file_name, 1024,  768, 0xFFFFF0, 0);
            }else{
                img_resize($file_name, $file_name, 768,  1024, 0xFFFFF0, 0);                
            }
            unlink($temp_file_name); // удаляем временный файл
            $id = CreateOutgoing($contragent_id, $contragent_attachment_id, $_SESSION['user_building'], $outgoing_date, $outgoing_amount, $outgoing_osnovanie, $drawing_id);
            CreateOutgoingAttachment($id, $name, basename($file_name), $filepath.'/', filesize($file_name));
            header("Location: ../pays.php?pays&outgoings");
            
        }else {
            echo "Что-то пошло не так :-[";
            continue;
        }       
    }else{
        header("Location: ../pays.php?pays&outgoings&err=filetypeerror");        
    }
}
//Обработка формы добаления выписки
if(isset($_SESSION['user_id']) && $role['create_drawing'] == 'yes' && isset($_POST['drawing_add_to_control'])){ 
    $drawing_name = htmlspecialchars($_POST['drawing_name']);
    $drawing_start_date= date("Y-m-d H:i:s",  strtotime(htmlspecialchars($_POST['drawing_start_date'])));
    $drawing_end_date= date("Y-m-d H:i:s",  strtotime(htmlspecialchars($_POST['drawing_end_date'])));
    $fname =  	$_FILES['drawing']['name'];
    $type = 	$_FILES['drawing']['type'];
    $tmp_name = $_FILES['drawing']['tmp_name'];    
    if (!is_uploaded_file($tmp_name)) {
        header("Location: ../pays.php?pays&drawings&err=filenotselected");        
        break;
    }
    if ($type == "image/jpeg"){        
        $file_ext = substr($fname, 1 + strrpos($name, "."));
        $file_name = substr($fname, 0, strrpos($name, "."));
        $filepath = 'docs/drawings';
        $real_path = realpath('../'.$filepath);
        $temp_file_name = tempnam($real_path, "DRAWINGS");
        $file_name = $temp_file_name."orig".date("Y_m_d_H_m_s").'.jpg';;
        if (move_uploaded_file($tmp_name, $file_name)) {
            require_once '../libs/image_resize.php';
            $imagedata = getimagesize($file_name);
            if($imagedata[0]>$imagedata[1]){
                img_resize($file_name, $file_name, 1024,  768, 0xFFFFF0, 0);
            }else{
                img_resize($file_name, $file_name, 768,  1024, 0xFFFFF0, 0);                
            }
            unlink($temp_file_name); // удаляем временный файл
            $id = CreateDrawing($_SESSION['user_building'],$drawing_name,$drawing_start_date,$drawing_end_date);
            CreateDrawingAttachment($id, $drawing_name, basename($file_name), $filepath.'/', filesize($file_name));
            header("Location: ../pays.php?pays&drawings");
            
        }else {
            echo "Что-то пошло не так :-[";
            continue;
        }       
    }else{
        header("Location: ../pays.php?pays&drawings&err=filetypeerror");        
    }
}
if(isset($_SESSION['user_id']) && $role['create_incoming'] == 'yes' && isset($_POST['incoming_add_to_control'])){ 
    $type = htmlspecialchars($_POST['incoming_type']);
    $type_name = htmlspecialchars($_POST['incoming_type_name']);
    if($type == 2 && $type_name == null){
        header("Location: ../pays.php?pays&outgoings&err=typenamenull");                
        break;
    }
    $amount = htmlspecialchars($_POST['incoming_amount']);
    $date = htmlspecialchars(date("Y-m-d H:i:s",strtotime($_POST['incoming_date'])));
    $query = "SELECT * FROM `ph_drawings` WHERE '{$date}' BETWEEN `start_date` AND `end_date` LIMIT 1";
    require_once 'db.php';
    $sql = DataBaseConnection($query);
    if(mysql_num_rows($sql) > 0){
        $row = mysql_fetch_assoc($sql);   
        $drawing_id = $row['id'];
        CreateIncoming($_SESSION['user_building'], $drawing_id, $type, $type_name, $amount, $date);
        header("Location: ../pays.php?pays&outgoings");                
    }else{
        header("Location: ../pays.php?pays&drawings&err=drawingnoinbase");                
        break;        
    }
}
//Обработка выхода из режима дома
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 0 && isset($_POST['quit_building'])){ 
    $_SESSION['user_building'] = 0;
    header("Location: ../adm.php");  
}
//Обработка формы добавления Улицы
if(isset($_SESSION['user_id']) && $role['create_street'] == 'yes' && isset($_POST['street_add_to_control'])){ 
    $name = htmlspecialchars($_POST['street_name']);
    CreateStreet($name);
    header("Location: ../adm.php?functions=buildings");  
}
//Обработка формы добавления новости
if(isset($_SESSION['user_id']) && $role['create_news'] == 'yes' && isset($_POST['news_add_to_control'])){ 
    $title = htmlspecialchars($_POST['news_title']);
    $text = $_POST['news_text'];
    CreateNews($title,$text,$_SESSION['user_building']);
    header("Location: ../news.php?news=def");  
}
//Обработка формы добавления Дома
if(isset($_SESSION['user_id']) && $role['create_building'] == 'yes' && isset($_POST['building_add_to_control'])){ 
    $id_street = htmlspecialchars($_POST['street_id']);
    $number = htmlspecialchars($_POST['building_number']);
    $apps = htmlspecialchars($_POST['building_apps']);
    CreateBuilding($id_street,$number,$apps);
    header("Location: ../adm.php?functions=buildings");  
}
//Обработка формы входа в режим Дома
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 0 && isset($_POST['enter_building'])){ 
    $id_building = htmlspecialchars($_POST['building_id']);
    $_SESSION['user_building'] = $id_building;
    header("Location: ../");  
}
//Обработка формы добавления Пользователя
if(isset($_SESSION['user_id']) && $role['create_user'] == 'yes' && isset($_POST['user_add_to_control'])){ 
    $id_street = htmlspecialchars($_POST['street']);
    $id_building = htmlspecialchars($_POST['building_id']);
    $role = htmlspecialchars($_POST['role']);
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $app_number = htmlspecialchars($_POST['app_number']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $third_name = htmlspecialchars($_POST['third_name']);
    $post = htmlspecialchars($_POST['post']);
    $phone1 = htmlspecialchars($_POST['phone1']);
    $phone2 = htmlspecialchars($_POST['phone2']);
    CreateUser($login,$password,$first_name,$last_name,$third_name,$app_number,$role,$id_building,$post,$phone1,$phone2);
    header("Location: ../adm.php?functions=users");  
}
//Обработка формы добавления Телефона дома
if(isset($_SESSION['user_id']) && $role['create_phone'] == 'yes' && isset($_POST['phone_add_to_control'])){ 
    $name = htmlspecialchars($_POST['phone_name']);
    $number = htmlspecialchars($_POST['phone_number']);
    $type = htmlspecialchars($_POST['type']);
    CreatePhone($_SESSION['user_building'],$name,$number,$type);
    header("Location: ../inf.php");  
}
//Обработа формы добавления документа к дому
if(isset($_SESSION['user_id']) && $role['create_building_att'] == 'yes' && isset($_POST['building_att_add_to_control'])){ 
    $id_building = $_SESSION['user_building'];
    $name = htmlspecialchars($_POST['document_name']);
    $fname =  	$_FILES['document']['name'];
    $type = 	$_FILES['document']['type'];
    $tmp_name = $_FILES['document']['tmp_name'];
    if (!is_uploaded_file($tmp_name)) {
        header("Location: ../inf.php&err=filenotselected");        
        break;
    }
    if ($type == "image/jpeg"){        
        $file_ext = substr($fname, 1 + strrpos($name, "."));
        $file_name = substr($fname, 0, strrpos($name, "."));
        $filepath = 'docs/buildings/documents';
        $real_path = realpath('../'.$filepath);
        $temp_file_name = tempnam($real_path, "DOCSBUILDINGS");
        $file_name = $temp_file_name."orig".date("Y_m_d_H_m_s").'.jpg';
        if (move_uploaded_file($tmp_name, $file_name)) {
            require_once '../libs/image_resize.php';
            $imagedata = getimagesize($file_name);
            if($imagedata[0]>$imagedata[1]){
                img_resize($file_name, $file_name, 1024,  768, 0xFFFFF0, 0);
            }else{
                img_resize($file_name, $file_name, 768,  1024, 0xFFFFF0, 0);                
            }
            unlink($temp_file_name); // удаляем временный файл
            CreateBuildingAttachment($_SESSION['user_building'],$name,basename($file_name),$filepath.'/',filesize($file_name));
            header("Location: ../inf.php");
            
        }else {
            echo "Что-то пошло не так :-[";
            continue;
        }       
    }else{
        header("Location: ../pays.php?contragents=$contragent_id&err=filetypeerror");        
    }
}
//    CreateContrAgentContact($contragent_id, $fio, $post, $phone1, $phone2, $fax, $email);
    //CreateContrAgentConnectionToBuilding($id,  mysql_insert_id());
//    header("Location: ../pays.php?contragents=$contragent_id");
?>
