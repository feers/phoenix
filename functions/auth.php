<?php
session_start();
    require_once 'db.php';
    if(!isset($_SESSION["user_id"])){
        if(isset($_COOKIE["login"]) && isset($_COOKIE["password"])){
            $link = DBConn();
            $login = mysql_real_escape_string($_COOKIE["login"]);
            $passw = mysql_real_escape_string($_COOKIE["password"]);
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
                setcookie("user_id", $id, time() + 3600);
                setcookie("login", $login, time() + 3600);
                setcookie("password", $passw, time() + 3600);
                $_SESSION['user_id'] = $id;                
                $_SESSION['login'] = $login;   
                $_SESSION['user_role'] = $role;
                $_SESSION['user_building'] = $building;
            }
            else{
                
            }
        }
    }
?>
