<?php

$config = array
(
  'site' => array(
    'parent' => '../../',
    'path' => 'http://'.$_SERVER['HTTP_HOST'].str_replace('index.php','',$_SERVER['PHP_SELF']),
    'url' => 'https://haolongbin.unlink.men/',
    'name'  => '天龍上人合成器',
    'title' => '天龍上人合成器',
    'description' => '郝龍斌聲援靜坐抗暴強調人權普世價值',
    'copyright' => 'just for fun',
    'shortcut-icon' => 'https://haolongbin.unlink.men/images/favicon.png',
    'apple-touch-icon' => ''
  ),
  'setting' => array(
    'enable-database' => false,
    'enable-navbar-search' => false,
    'enable-member-system' => false
  ),
  'member' => array(
    'default-page' => 'member'
  ),
  'database' => array(
    'host'  => '',
    'user'  => '',
    'pass'  => '',
    'db'  => ''
  ),
  'admin' => array(
    '000000000000000'
  ),
  'google' => array(
    'analytics-id'  => 'UA-00000000-00'
  ),
  'facebook' => array(
    'fanpage' => '',
    'app-id' => '',
    'app-secret' => '',
    'privacy-policy' => ''
  ),
  'og' => array(
    'title' => '天龍上人合成器',
    'type'  => 'website',
    'url' => 'https://haolongbin.unlink.men/',
    'image' => 'http://haolongbin.unlink.men/images/fb.jpg',
    'sitename'  => '天龍上人合成器',
    'description' => '郝龍斌聲援靜坐抗暴強調人權普世價值'
  ),
  'application' => array(
    'object-name' => '天龍上人'
  ),
);

?>
