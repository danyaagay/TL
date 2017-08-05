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

```php
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
```

Эти данные необходимо заменить на свои.
Пользуйтесь! По всем вопросам <vk.com/devlixbot> (Я всегда онлайн)

----------ENGLISH PERSON-------------------

To work, you need 2 tables, actos and replis.
You can create a database in PHPMyAdmin.

And so, the action database should have 7 columns
Id, vk_id, countr, search_act, gender, age, ivent

The replis database must have 3 columns
Id, usr_id, iskaemoe

Next, after creating the two tables actos and replis, go to the setup.
All settings are in /tech/settings.php

```php
// callback API
$confirm_token = '';

// Community Access Key
$token = '';
// The user's access key
$ustoken = '';
// Group ID
$groupid = 123456789;

// Database data
$mysqli = new mysqli ("localhost", "Magist", "12345", "Magist");
```

These data must be replaced with their own.
Use it! For all questions <vk.com/devlixbot> (I'm always online)
