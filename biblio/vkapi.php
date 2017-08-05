<?php

function group_isMember($user_id, $token, $groupid) {
  
  $request_params = array( 
    'group_id' => $groupid, 
    'user_id' => $user_id, 
    'access_token' => $token, 
    'v' => '5.65' 
  );

  $get_params = http_build_query($request_params);

  $obj = json_decode(file_get_contents('https://api.vk.com/method/groups.isMember?'. $get_params));

  return $obj->response;
}

function massage_send($message, $user_id, $token, $attachment = '') {

  $request_params = array( 
    'message' => $message, 
    'attachment' => $attachment, 
    'user_id' => $user_id, 
    'access_token' => $token, 
    'v' => '5.65' 
  );

  $get_params = http_build_query($request_params);

  file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
}

function user_search($row, $mysqli, $ustoken, $sow) {

  $request_params = array( 
    'sex' => $row["gender"],
    'count' => 1000,
    'has_photo' => 1,
    'sort' => 1,
    'status' => 6,
    'country' => 1,
    'online' => 1,
    'hometown' => $row["country"],
    'age_from' => $row["age"], 
    'age_to' => $row["age"], 
    'access_token' => $ustoken,
    'v' => '5.65' 
  );

  $get_params = http_build_query($request_params);
  $obj = json_decode(file_get_contents('https://api.vk.com/method/users.search?'. $get_params));

  global $count, $id, $name, $familia, $photo1, $photo2, $photo3, $photo, $set;

  $count = $obj->response->count;

  if ($count>1000) {
    $count = 1000;
  }

  $id = $obj->response->items[$set]->id;
  $user_id = $id;
  $name = $obj->response->items[$set]->first_name;
  $familia = $obj->response->items[$set]->last_name;

  photo_getAll($user_id, $ustoken, $cout = 3);

  $photo = "photo{$user_id}_{$photo1},photo{$user_id}_{$photo2},photo{$user_id}_{$photo3}";
}

function photo_getAll($user_id, $ustoken, $cout = 3) {

  $request_params = array( 
    'owner_id' => $user_id, 
    'cout' => $cout, 
    'skip_hidden' => 1, 
    'access_token' => $ustoken, 
    'v' => '5.65'
  );

  $get_params = http_build_query($request_params);

  $obj = json_decode(file_get_contents('https://api.vk.com/method/photos.getAll?'. $get_params));

  global $photo1, $photo2, $photo3;
  $photo1 = $obj->response->items[0]->id;
  $photo2 = $obj->response->items[1]->id;
  $photo3 = $obj->response->items[2]->id;
}