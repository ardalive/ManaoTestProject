<?php
use app\XmlManager;
include 'class/XmlManager.php';

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    $login = $_POST['login'];
    $password = $_POST['password'];

    $response = array();
    $loginErrorMessages = array(
        'login' => 'User with such login is not found',
        'login_empty' => 'Please input your login',
        'password' => 'Please check your password',
        'password_empty' => 'Please input your password'
    );

    if (strlen($_POST['login']) < 1)
        $response['login'] = $loginErrorMessages['login_empty'];

    if (strlen($_POST['password']) < 1)
        $response['password'] = $loginErrorMessages['password_empty'];

    if (count($response) < 1) {
        $xml = new XmlManager('database/database.xml');
        $loginArray = $xml->getValuesByTagName('login');

        $loginIndex = array_search($login, $loginArray);

        if(!$loginIndex) $response['login'] = $loginErrorMessages['login'];
        else{
            $databasePassword = $xml->getValuesByTagName('password')[$loginIndex];
            $salt = trim($xml->getValuesByTagName('salt')[0]);
            if($databasePassword !== passwordCrypt($password, $salt)) $response['password'] = $loginErrorMessages['password'];
            else{
                $user = $xml->getUserByIndex($loginIndex);
                $token = $xml->insertToken($user);
                $xml->insertSessionId($user, session_id());
                $_SESSION['name'] = $login;
                setcookie('token', $token, time()+3600);
                $response['success'] = true;
            }
        }

    }
    echo json_encode($response);
}

function passwordCrypt($password, $salt){
    return crypt($password, $salt);
}