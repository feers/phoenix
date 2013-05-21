<?php
//обработчик ошибок    
function ErrorsControl($xtpl_body){
    if(isset($_GET['err'])){
        switch ($_GET['err']) {
            case 'filetypeerror':
                $xtpl_body->assign("error_text","Неверный тип загружаемого файла! Файл должен быть формата JPEG.");
                $xtpl_body->parse("header."."error");
                break;
            case 'filenotselected':
                $xtpl_body->assign("error_text","Вы не выбрали файл для загрузки.");
                $xtpl_body->parse("header."."error");
                break;
            case 'normativdocument':
                $xtpl_body->assign("error_text","Добавьте нормативный документ, прежде чем создавать платеж.");
                $xtpl_body->parse("header."."error");
                break;
            case 'typenamenull':
                $xtpl_body->assign("error_text","Вы не указали название для \"Прочие\"");
                $xtpl_body->parse("header."."error");
                break;
            case 'drawingnoinbase':
                $xtpl_body->assign("error_text","Не найдена выписка с введенной вами Датой, попробуйте сначала добавить выписку с правильным диапозоном Дат");
                $xtpl_body->parse("header."."error");
                break;
            case 'login_or_password_incorrect':
                $xtpl_body->parse("header."."autherr");
                break;
            default:
                break;
        }
    }
}
//Обработка роли текущего пользователя
function UserRole(){
    switch ($_SESSION['user_role']) {
        case 0:
            return array('create_contragent'    => 'yes', 'change_contragent'   => 'yes', 'delete_contragent'   => 'yes',
                        'create_outgoing'       => 'yes', 'change_outgoing'     => 'yes', 'delete_outgoing'     => 'yes',
                        'create_incoming'       => 'yes', 'change_incoming'     => 'yes', 'delete_incoming'     => 'yes',
                        'create_news'           => 'yes', 'change_news'         => 'yes', 'delete_news'         => 'yes',
                        'create_building'       => 'yes', 'change_building'     => 'yes', 'delete_building'     => 'yes',
                        'create_building_att'   => 'yes', 'change_building_att' => 'yes', 'delete_building_att' => 'yes',
                        'create_street'         => 'yes', 'change_street'       => 'yes', 'delete_street'       => 'yes',
                        'create_phone'          => 'yes', 'change_phone'        => 'yes', 'delete_phone'        => 'yes',
                        'create_user'          => 'yes', 'change_user'        => 'yes', 'delete_user'        => 'yes',
                        'create_drawing'        => 'yes', 'change_drawing'      => 'yes', 'delete_drawing'      => 'yes');
            break;
        case 1:
            return array('create_contragent'    => 'no', 'change_contragent'    => 'no', 'delete_contragent'    => 'no',
                        'create_outgoing'       => 'no', 'change_outgoing'      => 'no', 'delete_outgoing'      => 'no',
                        'create_incoming'       => 'no', 'change_incoming'      => 'no', 'delete_incoming'      => 'no',
                        'create_drawing'        => 'no', 'change_drawing'       => 'no', 'delete_drawing'       => 'no');
            break;
        case 2:
            return array('create_contragent'    => 'yes', 'change_contragent'   => 'yes', 'delete_contragent'   => 'no',
                        'create_outgoing'       => 'yes', 'change_outgoing'     => 'yes', 'delete_outgoing'     => 'no',
                        'create_incoming'       => 'yes', 'change_incoming'     => 'yes', 'delete_incoming'     => 'no',
                        'create_building'       => 'no', 'change_building'      => 'no', 'delete_building'      => 'no',
                        'create_phone'          => 'yes', 'change_phone'        => 'yes', 'delete_phone'        => 'no',
                        'create_building_att'   => 'yes', 'change_building_att' => 'yes', 'delete_building_att' => 'no',
                        'create_news'           => 'yes', 'change_news'         => 'yes', 'delete_news'         => 'no',
                        'create_drawing'        => 'yes', 'change_drawing'      => 'yes', 'delete_drawing'      => 'no');
            break;
        case 3:
            return array('create_contragent'    => 'no', 'change_contragent'    => 'no', 'delete_contragent'    => 'no',
                        'create_outgoing'       => 'no', 'change_outgoing'      => 'no', 'delete_outgoing'      => 'no',
                        'create_incoming'       => 'no', 'change_incoming'      => 'no', 'delete_incoming'      => 'no',
                        'create_drawing'        => 'no', 'change_drawing'       => 'no', 'delete_drawing'       => 'no');
            break;
    }
}
?>
