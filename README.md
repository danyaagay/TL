# TL
БОТВК, ВК, ВКонтакте, Бот, Знакомства, VKBOT, VK, Bot, PHP

Для работы необходимо 2 таблицы, actos и replis.
Базу можно создать в PHPMyAdmin.

И так, В базе actos должны быть 7 столбцов
id, vk_id, countr, search_act, gender, age, ivent

В базе replis должны быть 3 столбца
id, usr_id, iskaemoe

Далее, после создания 2 таблиц actos и replis переходим к настройке.
Все настройки находятся в /tech/settings.php

//Строка для подтверждения адреса сервера из настроек Callback API 
$confirmation_token = ''; 

//Ключ доступа сообщества 
$token = '';
//Ключ доступа пользователя
$ustoken = '';
//ИД группы
$groupid = 123456789;

//Данные базы
$mysqli = new mysqli("localhost", "Magist", "12345", "Magist");

Эти данные необходимо заменить на свои.
Пользуйтесь! По всем вопросам vk.com/devlixbot (Я всегда онлайн)
