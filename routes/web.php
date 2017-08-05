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
/*Register*/
Route::resource('user','UserController');

/*Tester*/
/*Add Random Work*/
Route::resource('tester/work/random','Tester\WorkRandomController');
Route::get('tester/employee/{number}','Tester\EmployeeController@addEmployee')
    ->name('tester.employee');

/*Work Controller*/
Route::get('tester/work/store','Tester\WorkController@store');
Route::get('tester/work/','Tester\WorkController@getWorkList');
Route::get('tester/work/details/{work_id}','Tester\WorkController@getWorkDetails');
Route::get('tester/production/non_group/employee','Tester\ProductionEmployeeController@getNonGroupEmployee');
/*Add with firstOrCreate*/
Route::get('tester/production/members/add','Tester\ProductionEmployeeController@addWithFirstOrCreate');
Route::get('tester/production/members/change','Tester\ProductionEmployeeController@changeGroupMember');

/*Add Production Employee*/
Route::get('tester/production_employee/{number}','Tester\EmployeeController@addProductionEmployee')
    ->name('tester.productionEmployee');
Route::resource('tester/production','Tester\ProductionController');

Route::get('excel','ExcelController@index')->name('excel.index');

Route::prefix('production')->group(function(){
    Route::get('activity','Api\Production\ActivityController@getAllActivity');
});
Route::prefix('human_resource')->group(function(){
    Route::prefix('car')->group(function(){
        Route::get('hello',function(){
            echo "Hello World";
        })  ;
    });
    Route::get('yo',function(){
       echo "Yo!!";
    });
});

