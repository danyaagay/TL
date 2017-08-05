<?php

if (!isset($_REQUEST)) { 
  return; 
} 

//Строка для подтверждения адреса сервера из настроек Callback API 
$confirmation_token = '211f32b0'; 

//Ключ доступа сообщества 
$token = '';
//Ключ доступа пользователя
$ustoken = '';
//ИД группы
$groupid = 123456789;

//анные базы
$mysqli = new mysqli("localhost", "Magist", "12345", "Magist");
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}