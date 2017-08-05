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

    /*Image Upload*/
    Route::resource('image_transfer', 'Api\ImageController');

    /*Production Group*/
    Route::prefix('production')->group(function () {
        /*Activity*/
        Route::get('activity', 'Api\Production\ActivityController@getAllActivity')
            ->name('production.activity.getAllActivity');
        Route::get('activity/enable', 'Api\Production\ActivityController@getEnableActivity')
            ->name('production.activity.getEnableActivity');
        Route::post('activity/update/status/{activity_id}', 'Api\Production\ActivityController@updateStatus')
            ->name('production.activity.update.status');
        Route::post('activity/update/{id}', 'Api\Production\ActivityController@update')
            ->name('production.activity.update');
        Route::get('activity/delete/{id}', 'Api\Production\ActivityController@delete')
            ->name('production.activity.delete');
        Route::post('activity/add', 'Api\Production\ActivityController@add')
            ->name('production.activity.add');
        /*Shrimp Size*/
        Route::get('shrimp_size', 'Api\Production\ShrimpSizeController@getAllSize')
            ->name('production.shrimp_size.getAllSize');
        Route::get('shrimp_size/enable', 'Api\Production\ShrimpSizeController@getEnableSize')
            ->name('production.shrimp_size.getEnableSize');
        Route::post('shrimp_size/update/status/{activity_id}', 'Api\Production\ShrimpSizeController@updateStatus')
            ->name('production.shrimp_size.update.status');
        Route::post('shrimp_size/update/{id}', 'Api\Production\ShrimpSizeController@update')
            ->name('production.shrimp_size.update');
        Route::get('shrimp_size/delete/{id}', 'Api\Production\ShrimpSizeController@delete')
            ->name('production.shrimp_size.delete');
        Route::post('shrimp_size/add', 'Api\Production\ShrimpSizeController@add')
            ->name('production.shrimp_size.add');
        /*Shrimp Type*/
        Route::get('shrimp_type', 'Api\Production\ShrimpTypeController@getAllType')
            ->name('production.shrimp_type.getAllType');
        Route::get('shrimp_type/enable', 'Api\Production\ShrimpTypeController@getEnableType')
            ->name('production.shrimp_type.getEnableType');
        Route::post('shrimp_type/update/status/{activity_id}', 'Api\Production\ShrimpTypeController@updateStatus')
            ->name('production.shrimp_type.update.status');
        Route::post('shrimp_type/update/{id}', 'Api\Production\ShrimpTypeController@update')
            ->name('production.shrimp_type.update');
        Route::get('shrimp_type/delete/{id}', 'Api\Production\ShrimpTypeController@delete')
            ->name('production.shrimp_type.delete');
        Route::post('shrimp_type/add', 'Api\Production\ShrimpTypeController@add')
            ->name('production.shrimp_type.add');

        /*Work*/
        Route::post('work', 'Api\Production\WorkController@store')
            ->name('production.work.store');
        Route::get('work/employee_amount_weight/{emID}', 'Api\Production\WorkController@employeeAmountWeight')
            ->name('production.work.employeeAmountWeight');
        /*Work Date*/
        Route::get('work/date/{date}', 'Api\Production\WorkController@getTimePeriod')
            ->name('production.work.date');
        /*Work Date Time Period*/
        Route::get('work/date/time_period/{id}', 'Api\Production\WorkController@getWorkList')
            ->name('production.work.date.time_period');
        /*Work Details*/
        Route::get('work/date/time_period/work_list/{work_id}', 'Api\Production\WorkController@getWorkDetails')
            ->name('production.workDetails');
        /*Delete Employee Weight*/
        Route::delete('work/date/time_period/work_list/weight/{weight_id}', 'Api\Production\WorkController@deleteWeight')
            ->name('production.work.delete.employee.weight');
        Route::get('work/delete/{id}', 'Api\Production\WorkController@deleteWork')
            ->name('production.work.delete');

        /*******Employee*******/
        /*Groups*/
        Route::get('groups', 'Api\Production\EmployeeController@getGroups')
            ->name('production.groups');
        /*Group Member*/
        Route::get('group/members/{id}', 'Api\Production\EmployeeController@getGroupMembers')
            ->name('production.group.members');
        /*Get Non Group Employee*/
        Route::get('group/non_group/members/{division_id}', 'Api\Production\EmployeeController@getNonGroupEmployee')
            ->name('production.nonGroup.members');
        /*Get All Division Employee*/
        Route::get('group/all/employee/{division_id}',
            'Api\Production\EmployeeController@getAllDivisionEmployee')
            ->name('production.all.employee');
        /*Add New Group Member*/
        Route::post('group/member/add', 'Api\Production\EmployeeController@addGroupMember')
            ->name('production.member.add');
        Route::post('group/member/edit', 'Api\Production\EmployeeController@changeGroupMember')
            ->name('production.member.edit');
        Route::post('group/member/delete', 'Api\Production\EmployeeController@deleteGroupMember')
            ->name('production.member.delete');
    });
    /*Human Resource*/
    Route::prefix('human_resource')->group(function () {
        /*Employee*/
        /*Get All Employee*/
        Route::get('employee/all', 'Api\HumanResource\EmployeeController@getAllEmployee')
            ->name('human_resource.employee.all');
        /*Get All Employee without Page*/
        Route::get('employee/all/without_page','Api\HumanResource\EmployeeController@getAllEmployeeWithOutPage')
            ->name('human_resource.employee.all.withoutPage');
        /*Division Employee*/
        Route::get('employee/division/{division_id}', 'Api\HumanResource\EmployeeController@getDivisionEmployee')
            ->name('human_resource.employee.division');
        /*Update Single Employee*/
        Route::post('employee/update', 'Api\HumanResource\EmployeeController@update')
            ->name('human_resource.employee.update');
        /*Change Division Department Employees*/
        Route::post('employee/change/division_department', 'Api\HumanResource\EmployeeController@changeDivisionDepartment')
            ->name('human_resource.employee.change.divisionDepartment');
        /*Change Salary Type*/
        Route::post('employee/change/salary_type', 'Api\HumanResource\EmployeeController@changeSalaryType')
            ->name('human_resource.employee.change.salaryType');
        /*Delete*/
        Route::post('employee/delete', 'Api\HumanResource\EmployeeController@delete')
            ->name('human_resource.employee.delete');
        /*Division*/
        Route::resource('division', 'Api\HumanResource\DivisionController');
        /*Department*/
        Route::resource('department', 'Api\HumanResource\DepartmentController');
//        Route::post('employee')
    });
    /*Human Car*/
    Route::prefix('human_resource/car')->group(function () {
        /*Manage*/
        Route::prefix('manage')->group(function () {
            /*Get Car*/
            Route::get('{type}', 'Api\HumanResource\CarController@getCar')
                ->name('human_resource.car.get');
            /*Add Car*/
            Route::post('add', 'Api\HumanResource\CarController@addCar')
                ->name('human_resource.car.add');
            /*Update Car*/
            Route::post('update', 'Api\HumanResource\CarController@updateCar')
                ->name('human_resource.car.update');
            Route::post('update_status','Api\HumanResource\CarController@updateStatus')
                ->name('human_resource.car.updateStatus');
        });
        /*Car Type*/
        Route::prefix('car_type')->group(function(){
            /*Get Types*/
           Route::get('/','Api\HumanResource\CarTypeController@getCarType')
               ->name('human_resource.car.type');
        });
        /*Car Request*/
        Route::prefix('car_request')->group(function () {
            /*Get Car Request*/
            Route::get('{userID}', 'Api\HumanResource\CarRequestController@getCarRequest')
                ->name('human_resource.car_request.get');
            /*Add Car Request*/
            Route::post('add', 'Api\HumanResource\CarRequestController@addCarRequest')
                ->name('human_resource.car_request.add');
            /*Update*/
            Route::post('update', 'Api\HumanResource\CarRequestController@updateCarRequest')
                ->name('human_resource.car_request.update');
        });
        /*Car Response*/
        Route::prefix('car_response')->group(function () {
            /*Get Request*/
            Route::get('get_request/{status}', 'Api\HumanResource\CarResponseController@getCarRequest')
                ->name('human_resource.car.car_response.getCarRequest');
            /*Get Response*/
            Route::get('get_response/{userID}','Api\HumanResource\CarResponseController@getCarResponse')
                ->name('human_resource.car.car_response.getCarResponse');
            /*Approve Request*/
            Route::post('approve', 'Api\HumanResource\CarResponseController@approveRequest')
                ->name('human_resource.car.car_response.approve');
            /*Update Response*/
            Route::post('update', 'Api\HumanResource\CarResponseController@updateResponse')
                ->name('human_resource.car.car_response.update');
            /*Delete Response*/
            Route::post('delete_response', 'Api\HumanResource\CarResponseController@deleteResponse')
                ->name('human_resource.car.car_response.deleteResponse');
            /*Delete Response Request*/
            Route::post('delete_response_request', 'Api\HumanResource\CarResponseController@deleteResponseRequest')
                ->name('human_resource.car.car_response.deleteResponseRequest');
        });
    });
});
