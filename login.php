<?php
session_start();
require_once 'functions/db.php';
if (isset($_POST['login']) && $_POST['password']){
    $link = DBConn();
    $login = mysql_real_escape_string($_POST['login']);
    $passw = mysql_real_escape_string($_POST['password']);
    DBClos($link);
    $query = "SELECT `salt` FROM `ph_users` WHERE `login`='{$login}' LIMIT 1";
    $sql = DataBaseConnection($query);
    if (mysql_num_rows($sql) == 1){
        $row = mysql_fetch_assoc($sql);
        $salt = $row['salt'];
        $passwdb = md5(md5($passw.$salt));
        $query = "SELECT `id`,`id_role`,`id_building` FROM `ph_users` WHERE `login` = '{$login}' AND `password` = '{$passwdb}'";
        $sql = DataBaseConnection($query);
        $result = mysql_fetch_assoc($sql);
        $id = $result['id'];
        $role = $result['id_role'];
        $building = $result['id_building'];
        setcookie("login", $login, time() + 3600);
        setcookie("password", $passw, time() + 3600);
        setcookie("user_id", $id, time() + 3600);
        $_SESSION['user_id'] = $id;                
        $_SESSION['login'] = $login;                
        $_SESSION['user_role'] = $role;
        $_SESSION['user_building'] = $building;
        header('Location: /');
    }  
    else{
        header('Location: /?err=login_or_password_incorrect');        
    }
}else{
    header('Location: /?err=login_and_password_need');  
}
if(isset($_REQUEST['quit'])) { 
        setcookie("login", $login, time() - 3600);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        unset($_COOKIE['login']);
        unset($_COOKIE['password']);
        unset($_COOKIE['user_id']);
        session_destroy();
        header('Location: /');
    }  
?>
