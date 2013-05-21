<?php
require_once 'functions/logic_start.php';
$role = UserRole();
        //print_r($_GET['news']);
        //print_r($_SESSION['user_role']);
if(isset($_SESSION['user_id']) && $_SESSION['user_building'] != 0 && isset($_GET['news'])){
    switch ($_GET['news']) {
        case 'all':
            $query = "SELECT * FROM `ph_news` WHERE `id_building` = '{$_SESSION['user_building']}'
            ORDER BY `reg_date` DESC";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('news_title',$row['title']);
                $xtpl_body->assign('news_text',$row['text']);
                $xtpl_body->assign('news_date',date("d.m.y H:m",strtotime($row['reg_date'])));
                $xtpl_body->parse('body.news');
            }
            break;

            case 'def':
            $query = "SELECT * FROM `ph_news` WHERE `id_building` = '{$_SESSION['user_building']}'
            ORDER BY `reg_date` DESC LIMIT 5";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('news_title',$row['title']);
                $xtpl_body->assign('news_text',$row['text']);
                $xtpl_body->assign('news_date',date("d.m.y H:m",strtotime($row['reg_date'])));
                $xtpl_body->parse('body.news');
            }
            $xtpl_body->parse('body.news_can');
            break;
            
        default:
            $query = "SELECT * FROM `ph_news` WHERE `id_building` = '{$_SESSION['user_building']}' AND `id` = '{$_GET['news']}'";
            $sql = DataBaseConnection($query);
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('news_title',$row['title']);
                $xtpl_body->assign('news_text',$row['text']);
                $xtpl_body->assign('news_date',date("d.m.y H:m",strtotime($row['reg_date'])));
                $xtpl_body->parse('body.news');
                $xtpl_body->parse('body.news_can');
            }
            break;
    }
    if($role['create_news'] == 'yes'){
            $xtpl_body->assign('button_name','Добавить Новость');            
            $xtpl_body->assign('form_name','add_news');            
            $xtpl_body->parse('body.create');
        }

}

require_once 'functions/logic_end.php';
?>
