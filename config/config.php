<?php

/*
=====================================================
 Настройки сайта
 -------------------------------------
 Файл: config.php
=====================================================
*/

$system = [];

#Адрес нашего сайта (слеш на конце обязателен)
$system['url'] = 'http://obmenpunkt.local/';

$system['recaptcha'] = [

  'public_key' => '6LdS7QQaAAAAACU2UwltOLiKSO7PiJY0TQjDiKp6',
  'secret_key' => '6LdS7QQaAAAAAOZSkElMAlfk35fcfPW4GJTqh1Vc',
  'check' => 'https://www.google.com/recaptcha/api/siteverify'

];

$system['vk'] = [

  'info_url' => 'https://api.vk.com/method/users.get',

  'oauth_url' => 'https://oauth.vk.com/access_token',
  'app_url' => 'https://oauth.vk.com/authorize',

  'app_id' => '7639348',
  'secret_key' => 'NRt6KGYPrUgimTaPPp3G'

];

 ?>