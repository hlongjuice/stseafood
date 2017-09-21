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
Route::get('/test', 'Api\ProductionController@index');
/*Register*/
Route::resource('user', 'UserController');

Route::group(['middleware' => 'auth'], function () {
    Route::prefix('hr')->group(function () {
        Route::get('import_employee', 'WebService\HR\EmployeeController@importEmployee')
            ->name('hr.employee.import');
    });
    //Dashboard
    Route::get('dashboard','Web\DashboardController@index')
        ->name('dashboard');

    //Admin
    Route::prefix('admin')->group(function(){
        //
        Route::prefix('users')->group(function(){
            Route::get('/','Web\Admin\UserController@index')
                ->name('admin.users.index');
            Route::post('/','Web\Admin\UserController@store')
                ->name('admin.users.store');
            Route::get('create','Web\Admin\UserController@create')
                ->name('admin.users.create');
            Route::delete('/{id}','Web\Admin\UserController@destroy')
                ->name('admin.users.destroy');
            Route::put('/{id}','Web\Admin\UserController@update')
                ->name('admin.users.update');
            Route::get('edit/{id}','Web\Admin\UserController@edit')
                ->name('admin.users.edit');
            Route::get('edit/password/{id}','Web\Admin\UserController@editPassword')
                ->name('admin.users.edit_password');
            Route::put('edit/password/{id}','Web\Admin\UserController@updatePassword')
                ->name('admin.users.update_password');
        });
    });
    //Other
    Route::prefix('other')->group(function () {
        //Repair Invoice
        Route::prefix('repair_invoice')->group(function () {
            //Index
            Route::get('/','Web\Other\RepairInvoiceController@index')
                ->name('other.repair_invoice.index');
            //Get Record By Date
            Route::get('get_record_by_date/{date}','Web\Other\RepairInvoiceController@getRecordByDate')
                ->name('other.repair_invoice.getRecordByDate');
            //Get Excel
            Route::get('get_excel/{id}', 'Web\Other\RepairInvoiceController@getInvoiceExcel')
                ->name('other.repair_invoice.getInvoiceExcel');
        });
    });
});


/*Tester*/
/*Add Random Work*/
Route::resource('tester/work/random', 'Tester\WorkRandomController');
Route::get('tester/employee/{number}', 'Tester\EmployeeController@addEmployee')
    ->name('tester.employee');

/*Work Controller*/
Route::get('tester/work/store', 'Tester\WorkController@store');
Route::get('tester/work/', 'Tester\WorkController@getWorkList');
Route::get('tester/work/details/{work_id}', 'Tester\WorkController@getWorkDetails');
Route::get('tester/production/non_group/employee', 'Tester\ProductionEmployeeController@getNonGroupEmployee');
/*Add with firstOrCreate*/
Route::get('tester/production/members/add', 'Tester\ProductionEmployeeController@addWithFirstOrCreate');
Route::get('tester/production/members/change', 'Tester\ProductionEmployeeController@changeGroupMember');

/*Add Production Employee*/
Route::get('tester/production_employee/{number}', 'Tester\EmployeeController@addProductionEmployee')
    ->name('tester.productionEmployee');
Route::resource('tester/production', 'Tester\ProductionController');

Route::get('excel', 'ExcelController@index')->name('excel.index');

Route::prefix('production')->group(function () {
    Route::get('activity', 'Api\Production\ActivityController@getAllActivity');
});
Route::prefix('human_resource')->group(function () {
    Route::prefix('car')->group(function () {
        Route::get('hello', function () {
            echo "Hello World";
        });
    });
    Route::get('yo', function () {
        echo "Yo!!";
    });
});

