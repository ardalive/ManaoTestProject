<?php
use app\XmlManager;
include 'class\XmlManager.php';

include 'header.html';

$xml = new XmlManager('database/database.xml');

if(!isset($_SESSION['name'])){
        include 'register.html';
        include 'login.html';
}
if(isset($_COOKIE['token']) && $xml->tokenIsValid($_COOKIE['token']) !== false && isset($_SESSION['name'])){
    include 'logout.php';
}

include 'footer.html';
