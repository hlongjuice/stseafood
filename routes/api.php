<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:api'], function () {
//    Route::get('production/dates', 'Api\ProductionController@getAllDate')
//        ->name('production.dates');
//    Route::get('production/schedule/{id}', 'Api\ProductionController@getProductionSchedule')
//        ->name('production.schedule');
//    Route::resource('production', 'Api\ProductionController');
    /*Employee*/
    Route::resource('employee','Api\EmployeeController');
    /*Division*/
    Route::resource('division','Api\DivisionController');
    /*Image Upload*/
    Route::resource('image_transfer','Api\ImageController');

    /*Production Group*/
    Route::prefix('production')->group(function(){
        /*Activity*/
       Route::get('activity','Api\Production\ActivityController@getAllActivity')
           ->name('production.activity.getAllActivity');
        /*Shrimp Size*/
        Route::get('shrimp_size','Api\Production\ShrimpSizeController@getAllSize')
            ->name('production.shrimp_size.getAllSize');
        /*Shrimp Type*/
        Route::get('shrimp_type','Api\Production\ShrimpTypeController@getAllType')
            ->name('production.shrimp_type.getAllType');
        /*Groups*/
        Route::get('groups','Api\Production\EmployeeController@getGroups')
            ->name('production.groups');
        /*Group Member*/
        Route::get('group/members/{id}','Api\Production\EmployeeController@getGroupMembers')
            ->name('production.group.members');

        /*Work*/
        Route::post('work','Api\Production\WorkController@store')
            ->name('production.work.store');
        Route::get('work/employee_amount_weight/{emID}','Api\Production\WorkController@employeeAmountWeight')
            ->name('production.work.employeeAmountWeight');
        /*Work Date*/
        Route::get('work/date/{date}','Api\Production\WorkController@getTimePeriod')
            ->name('production.work.date');
        /*Work Date Time Period*/
        Route::get('work/date/time_period/{id}','Api\Production\WorkController@getWorkList')
            ->name('production.work.date.time_period');
        /*Work Details*/
        Route::get('work/date/time_period/work_list/{work_id}','Api\Production\WorkController@getWorkDetails')
            ->name('production.workDetails');
    });
});
