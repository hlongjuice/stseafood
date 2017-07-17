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
       Route::get('activity/enable','Api\Production\ActivityController@getEnableActivity')
           ->name('production.activity.getEnableActivity');
       Route::patch('activity/update/status/{activity_id}','Api\Production\ActivityController@updateStatus')
           ->name('production.activity.update.status');
       Route::patch('activity/update/{id}','Api\Production\ActivityController@update')
           ->name('production.activity.update');
       Route::delete('activity/delete/{id}','Api\Production\ActivityController@delete')
           ->name('production.activity.delete');
       Route::post('activity/add','Api\Production\ActivityController@add')
           ->name('production.activity.add');
        /*Shrimp Size*/
        Route::get('shrimp_size','Api\Production\ShrimpSizeController@getAllSize')
            ->name('production.shrimp_size.getAllSize');
        Route::get('shrimp_size/enable','Api\Production\ShrimpSizeController@getEnableSize')
            ->name('production.shrimp_size.getEnableSize');
        Route::patch('shrimp_size/update/status/{activity_id}','Api\Production\ShrimpSizeController@updateStatus')
            ->name('production.shrimp_size.update.status');
        Route::patch('shrimp_size/update/{id}','Api\Production\ShrimpSizeController@update')
            ->name('production.shrimp_size.update');
        Route::delete('shrimp_size/delete/{id}','Api\Production\ShrimpSizeController@delete')
            ->name('production.shrimp_size.delete');
        Route::post('shrimp_size/add','Api\Production\ShrimpSizeController@add')
            ->name('production.shrimp_size.add');
        /*Shrimp Type*/
        Route::get('shrimp_type','Api\Production\ShrimpTypeController@getAllType')
            ->name('production.shrimp_type.getAllType');
        Route::get('shrimp_type/enable','Api\Production\ShrimpTypeController@getEnableType')
            ->name('production.shrimp_type.getEnableType');
        Route::patch('shrimp_type/update/status/{activity_id}','Api\Production\ShrimpTypeController@updateStatus')
            ->name('production.shrimp_type.update.status');
        Route::patch('shrimp_type/update/{id}','Api\Production\ShrimpTypeController@update')
            ->name('production.shrimp_type.update');
        Route::delete('shrimp_type/delete/{id}','Api\Production\ShrimpTypeController@delete')
            ->name('production.shrimp_type.delete');
        Route::post('shrimp_type/add','Api\Production\ShrimpTypeController@add')
            ->name('production.shrimp_type.add');

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
        /*Delete Employee Weight*/
        Route::delete('work/date/time_period/work_list/weight/{weight_id}','Api\Production\WorkController@deleteWeight')
            ->name('production.delete.employee.weight');

        /*******Employee*******/
        /*Groups*/
        Route::get('groups','Api\Production\EmployeeController@getGroups')
            ->name('production.groups');
        /*Group Member*/
        Route::get('group/members/{id}','Api\Production\EmployeeController@getGroupMembers')
            ->name('production.group.members');
        /*Get Non Group Employee*/
        Route::get('group/non_group/members/{division_id}','Api\Production\EmployeeController@getNonGroupEmployee')
            ->name('production.nonGroup.members');
        /*Get All Division Employee*/
        Route::get('group/all/employee/{division_id}',
            'Api\Production\EmployeeController@getAllDivisionEmployee')
            ->name('production.all.employee');
        /*Add New Group Member*/
        Route::post('group/member/add','Api\Production\EmployeeController@addGroupMember')
            ->name('production.member.add');
        Route::post('group/member/edit','Api\Production\EmployeeController@changeGroupMember')
            ->name('production.member.edit');
        Route::post('group/member/delete','Api\Production\EmployeeController@deleteGroupMember')
            ->name('production.member.delete');
    });
});
