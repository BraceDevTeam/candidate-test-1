<?php


Route::get('/', function () {
    return view('auth/login');
});

Route::resource('customers', 'CustomersController')->except('show')->middleware('auth');
Route::resource('orders', 'OrdersController')->except('show')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('contracts', 'ContractsController@index');

Auth::routes();
