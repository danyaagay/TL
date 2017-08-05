<?php

require_once 'biblio/vkapi.php';
require_once 'tech/settings.php';
require_once 'modules/searchmod.php';

//Получаем и декодируем уведомление 
$data = json_decode(file_get_contents('php://input')); 

//Проверяем, что находится в поле "type" 
switch ($data->type) { 
  //Если это уведомление для подтверждения адреса... 
  case 'confirmation': 
    //...отправляем строку для подтверждения 
    echo $confirmation_token; 
    break; 

  //Если это уведомление о новом сообщении... 
  case 'message_new':
    $user_id = $data->object->user_id;
    $row = mysqli_fetch_array($mysqli->query("SELECT * FROM actos WHERE vk_id = $user_id"));
    $sow = mysqli_fetch_array($mysqli->query("SELECT * FROM replis WHERE usr_id = $user_id"));
    if ($row["vk_id"] != $user_id) {
    	$mysqli->query("INSERT INTO actos VALUES ('', $user_id, 0, 0, 0, 0, 1) ");
    } else {
      get_answer($message, $user_id, $token, $data, $mysqli, $row, $ustoken, $sow);
    }

break;

}