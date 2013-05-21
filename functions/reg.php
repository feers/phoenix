<?php
session_start();
//Создание пользователя
//$idrole
//0 - Администратор
//1 - Пользователь(житель)
//2 - ТСЖ(житель)
//3 - Муниципалитет
function CreateUser($login,$password,$fname,$lname,$tname,$appnum,$idrole,$idbuilding,$post,$phone1,$phone2){
    require_once 'db.php';
    if($idrole == 0){
        $idbuilding = 0;
    }
    $salt = rand(100, 999);
    $passworddb = md5(md5($password.$salt));
    $regdate = date("Y-m-d H:i:s");
    if($post == null){
        switch ($idrole) {
            case 0:
                $post = 'Администратор';
                break;
            case 1:
                $post = 'Житель';
                break;
            case 2:
                $post = 'Управляющий домом';
                break;
            case 3:
                $post = 'Муниципалитет';
                break;
        }
    }
    $query = "INSERT INTO `ph_users` (`login`,`password`,`salt`,`first_name`,`last_name`,`third_name`,`app_number`,`id_role`,`reg_date`,`change_date`,`id_building`,`post`,`phone1`,`phone2`) 
    VALUES ('{$login}','{$passworddb}','{$salt}','{$fname}','{$lname}','{$tname}','{$appnum}','{$idrole}','{$regdate}','{$regdate}','{$idbuilding}','{$post}','{$phone1}','{$phone2}')";
    DataBaseConnection($query);
}
//Создание Дома
function CreateBuilding($idstreet,$number,$appcount){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_buildings` (`id_street`,`number`,`app_count`,`reg_date`,`change_date`) 
    VALUES ('{$idstreet}','{$number}','{$appcount}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);    
}
//Создание Улицы
function CreateStreet($name){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_streets` (`name`,`reg_date`,`change_date`) 
    VALUES ('{$name}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);    
}
//Моздание Телефона
function CreatePhone($id_building,$name,$number,$type){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_phones` (`name`,`number`,`type`,`id_building`,`reg_date`,`change_date`)
    VALUES ('{$name}','{$number}','{$type}','{$id_building}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);
}
//Создание форума
function CreateForum($id_building,$name,$desc){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_forums` (`name`,`desc`,`id_building`,`reg_date`,`change_date`)
    VALUES ('{$name}','{$desc}','{$id_building}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);
}
//Создание Топика для Форума
function CreateTopic($id_forum,$title,$first_poster_name,$first_poster_id,$first_post_id,$first_post_date){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_forums_topics` (`id_forum`,`title`,`first_poster_name`,`first_poster_id`,`first_post_id`,
    `first_post_date`,`reg_date`,`change_date`,`last_poster_name`,`last_poster_id`,`last_post_id`,`last_post_date`)
    VALUES ('{$id_forum}','{$title}','{$first_poster_name}','{$first_poster_id}','{$first_post_id}','{$first_post_date}',
    '{$regdate}','{$regdate}','{$first_poster_name}','{$first_poster_id}','{$first_post_id}','{$first_post_date}')";
    DataBaseConnection($query);
}
//Создание Поста для Топика Форума
function CreatePost($id_topic,$id_forum,$id_poster,$attachment,$post_text){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_forums_posts` (`id_topic`,`id_forum`,`id_poster`,`post_date`,`attachment`,`post_text`)
    VALUES ('{$id_topic}','{$id_forum}','{$id_poster}','{$regdate}','{$attachment}','{$post_text}')";
    DataBaseConnection($query);
}
//Создать Новость
function CreateNews($title,$text,$id_building){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_news` (`title`,`text`,`reg_date`,`id_building`)
    VALUES ('{$title}','{$text}','{$regdate}','{$id_building}')";
    DataBaseConnection($query);
}
//Создание КонтрАгента
//$type: 1 - комунальные 2 - некомунальные
function CreateContrAgent($name,$type,$inn,$kpp,$yur_adres,$fact_adres,$email,$account_number,$bank_name,$bank_bik,$bank_kor,$information){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_contragents` (`name`,`type`,`inn`,`kpp`,`yur_adres`,`fact_adres`,`email`,`account_number`,`bank_name`,`bank_bik`,
    `bank_kor`,`information`,`reg_date`,`change_date`)
    VALUES ('{$name}','{$type}','{$inn}','{$kpp}','{$yur_adres}','{$fact_adres}','{$email}','{$account_number}','{$bank_name}','{$bank_bik}',
    '{$bank_kor}','{$information}','{$regdate}','{$regdate}')";
    $link = DBConn();
    mysql_query($query) or die(mysql_error());
    CreateContrAgentConnectionToBuilding($_SESSION['user_building'],  mysql_insert_id());
    DBClos($link);
}
//Создание ссылки КонтрАгента на Дом(привязка)
function CreateContrAgentConnectionToBuilding($building_id,$contragent_id){
    require_once 'db.php';
    $query = "INSERT INTO `ph_contragents_buildings` (`id_contragent`,`id_building`)
    VALUES ('{$contragent_id}','{$building_id}')";
    DataBaseConnection($query);
}
//Создание Контактного Лица для КонтрАгента
function CreateContrAgentContact($id_contragent,$id_building,$fio,$post,$phone1,$phone2,$fax,$email){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_contragents_contacts` (`id_contragent`,`id_building`,`fio`,`post`,`phone1`,`phone2`,`fax`,`email`,`reg_date`,`change_date`)
    VALUES ('{$id_contragent}','{$id_building}','{$fio}','{$post}','{$phone1}','{$phone2}','{$fax}','{$email}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);
}
//Создание скан-копии документа с прикреплением к контрагенту
function CreateContrAgentAttachment($id_contragent,$id_building,$name,$filename,$path,$size){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_contragents_attachments` (`id_contragent`,`id_building`,`name`,`filename`,`path`,`size`,`reg_date`,`change_date`)
    VALUES ('{$id_contragent}','{$id_building}','{$name}','{$filename}','{$path}','{$size}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);
}
//Создание Платежа
function CreateOutgoing($id_contragent,$id_contragent_doc,$id_building,$outgoing_date,$amount,$osnovanie,$id_drawing){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");    
    $query = "INSERT INTO `ph_outgoings` (`id_contragent`,`id_contragent_doc`,`id_building`,`outgoing_date`,`amount`,`osnovanie`,`id_drawing`,`reg_date`,`change_date`)
    VALUES ('{$id_contragent}','{$id_contragent_doc}','{$id_building}','{$outgoing_date}','{$amount}','{$osnovanie}','{$id_drawing}','{$regdate}','{$regdate}')";    
    $link = DBConn();
    mysql_query($query) or die(mysql_error());
    return mysql_insert_id($link);
    DBClos($link);
}
//Создание нормативного документа для платежа
function CreateOutgoingAttachment($id_outgoing,$name,$filename,$path,$size){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");    
    $query = "INSERT INTO `ph_outgoings_attachments` (`id_outgoing`,`name`,`filename`,`path`,`size`,`reg_date`,`change_date`)
    VALUES ('{$id_outgoing}','{$name}','{$filename}','{$path}','{$size}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);        
}
//Создание выписки из Банка
function CreateDrawing($id_building,$name,$start_date,$end_date){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");    
    $query = "INSERT INTO `ph_drawings` (`id_building`,`name`,`start_date`,`end_date`,`reg_date`,`change_date`)
    VALUES ('{$id_building}','{$name}','{$start_date}','{$end_date}','{$regdate}','{$regdate}')";
    $link = DBConn();
    mysql_query($query) or die(mysql_error());
    return mysql_insert_id($link);
    DBClos($link); 
}
//Создание скан-копии с прикреплением к выписке
function CreateDrawingAttachment($id_drawing,$name,$filename,$path,$size){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");    
    $query = "INSERT INTO `ph_drawings_attachments` (`id_drawing`,`name`,`filename`,`path`,`size`,`reg_date`,`change_date`)
    VALUES ('{$id_drawing}','{$name}','{$filename}','{$path}','{$size}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);          
}
//Создание Прихода
function CreateIncoming($id_building,$id_drawing,$type,$name,$amount,$incoming_date){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");    
    if($type == 1) $name = 'Коммунальные платежи';
    $query = "INSERT INTO `ph_incomings` (`id_building`,`id_drawing`,`type`,`name`,`amount`,`income_date`,`reg_date`,`change_date`)
    VALUES ('{$id_building}','{$id_drawing}','{$type}','{$name}','{$amount}','{$incoming_date}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);    
}
//Создание документа для дома
function CreateBuildingAttachment($id_building,$name,$filename,$path,$size){
    require_once 'db.php';
    $regdate = date("Y-m-d H:i:s");
    $query = "INSERT INTO `ph_buildings_attachments` (`id_building`,`name`,`filename`,`path`,`size`,`reg_date`,`change_date`)
    VALUES ('{$id_building}','{$name}','{$filename}','{$path}','{$size}','{$regdate}','{$regdate}')";
    DataBaseConnection($query);
}
//CreateIncoming(1, 1, 1, 'Дворнику', '10000,54', date("Y-m-d H:i:s"));
//CreateOutgoingAttachment(1, "Март", "test.jpg", "docs/outgoings/", 3000);
//CreateDrawingAttachment(1, "Март", "test.jpg", "docs/drawings/", 3000);
//CreateOutgoing(1, 1, 1, date("Y-m-d H:i:s"), 12200.69, 'СЧЕТ №23 от 12.01.2012 за холодную воду', 1);
//CreateDrawing(1, "Март", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));


//-------------------------------------------------------------------------------------------------//
//Изменение данных в БД
//
//Изменение КонтрАгента
function UpdateContrAgent($id,$name,$type,$inn,$kpp,$yur,$fact,$email,$account,$bankname,$kor,$information){
    require_once 'db.php';
    $change_date = date("Y-m-d H:i:s");
    $query = "UPDATE `ph_contragents` SET 
    `name` = '{$name}',
    `type` = '{$type}',
    `inn` = '{$inn}',
    `kpp` = '{$kpp}',
    `yur_adres` = '{$yur}',
    `fact_adres` = '{$fact}',
    `email` = '{$email}',
    `account_number` = '{$account}',
    `bank_name` = '{$bankname}',
    `bank_bik` = '{$kor}',
    `information` = '{$information}',
    `change_date` = '{$change_date}'
    WHERE `id` = '{$id}'";
    DataBaseConnection($query);
}
//Изменение контакта
function UpdateContrAgentContact($id,$fio,$post,$phone1,$phone2,$fax,$email){
    require_once 'db.php';
    $change_date = date("Y-m-d H:i:s");
    $query = "UPDATE `ph_contragents_contacts` SET 
    `fio` = '{$fio}',
    `post` = '{$post}',
    `phone1` = '{$phone1}',
    `phone2` = '{$phone2}',
    `fax` = '{$fax}',
    `email` = '{$email}',
    `change_date` = '{$change_date}'
    WHERE `id` = '{$id}'";
    DataBaseConnection($query);
}
function UpdateContrAgentDocument($document_id,$name,$filename,$filepath,$size){
    require_once 'db.php';
    $change_date = date("Y-m-d H:i:s");
    if($filename != null){
        $query = "UPDATE `ph_contragents_attachments` SET 
        `name` = '{$name}',    
        `filename` = '{$filename}',
        `path` = '{$filepath}',
        `size` = '{$size}',
        `change_date` = '{$change_date}'
        WHERE `id` = '{$document_id}'";
    }else{
        $query = "UPDATE `ph_contragents_attachments` SET 
        `name` = '{$name}',  
        `change_date` = '{$change_date}'
        WHERE `id` = '{$document_id}'";        
    }
    DataBaseConnection($query);
}
//CreateContrAgentAttachment(1, 'Договор', 'test.jpg', 'docs/contragents/', '26546546');
//CreateContrAgentContact(1, 'Мухлин Роман Николаевич', 'Генеральный директор', '89600348080', "22461148", "2246148", "feers@inbox.ru");
//CreateContrAgent('ООО "Напорище"', 1, '16555557', '165501', '420101, г. Казань, ул. Карбышева, д. 29', '420101, г. Казань, ул. Карбышева, д. 29',
//'feers@inbox.ru', '1234560000001231', 'Сбербанк', '123132132', '132132400000123101', 'Информация о контрагенте... занимается всякой всячиной');
//CreateNews('Новость', "Новости это хорошо и лучше",1);
//CreatePost(2, 1, 3, NULL, "Проверка поста");
//CreateTopic(1, Обсуждаем, "Мухлин Роман", 3, 1, date("Y-m-d H:i:s"));
//CreateForum(1, "Обсуждение постановления", "Обсуждаются постановления");
//CreatePhone(1, "ТСЖ", "8-9600-34-80-83", 2);
//CreateStreet("Аделя Кутуя");
//CreateBuilding("1", "29", "36");
//CreateUser("test_tsj", "123456", "Роман", "Мухлин", "Николаевич", 0, 2, 0);
//print_r(date("Y-m-d H:i:s"));

//Проверки различные
//Проеверка на ИНН Контрагента
function CheckContrAgentINN($inn){
    require_once 'db.php';
    $query = "SELECT `id`,`inn` FROM `ph_contragents` WHERE `inn` = '{$inn}' LIMIT 1";
    $sql = DataBaseConnection($query);
    if(mysql_num_rows($sql) > 0){
        $row = mysql_fetch_assoc($sql);
        return $row['id'];
    }else{
        return null;
    }    
}
?>
