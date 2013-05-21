<?php
require_once 'functions/logic_start.php';
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 0){
    if(isset($_SESSION['user_id']) && isset($_GET['functions'])){
        switch($_GET['functions']){
            case 'buildings':
                $xtpl_body->assign('form_name','add_street');            
                $xtpl_body->assign('button_name','Добавить Улицу');  
                $xtpl_body->parse('body.create');  
                $xtpl_body->assign('form_name','add_building');            
                $xtpl_body->assign('button_name','Добавить Дом');  
                $xtpl_body->parse('body.create');  
                break;
            case 'users':
                $xtpl_body->assign('form_name','add_user');            
                $xtpl_body->assign('button_name','Добавить Пользователя');  
                $xtpl_body->parse('body.create');  
                $xtpl_body->assign('form_name','add_users');            
                $xtpl_body->assign('button_name','Добавить Пользователей');  
                $xtpl_body->parse('body.create');  
                $xtpl_body->assign('form_name','del_user');            
                $xtpl_body->assign('button_name','Удалить Пользователя');  
                $xtpl_body->parse('body.create');  
                break;
            case 'building_enter':
                $xtpl_body->assign('form_name','enter_building');            
                $xtpl_body->assign('button_name','Выбрать дом');  
                $xtpl_body->parse('body.create');  
                break;
        }
    }
}
require_once 'functions/logic_end.php';
?>
