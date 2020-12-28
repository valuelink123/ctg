<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//App::setLocale('ja');

Route::match(['post','get'],'/', 'HomeController@index');//首页privacy-statement
Route::match(['post','get'],'/privacyStatement', 'HomeController@privacyStatement');//privacy-statement
Route::match(['post','get'],'/article', 'HomeController@article');
Route::match(['post','get'],'/blog', 'HomeController@blog');//
Route::post('/addTester', 'HomeController@addTester');//客户添加的自己的邮箱和姓名


Route::match(['post','get'],'/claim-the-gift-step-0', 'StepController@stepZero');
Route::match(['post','get'],'/step/step-1', 'StepController@stepOne');
Route::match(['post','get'],'/step/step-2', 'StepController@stepTwo');
Route::match(['post','get'],'/step/step-3', 'StepController@stepThree');
Route::match(['post','get'],'/step/step-4', 'StepController@stepFour');
Route::match(['post','get'],'/step/step-5', 'StepController@stepFive');

Route::match(['post','get'],'/product/detail', 'ProductController@detail');

