<?php
session_start();
    require_once 'functions/functions.php'; //Подключаем модуль функций
    require_once 'functions/auth.php';
    require_once 'functions/gets.php';
    include('libs/xtpl/xtemplate.class.php');
    //Подключение шаблонов в зависимости от скриптов
    switch ($_SERVER['SCRIPT_NAME']) {
    //Обработчик заглавной страницы ----------------------------------- начало
    case '/index.php':
            $xtpl_body = new XTemplate('templates/index.html');
            $xtpl_body->assign_file('body','templates/main/body.html');  
            $xtpl_body->assign_file('news','templates/main/body.news.html');  
            break;
    case '/inf.php':
            $xtpl_body = new XTemplate('templates/index.html');
            $xtpl_body->assign_file('body','templates/inf/inf.html');    
            break;
    //Обработчик заглавной страницы ----------------------------------- конец
    //Обработчик новостей ----------------------------------- начало
    case '/news.php':
            $xtpl_body = new XTemplate('templates/index.html');
            $xtpl_body->assign_file('body','templates/news/news.html'); 
            break;
    //Обработчик новостей ----------------------------------- конец
    //Обработчик платежей ----------------------------------- начало
    case '/pays.php':
            $xtpl_body = new XTemplate('templates/index.html');
            if(isset($_GET['contragents'])){
                $xtpl_body->assign_file('body','templates/pays/contragents.html'); 
            }
            elseif(isset($_GET['pays'])){
                $xtpl_body->assign_file('body','templates/pays/pays.html');                 
            }
            else{
                $xtpl_body->assign_file('body','templates/pays/buttons.html');                
            }
            break;
    //Обработчик платежей ----------------------------------- конец
    //Обработчик форм ----------------------------------- начало
    case '/forms.php':
            $xtpl_body = new XTemplate('templates/index.html');
            switch ($_POST['form_name']) {
                //Форма изменения контрагента ----------------------------------- начало
                case 'change_contragent':
                    $xtpl_body->assign_file('body','templates/pays/contragents/forms/contragent_change_add.html'); 
                    break;
                //Форма изменения контрагента ----------------------------------- конец
                //Форма добавления контрагента ----------------------------------- начало
                case 'add_contragent':
                    $xtpl_body->assign_file('body','templates/pays/contragents/forms/contragent_change_add.html'); 
                    break;
                //Форма добаления контрагента ----------------------------------- конец
                case 'add_user':
                    $xtpl_body->assign_file('body','templates/adm/forms/user_change_add.html'); 
                    break;
                case 'add_contact':
                    $xtpl_body->assign_file('body','templates/pays/contragents/forms/contact_change_add.html'); 
                    break;
                case 'add_outgoing':
                    $xtpl_body->assign_file('body','templates/pays/outgoings/forms/outgoing_change_add.html'); 
                    break;
                case 'add_drawing':
                    $xtpl_body->assign_file('body','templates/pays/drawings/forms/drawing_change_add.html'); 
                    break;
                case 'add_incoming':
                    $xtpl_body->assign_file('body','templates/pays/incomings/forms/incoming_change_add.html'); 
                    break;
                case 'add_street':
                    $xtpl_body->assign_file('body','templates/adm/forms/street_change_add.html'); 
                    break;
                case 'add_building':
                    $xtpl_body->assign_file('body','templates/adm/forms/building_change_add.html'); 
                    break;
                case 'add_news':
                    $xtpl_body->assign_file('body','templates/news/forms/news_change_add.html'); 
                    break;
                case 'add_document':
                    $xtpl_body->assign_file('body','templates/pays/contragents/forms/document_change_add.html'); 
                    break;
                case 'add_phone':
                    $xtpl_body->assign_file('body','templates/inf/forms/phone_change_add.html'); 
                    break;
                case 'add_building_att':
                    $xtpl_body->assign_file('body','templates/inf/forms/attacmnets_change_add.html'); 
                    break;
                case 'change_contact':
                    $xtpl_body->assign_file('body','templates/pays/contragents/forms/contact_change_add.html'); 
                    break;
                case 'change_document':
                    $xtpl_body->assign_file('body','templates/pays/contragents/forms/document_change_add.html'); 
                    break;
                case 'enter_building':
                    $xtpl_body->assign_file('body','templates/adm/forms/enter_building.html'); 
                    break;
                }
                break;
    //Обработчик форм ----------------------------------- конец
       case '/adm.php':
            $xtpl_body = new XTemplate('templates/index.html');
            switch ($_GET['functions']) {
                case 'buildings':
                    $xtpl_body->assign_file('body','templates/adm/buildings.html'); 
                    break;
                case 'users':
                    $xtpl_body->assign_file('body','templates/adm/buildings.html'); 
                    break;
                case 'building_enter':
                    $xtpl_body->assign_file('body','templates/adm/buildings.html'); 
                    break;
            }
            break;
    default:
        $xtpl_body = new XTemplate('templates/index.html');
        $xtpl_body->assign_file('body','templates/main/body.html');  
        $xtpl_body->assign_file('news','templates/main/body.news.html');  
        break;
    }
        
    //Подключение шаблона основной части
    $arr = array(1,2); //Пользователи и ТСЖ
    $ara = array(0,3); //Администрация и Муниципалитет
    $xtpl_body->assign_file('head','templates/head.html');  
    $xtpl_body->assign_file('header','templates/header.html');
    $xtpl_body->assign_file('phones','templates/header.phones.html');
    $xtpl_body->assign_file('auth','templates/header.auth.html');
    $xtpl_body->assign_file('header_navi','templates/header.navi.html');
    $xtpl_body->assign_file('footer','templates/footer.html');
    $xtpl_body->assign('title',GetTitle());
    
    //Формирование главного меню для Пользователя(Жильца) и ТСЖ
    if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0){
        //Новости
        $xtpl_body->assign('href','/news.php?news=def');
        $xtpl_body->assign('item','Новости');
        $xtpl_body->parse('header.navi.menu_item');
        //Платежи
        $xtpl_body->assign('href','/pays.php');
        $xtpl_body->assign('item','Платежи');
        $xtpl_body->parse('header.navi.menu_item');
        //Тендер
        $xtpl_body->assign('href','/#');
        $xtpl_body->assign('item','Тендер');
        $xtpl_body->parse('header.navi.menu_item');
        //Форум
        $xtpl_body->assign('href','/#');
        $xtpl_body->assign('item','Форум');
        $xtpl_body->parse('header.navi.menu_item');
        //О Доме
        $xtpl_body->assign('href','/inf.php');
        $xtpl_body->assign('item','О доме');
        $xtpl_body->parse('header.navi.menu_item');
    }
    if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0 && $_SESSION['user_building'] == 0){
        //Новости
        $xtpl_body->assign('href','/news.php?news=def');
        $xtpl_body->assign('item','Новости');
        $xtpl_body->parse('header.navi.menu_item');
        //Платежи
        $xtpl_body->assign('href','/pays.php');
        $xtpl_body->assign('item','Платежи');
        $xtpl_body->parse('header.navi.menu_item');
        //Тендер
        $xtpl_body->assign('href','/#');
        $xtpl_body->assign('item','Тендер');
        $xtpl_body->parse('header.navi.menu_item');
        //Форум
        $xtpl_body->assign('href','/#');
        $xtpl_body->assign('item','Форум');
        $xtpl_body->parse('header.navi.menu_item');
        //О Доме
        $xtpl_body->assign('href','/inf.php');
        $xtpl_body->assign('item','О доме');
        $xtpl_body->parse('header.navi.menu_item');
    }elseif(isset($_SESSION['user_id']) && $_SESSION['user_building'] == 0){
        //Дома
        $xtpl_body->assign('href','/adm.php?functions=buildings');
        $xtpl_body->assign('item','Дома');
        $xtpl_body->parse('header.navi.menu_item');
        //Пользователи
        $xtpl_body->assign('href','/adm.php?functions=users');
        $xtpl_body->assign('item','Пользователи');
        $xtpl_body->parse('header.navi.menu_item');
        //Вход в режим дома
        $xtpl_body->assign('href','/adm.php?functions=building_enter');
        $xtpl_body->assign('item','Вход в режим дома');
        $xtpl_body->parse('header.navi.menu_item');
        }
    
    //Заполнение шаблона телефонами дома не для администратоции и муниципалитета
    //Получение списка Телефонов для данного жильца 
    if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0){
        $xtpl_body->parse('header.can_phones');        
        $query = "SELECT * FROM `ph_users` LEFT JOIN `ph_phones` ON ph_users.id_building = ph_phones.id_building 
        WHERE ph_users.id = '{$_SESSION['user_id']}' AND ph_phones.type = 1";
        $sql = DataBaseConnection($query);
        while($row = mysql_fetch_assoc($sql)){
            $xtpl_body->assign('pname',$row['name']);
            $xtpl_body->assign('pnumber',$row['number']);        
            $xtpl_body->parse('header.phones');    
        }
    }
    
    //Получение списка Факсов для данного жильца    
    if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0){
        $xtpl_body->parse('header.can_fax');
        $query = "SELECT * FROM `ph_users` LEFT JOIN `ph_phones` ON ph_users.id_building = ph_phones.id_building 
        WHERE ph_users.id = '{$_SESSION['user_id']}' AND ph_phones.type = 2";
        $sql = DataBaseConnection($query);
        while($row = mysql_fetch_assoc($sql)){
            $xtpl_body->assign('fname',$row['name']);
            $xtpl_body->assign('fnumber',$row['number']);    
            $xtpl_body->parse('header.fax');
        }
    }
    
    //Вывод ошибок
    ErrorsControl($xtpl_body);
    
    //Авторизация
    if(!isset($_SESSION['user_id'])){
        $xtpl_body->parse('header.auth');
    }
    else{
        if($_SESSION['user_role'] == 0 && $_SESSION['user_building'] != 0){
            $xtpl_body->parse('header.quit_building');            
        }
       $xtpl_body->assign('login',$_SESSION['login']);
       $xtpl_body->parse('header.welcome');        
    }
?>
