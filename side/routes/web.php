<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// 首頁:沒東西
Route::get('/home', 'HomeController@index')->name('home');

// 取得星座
Route::get('/zodiac', 'ZodiacController@latestZodiacInfo');
Route::get('/crawler/save', 'ZodiacController@saveZodiac');


// Route::group(['prefix' => 'user'], function(){
//     //使用者驗證
//     Route::group(['prefix' => 'auth'], function(){
//         //Facebook登入
//         Route::get('/facebook-sign-in', 'UserAuthController@facebookSignInProcess');
//         //Facebook登入重新導向授權資料處理
//         Route::get('/facebook-sign-in-callback', 'UserAuthController@facebookSignInCallbackProcess');
//     });
// });