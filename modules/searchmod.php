<?php

require_once 'biblio/vkapi.php';
require_once 'biblio/levinshtein.php';

$commnd = array("Девушку", "девушку", "Парня", "парня", "Заного", "заного");
$girl = array("Девушку", "девушку", "Дев", "дев", "Дв", "дв");
$ages = array('14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45');
$boy = array("Парня", "парня", "Пр", "пр");
$refresh = array("Ещё", "ещё", "Еще", "еще", "Еше", "еше");
$del = array("Заного", "заного");

function search($row, $mysqli, $ustoken, $user_id, $token, $sow){
  user_search($row, $mysqli, $ustoken, $sow);
  global $id, $count, $name, $familia, $photo, $set;
  if ($count == 0) {
    $message = "Мы не нашли некого из этого города. Вернитесь позже!";
    massage_send($message, $user_id, $token, $attachment);
  }
  if ($sow["usr_id"] != $user_id) {
    $mysqli->query("INSERT INTO replis VALUES ('', $user_id, 0) ");
    $mysqli->query("UPDATE replis SET iskaemoe = $count WHERE usr_id = $user_id");
    $sow = mysqli_fetch_array($mysqli->query("SELECT * FROM replis WHERE usr_id = $user_id"));
  }
  if ($sow["iskaemoe"]>$count) {
    $mysqli->query("UPDATE replis SET iskaemoe = $count WHERE usr_id = $user_id");
    $sow = mysqli_fetch_array($mysqli->query("SELECT * FROM replis WHERE usr_id = $user_id"));
  }
  if (!$count == 0 and $sow["iskaemoe"] <= 0) {
      $mysqli->query("UPDATE replis SET iskaemoe = $count WHERE usr_id = $user_id");
      $sow = mysqli_fetch_array($mysqli->query("SELECT * FROM replis WHERE usr_id = $user_id"));
      $message = "Мы обновили анкеты.";
      massage_send($message, $user_id, $token, $attachment);
    }
  if (!$count == 0 and $sow["iskaemoe"] !== 0) {
    $set = $sow["iskaemoe"] - 1;
    user_search($row, $mysqli, $ustoken, $sow);
    $message = "{$name} {$familia} vk.com/id{$id}";
    $mysqli->query("UPDATE replis SET iskaemoe = $set WHERE usr_id = $user_id");
    $sow = mysqli_fetch_array($mysqli->query("SELECT * FROM replis WHERE usr_id = $user_id"));
    $attachment = $photo;
    massage_send($message, $user_id, $token, $attachment);
  }
}

