<? 
require_once 'functions/logic_start.php';
    
    //Показ Новостей
    if(isset($_SESSION['user_id']) && $_SESSION['user_building != 0']){
        $query = "SELECT * FROM `ph_users` LEFT JOIN `ph_news` on ph_users.id_building = ph_news.id_building
        WHERE ph_users.id = '{$_SESSION['user_id']}' ORDER BY ph_news.reg_date DESC LIMIT 3";
        $sql = DataBaseConnection($query);
            $xtpl_body->parse('body.news_can');
        if (mysql_num_rows($sql)>0){
            while($row = mysql_fetch_assoc($sql)){
                $xtpl_body->assign('date',date("d.m.y",strtotime($row['reg_date'])));        
                $xtpl_body->assign('text',$row['title']);        
                $xtpl_body->assign('news_id',$row['id']);        
                $xtpl_body->parse('body.news');
            }
        }
        else{
            $xtpl_body->assign('date',date("d.m.y"));        
            $xtpl_body->assign('text','Новости и объявеления для вашего дома пока отсутсвуют');        
            $xtpl_body->parse('body.news');                
        }
    }
    
    
require_once 'functions/logic_end.php';
?>

