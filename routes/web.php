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

//Route::get('/', function () {
//    return view('welcome');
//})

;Route::get('/demo', function () {
    return view('userProfile.home');
});

Auth::routes(['reset' => false]);

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'UserProfile@show')->name('userProfileShow');
Route::get('/user-profile/edit', 'UserProfile@edit')->name('userProfileEdit');
Route::get('/user-profile/account/{id}', 'UserProfile@showAccount')->name('accountShow');
Route::get('/user-profile/available-accounts', 'UserProfile@availableAccounts')->name('availableAccounts');
Route::get('/user-profile/money-transfer', 'UserProfile@moneyTransferForm')->name('moneyTransferForm');
Route::post('/user-profile/money-transfer', 'UserProfile@moneyTransfer')->name('moneyTransfer');

Route::get('/user-profile/settings', 'UserProfile@edit')->name('profileEditForm');
Route::post('/user-profile/settings', 'UserProfile@update')->name('profileUpdate');

Route::get('/user-profile/password', 'UserProfile@updatePasswordForm')->name('passwordEditForm');
Route::post('/user-profile/password', 'UserProfile@updatePassword')->name('passwordEdit');

Route::get('/account-info/{id}', 'UserProfile@userInfo')->name('userInfo');
Route::get('/account-balance/{id}', 'UserProfile@balance')->name('balance');
Route::get('/transaction-type-info/{id}', 'UserProfile@transactionTypeInfo')->name('transactionTypeInfo');


