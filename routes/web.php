<?php


Route::get('/', function () {
    return view('welcome');
});

Route::resource('customers', 'CustomersController')->except('show')->middleware('auth');
Route::resource('orders', 'OrdersController')->except('show')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
