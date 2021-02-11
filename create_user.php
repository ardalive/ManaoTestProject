<?php
use app\XmlManager;
use app\Validator;
include 'class/XmlManager.php';
include 'class/Validator.php';

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    $credentials = array(
        'login' => $_POST['login'],
        'password' => $_POST['password'],
        'confirm' => $_POST['confirm'],
        'email' => $_POST['email'],
        'name' => $_POST['name'],
    );

    $validationErrorMessages = array(
        'invalidLoginMessage' => 'Login must be at least 6 symbols long, letters and numbers.',
        'invalidPasswordMessage' => 'Passwords must match. At least 6 characters long, at least one upper- and lowercase letter, a number and a symbol.',
        'invalidEmailMessage' => 'Please provide a valid email.',
        'invalidNameMessage' => 'Name must be 2 characters or longer, letters and numbers.',
        'loginInUseMessage' => 'Login is already in use.',
        'emailInUseMessage' => 'Email is already in use.'
    );

    $xml = new XmlManager('database/database.xml');
    $validator = new Validator($credentials);

    $response = $validator->validate($xml->getValuesByTagName('login'), $xml->getValuesByTagName('email'), $validationErrorMessages);

    if (count($response) < 1) {
        $response['success'] = true;
        $xml->createUser($credentials);
    }
    echo json_encode($response);
}