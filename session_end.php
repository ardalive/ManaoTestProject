<?php
session_start();
use app\XmlManager;
include 'class\XmlManager.php';

$xml = new XmlManager('database/database.xml');
$user = $xml->getUserIndexBySessionId(session_id());

$xml->removeToken($user);
$xml->removeSessionId($user);
unset($_SESSION['name']);
header("Location: http://localhost/index.php");
exit();