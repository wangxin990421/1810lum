<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/user/reg','User\UserController@reg');
$router->post('/user/login','User\UserController@login');
$router->post('/user/updatepwd','User\UserController@updatepwd');
$router->post('/user/weather','User\UserController@weather');
$router->post('/upload','User\UserController@upload');

//数据加密测试
$router->post('/user/ceshi','User\UserController@ceshi');

//数据加密 对称加密
$router->post('/user/decypt1','User\UserController@decypt1');

//数据加密  非对称数据加密 公钥解密
$router->post('/user/decypt2','User\UserController@decypt2');

//数据加密 练习
$router->post('/user/lianxi','User\UserController@lianxi');

//bu 数据传送
$router->post('/login/reg','login\LoginController@reg');
$router->post('/login/login','login\LoginController@login');
$router->post('/login/admin','login\LoginController@admin');