function get_answer($message, $user_id, $token, $data, $mysqli, $row, $ustoken, $sow) {
  $body = $data->object->body;
  global $girl, $boy, $commnd, $ages, $country, $refresh, $del;
  if (in_array($body, $refresh) and $row["gender"] == 1 and $row["age"] != 0 and $body != "заного" and $row["search_act"] == 1 or $row["gender"] == 2 and $row["age"] != 0 and $body != "заного" and $row["search_act"] == 1 and in_array($body, $refresh)) {
    sleep(1);
    search($row, $mysqli, $ustoken, $user_id, $token, $sow);
  }
  if (!in_array(levinshtein($body, $commnd), $del) and !in_array($body, $refresh) and !in_array($body, $girl) and !in_array($body, $boy) and $row["gender"] == 1 and $row["age"] != 0 and $body != "заного" and $row["search_act"] == 1 or $row["gender"] == 2 and $row["age"] != 0 and $body != "заного" and $row["search_act"] == 1 and !in_array($body, $refresh) and !in_array($body, $girl) and !in_array($body, $boy) and !in_array(levinshtein($body, $commnd), $del)) {
      reset($row);
      $message = "Рофлим дальше из {$row["country"]}? Введи 'ещё'! - Хочешь заного? Введи 'заного'!";
      massage_send($message, $user_id, $token, $attachment);
  }
  if (!in_array($body, $ages) and $row["gender"] == 1 and $row["age"] != 0 and $row["search_act"] == 0 or $row["gender"] == 2 and $row["age"] != 0 and $row["search_act"] == 0 and !in_array($body, $ages)) {
    $mysqli->query("UPDATE actos SET country = '$body' WHERE vk_id = $user_id");
    $mysqli->query("UPDATE actos SET search_act = 1 WHERE vk_id = $user_id");
    $row = mysqli_fetch_array($mysqli->query("SELECT * FROM actos WHERE vk_id = $user_id"));
    sleep(1);
    $message = "Хорошо я буду искать кого-то из {$row["country"]}.";
    massage_send($message, $user_id, $token, $attachment);
    search($row, $mysqli, $ustoken, $user_id, $token, $sow);
  }
  if (in_array($body, $ages) and $row["gender"] == 1 and $row["age"] == 0 or in_array($body, $ages) and $row["gender"] == 2 and $row["age"] == 0) {
    $mysqli->query("UPDATE actos SET age = '".$body."' WHERE vk_id = $user_id");
    $message = "Хорошо я буду искать кого-то от {$body} лет.";
    massage_send($message, $user_id, $token);
    $message = "А из какого города? Например: Москва";
    massage_send($message, $user_id, $token);
  }
  if (!in_array($body, $ages) and !in_array($body, $girl) and !in_array($body, $boy) and $body != "заного" and $row["gender"] == 2 and $row["age"] == 0 or !in_array($body, $ages) and $row["gender"] == 1 and !in_array(levinshtein($body, $commnd), $del) and $row["age"] == 0 and !in_array($body, $girl) and !in_array($body, $boy)) {
    $message = "А какого возраста? (Отправь ответ цифрой, Например: 14)";
    massage_send($message, $user_id, $token);
  }
  if (in_array($body, $girl) and $row["gender"] == 0) {
    $message = "Хорошо я буду искать Девушек.";
    massage_send($message, $user_id, $token);
    $mysqli->query("UPDATE actos SET gender = 1 WHERE vk_id = $user_id");
    $row = mysqli_fetch_array($mysqli->query("SELECT * FROM actos WHERE vk_id = $user_id"));
    $message = "А какого возраста?";
    massage_send($message, $user_id, $token);
  }
  if (in_array(levinshtein($body, $commnd), $girl) and iconv_strlen($body) > 5 and !in_array($body, $girl) and !in_array($body, $boy) and $row["gender"] == 0 and $body != "заного") {
    $wrr = levinshtein($body, $commnd);
    $ger = strlen($body);
    $message = "Я понял! Хорошо я буду искать {$wrr}.";
    massage_send($message, $user_id, $token);
    $mysqli->query("UPDATE actos SET gender = 1 WHERE vk_id = $user_id");
    $row = mysqli_fetch_array($mysqli->query("SELECT * FROM actos WHERE vk_id = $user_id"));
    $message = "А какого возраста?";
    massage_send($message, $user_id, $token);
  }
  if (in_array(levinshtein($body, $commnd), $boy) and iconv_strlen($body) > 4 and !in_array($body, $girl) and !in_array($body, $boy) and $row["gender"] == 0) {
    $wrr = levinshtein($body, $commnd);
    $message = "Я понял! Хорошо я буду искать {$wrr}.";
    massage_send($message, $user_id, $token);
    $mysqli->query("UPDATE actos SET gender = 2 WHERE vk_id = $user_id");
    $row = mysqli_fetch_array($mysqli->query("SELECT * FROM actos WHERE vk_id = $user_id"));
    $message = "А какого возраста?";
    massage_send($message, $user_id, $token);
  }
  if (in_array($body, $boy) and $row["gender"] == 0) {
    $message = "Хорошо я буду искать Парней.";
    massage_send($message, $user_id, $token);
    $mysqli->query("UPDATE actos SET gender = 2 WHERE vk_id = $user_id");
    $row = mysqli_fetch_array($mysqli->query("SELECT * FROM actos WHERE vk_id = $user_id"));
    $message = "А какого возраста?";
    massage_send($message, $user_id, $token);
  }
  if (!in_array($body, $girl) and !in_array($body, $boy)  and $row["gender"] == 0 and $body != "заного") {
    $message = "Кого ищешь Девушку или Парня?";
    massage_send($message, $user_id, $token);
  }
  if ($body == "заного" or in_array(levinshtein($body, $commnd), $del) and !in_array($body, $refresh) and iconv_strlen($body) > 4) {
    $mysqli->query("UPDATE actos SET gender = 0 WHERE vk_id = $user_id");
    $mysqli->query("UPDATE actos SET search_act = 0 WHERE vk_id = $user_id");
    $mysqli->query("UPDATE actos SET age = 0 WHERE vk_id = $user_id");
    $mysqli->query("UPDATE actos SET country = 0 WHERE vk_id = $user_id");
    $message = "Хорошо давай сначала, Девушку или Парня?";
    massage_send($message, $user_id, $token);
  }

  echo('ok');

};