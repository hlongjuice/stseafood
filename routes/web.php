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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test','Api\ProductionController@index');
<<<<<<< HEAD
/*Tester*/
Route::get('tester/employee/{number}','Tester\EmployeeController@addEmployee')
    ->name('tester.employee');
Route::resource('tester/production','Tester\ProductionController');
=======
Route::get('excel','ExcelController@index')->name('excel.index');
>>>>>>> origin/master
