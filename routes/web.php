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

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'Api\ProductionController@index');
/*Register*/
Route::resource('user', 'UserController');

//Test Eng
Route::get('test/eng','Web\Engineer\TestExportController@index')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/','Web\DashboardController@index');
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
    //QC
    Route::prefix('qc')->group(function(){
        Route::prefix('export')->group(function(){
            //Index
            Route::get('/','Web\QC\ExportController@index')
                ->name('qc.export.index');
            //Get Record By Date
            Route::post('get_record_by_date','Web\QC\ExportController@getRecordByDate')
                ->name('qc.export.getRecordByDate');
            //Get Excel
            Route::get('get_excel/{id}', 'Web\QC\ExportController@getExcel')
                ->name('qc.export.getExcel');
        });
    });

    //Production
    Route::prefix('production')->group(function(){
        Route::prefix('work')->group(function(){
            //Index
            Route::get('/','Web\Production\WorkController@index')
                ->name('production.work.index');
            //Get Record By Date
            Route::post('get_record_by_date','Web\Production\WorkController@getRecordByDate')
                ->name('production.work.getRecordByDate');
            //Get Excel
            Route::get('get_excel/{id}', 'Web\Production\WorkController@getExcel')
                ->name('production.work.getExcel');
        });
    });

    //Engineer
    Route::prefix('engineer')->group(function(){
        Route::prefix('water_usage')->group(function(){
            //Index
            Route::get('/','Web\Engineer\WaterUsageController@index')
                ->name('engineer.water_usage.index');
            //Get Record By Month
            Route::post('get_record_by_month','Web\Engineer\WaterUsageController@getRecordByMonth')
                ->name('engineer.water_usage.getRecordByMonth');
            //Get Excel
            Route::get('get_excel/{month}/test/{year}', 'Web\Engineer\WaterUsageController@getExcel')
                ->name('engineer.water_usage.getExcel');
        });
        //Cold Storage
        Route::prefix('cold_storage')->group(function(){
            //Index
            Route::get('/','Web\Engineer\ColdStorageController@index')
                ->name('engineer.cold_storage.index');
            //Get Record By Month
            Route::post('get_record_by_date','Web\Engineer\ColdStorageController@getRecordByDate')
                ->name('engineer.cold_storage.getRecordByDate');
            //Get Excel
            Route::get('get_excel/{date}', 'Web\Engineer\ColdStorageController@getExcel')
                ->name('engineer.cold_storage.getExcel');
        });
        Route::get('/',function(){
            return view('site.engineer.index');
        })->name('engineer.index');
    });

    //Hr
    Route::prefix('hr')->group(function(){
        Route::prefix('car_usage')->group(function(){
            //Index
            Route::get('/','Web\HR\CarUsageController@index')
                ->name('hr.car_usage.index');
            //By Month
            Route::prefix('by_month')->group(function (){
                //index
               Route::get('/','Web\HR\CarUsageController@indexByMonth')
                   ->name('hr.car_usage.by_month.index');
                //get by month
                Route::post('get_by_month','Web\HR\CarUsageController@getByMonth')
                    ->name('hr.car_usage.by_month.get');
                //get excel
                Route::get('get_excel/{date}/car_id/{id}','Web\HR\CarUsageController@getExcelByMonth')
                    ->name('hr.car_usage.by_month.getExcel');
            });
            //By Year
            Route::prefix('by_year')->group(function (){
                //index
                Route::get('/','Web\HR\CarUsageController@indexByYear')
                    ->name('hr.car_usage.by_year.index');
                //get by month
                Route::post('get_by_year','Web\HR\CarUsageController@getByYear')
                    ->name('hr.car_usage.by_year.get');
                //get excel
                Route::get('get_excel/{date}/car_id/{id}','Web\HR\CarUsageController@getExcelByYear')
                    ->name('hr.car_usage.by_year.getExcel');
            });
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
            Route::post('get_record_by_date','Web\Other\RepairInvoiceController@getRecordByDate')
                ->name('other.repair_invoice.getRecordByDate');
            //Get Excel
            Route::get('get_excel/{id}', 'Web\Other\RepairInvoiceController@getExcel')
                ->name('other.repair_invoice.getInvoiceExcel');
        });
    });
});

//Clear Cache
Route::get('clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
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

