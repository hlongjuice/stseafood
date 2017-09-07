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

        /*Last Insert*/
        Route::get('work/last_insert', 'Api\Production\WorkController@lastInsert');
        /*Update Work*/
        Route::post('work/update_work', 'Api\Production\WorkController@updateWork');
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
    /*Production Expiry Calculator*/
    Route::prefix('production/exp_calculator')->group(function () {
        Route::post('/', 'Api\Production\ExpCalculate\ExpirationController@uploadImage');
    });
    /*Human Resource*/
    Route::prefix('human_resource')->group(function () {
        /*Rank*/
        Route::prefix('rank')->group(function () {
            Route::get('get_all', 'Api\HumanResource\RankController@getAllRank');
        });
        /*Human Car Driver*/
        Route::prefix('driver')->group(function () {
            Route::get('all_driver', 'Api\HumanResource\DriverController@getAllDriver')
                ->name('human_resource.driver.getAllDriver');
        });
        /*Employee*/
        /*Add New Employee*/
        Route::post('employee/add', 'Api\HumanResource\EmployeeController@addNewEmployee');
        /*Get All Employee*/
        Route::get('employee/all', 'Api\HumanResource\EmployeeController@getAllEmployee')
            ->name('human_resource.employee.all');
        /*Get All Employee without Page*/
        Route::get('employee/all/without_page', 'Api\HumanResource\EmployeeController@getAllEmployeeWithOutPage')
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
            /*Available Car*/
            Route::get('available_car/{type}', 'Api\HumanResource\CarController@getAvailableCar')
                ->name('human_resource.car.get.available');
            /*Add Car*/
            Route::post('add', 'Api\HumanResource\CarController@addCar')
                ->name('human_resource.car.add');
            /*Update Car*/
            Route::post('update', 'Api\HumanResource\CarController@updateCar')
                ->name('human_resource.car.update');
            Route::post('update_status', 'Api\HumanResource\CarController@updateStatus')
                ->name('human_resource.car.updateStatus');
        });
        /*Car Type*/
        Route::prefix('car_type')->group(function () {
            /*Get Types*/
            Route::get('/', 'Api\HumanResource\CarTypeController@getCarType')
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
            /*Delete*/
            Route::post('delete', 'Api\HumanResource\CarRequestController@deleteRequest')
                ->name('human_resource.car_request.delete');
        });
        /*Car Response*/
        Route::prefix('car_response')->group(function () {
            Route::post('get_response/history', 'Api\HumanResource\CarResponseController@getCarResponseByUser');
            /*Get Request*/
            Route::get('get_request/{status}', 'Api\HumanResource\CarResponseController@getCarRequest')
                ->name('human_resource.car.car_response.getCarRequest');
            /*Search Request By Date*/
            Route::post('search_request/date', 'Api\HumanResource\CarResponseController@searchByDate')
                ->name('human_resource.car.car_response.searchByDate');
            /*Get Response*/
            Route::get('get_response/{status}', 'Api\HumanResource\CarResponseController@getCarResponse')
                ->name('human_resource.car.car_response.getCarResponse');
            /*Assign Car*/
            Route::post('assign_car', 'Api\HumanResource\CarResponseController@assignCar')
                ->name('human_resource.car.car_response.assignCar');
            /*Approve Request*/
            Route::post('approve', 'Api\HumanResource\CarResponseController@approveRequest')
                ->name('human_resource.car.car_response.approve');
            /*Update Response*/
            Route::post('update', 'Api\HumanResource\CarResponseController@updateResponse')
                ->name('human_resource.car.car_response.update');
            /*Delete Assigned Request*/
            Route::get('delete_assigned_request/{response_id}', 'Api\HumanResource\CarResponseController@deleteAssignedRequest');
            /*Cancel Approved Response*/
            Route::get('cancel_approved_response/{response_id}', 'Api\HumanResource\CarResponseController@cancelApprovedResponse');
            /*Delete Response*/
            Route::post('delete_response', 'Api\HumanResource\CarResponseController@deleteResponse')
                ->name('human_resource.car.car_response.deleteResponse');
            /*Delete Response Request*/
            Route::post('delete_response_request', 'Api\HumanResource\CarResponseController@deleteResponseRequest')
                ->name('human_resource.car.car_response.deleteResponseRequest');
            /*Get Car Request Status*/
            Route::get('car_request_status', 'Api\HumanResource\CarResponseController@getCarRequestStatus')
                ->name('human_resource.car.car_response.requestStatus');
        });
        /*Car Access Controller*/
        Route::prefix('car_access')->group(function () {
            /*Get Car Departure*/
            Route::get('get_cars/{status_id}', 'Api\HumanResource\CarAccessController@getCars');
            /*Car Departure*/
            Route::post('add_departure', 'Api\HumanResource\CarAccessController@addCarDeparture');
            /*Car Arrival*/
            Route::post('add_arrival', 'Api\HumanResource\CarAccessController@addCarArrival');
            /*Update Car Access*/
            Route::post('update', 'Api\HumanResource\CarAccessController@updateCarAccess');

            /*Cancel Status*/
            Route::get('cancel_status/{response_id}', 'Api\HumanResource\CarAccessController@cancelStatus');
        });
        /*Car Usage*/
        Route::prefix('car_usage')->group(function () {
            Route::post('get_by_month', 'Api\HumanResource\CarUsageController@getByMonth');
        });
    });
    /*QC*/
    Route::prefix('qc')->group(function () {

        Route::prefix('result')->group(function () {
            //Daily
            Route::get('daily/{date}', 'Api\QC\RecorderResultController@getDailyResult');
            //Monthly
            Route::post('monthly', 'Api\QC\RecorderResultController@getMonthlyResult');
            //Yearly
            Route::get('yearly/{year}', 'Api\QC\RecorderResultController@getYearlyResult');
            //Supplier Result by Month
            Route::post('supplier/month', 'Api\QC\RecorderResultController@getSupplierResultByMonth');
            //Supplier Result by Year
            Route::post('supplier/year', 'Api\QC\RecorderResultController@getSupplierResultByYear');
            //Supplier Result by Quarter
            Route::post('supplier/quarter', 'Api\QC\RecorderResultController@getSupplierResultByQuarter');

        });
        //Add Receiving
        Route::post('add_receiving', 'Api\QC\ShrimpReceivingController@addReceiving');
        //Add Extra Receiving
        Route::post('add_extra_receiving', 'Api\QC\ShrimpReceivingController@addExtraReceiving');
        //Show Receiving
        Route::post('show_receiving', 'Api\QC\ShrimpReceivingController@getReceiving');
        //Get Shrimp Receiving By ID
        Route::post('get_shrimp_receiving_by_id', 'Api\QC\ShrimpReceivingController@getShrimpReceivingByID');
        //Update Shrimp Receiving
        Route::post('update_shrimp_receiving', 'Api\QC\ShrimpReceivingController@updateShrimpReceiving');
        //Update Supplier Receiving
        Route::post('update_supplier_receiving', 'Api\QC\ShrimpReceivingController@updateSupplierReceiving');
        //Delete Supplier Receiving
        Route::get('delete_supplier_receiving/{receiving_id}', 'Api\QC\ShrimpReceivingController@deleteSupplierReceiving');
        //Delete Shrimp Receiving
        Route::get('delete_shrimp_receiving/{receiving_id}', 'Api\QC\ShrimpReceivingController@deleteShrimpReceiving');

    });

    /*Engineer*/
    Route::prefix('eng')->group(function () {
        //Water Supply Outside
        Route::prefix('water_supply_outside')->group(function () {
            //Get Supply By Date
            Route::get('get_supply_by_date/{date}', 'Api\Eng\WaterSupplyOutSideController@getSupplyByDate');
            //Get Supply By Month
            Route::post('get_supply_by_month', 'Api\Eng\WaterSupplyOutSideController@getSupplyByMonth');
            //Add New Supply
            Route::post('add_supply', 'Api\Eng\WaterSupplyOutSideController@addSupply');
            //Update Supply
            Route::post('update_supply', 'Api\Eng\WaterSupplyOutSideController@updateSupply');
            //Delete Supply
            Route::get('delete_supply/{id}', 'Api\Eng\WaterSupplyOutSideController@deleteSupply');
        });
        //Tank 210
        Route::prefix('tank_210')->group(function () {
            //Get Records
            Route::get('get_record_by_date/{date}', 'Api\Eng\Tank210Controller@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\Tank210Controller@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\Tank210Controller@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\Tank210Controller@deleteRecord');
        });
        //River Water
        Route::prefix('river_water')->group(function () {
            //Get Records
            Route::get('get_record_by_date/{date}', 'Api\Eng\RiverWaterController@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\RiverWaterController@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\RiverWaterController@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\RiverWaterController@deleteRecord');
        });
        //Water Meter
        Route::prefix('water_meter')->group(function () {
            //Get Records
            Route::get('get_record_by_date/{date}', 'Api\Eng\WaterMeterController@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\WaterMeterController@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\WaterMeterController@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\WaterMeterController@deleteRecord');
        });
        //5x7
        Route::prefix('_5x7')->group(function () {
            //Get Records
            Route::get('get_record_by_date/{date}', 'Api\Eng\_5x7Controller@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\_5x7Controller@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\_5x7Controller@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\_5x7Controller@deleteRecord');
        });
        //High Tank
        Route::prefix('high_tank')->group(function () {
            Route::get('get_record_by_date/{date}', 'Api\Eng\HighTankController@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\HighTankController@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\HighTankController@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\HighTankController@deleteRecord');
        });
        //Condens
        Route::prefix('condens')->group(function () {
            Route::get('get_record_by_date/{date}', 'Api\Eng\CondensController@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\CondensController@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\CondensController@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\CondensController@deleteRecord');
        });
        //Chlorine
        Route::prefix('chlorine')->group(function () {
            Route::get('get_record_by_date/{date}', 'Api\Eng\ChlorineController@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\ChlorineController@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\ChlorineController@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\ChlorineController@deleteRecord');
        });
        //Chlorine Lab
        Route::prefix('chlorine_lab')->group(function () {
            Route::get('get_record_by_date/{date}', 'Api\Eng\ChlorineLabController@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\ChlorineLabController@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\ChlorineLabController@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\ChlorineLabController@deleteRecord');
        });
        //Defrost Time
        Route::prefix('defrost_time')->group(function(){
            Route::get('get_record_by_date/{date}', 'Api\Eng\DefrostTimeController@getRecordByDate');
            //Add Records
            Route::post('add_record', 'Api\Eng\DefrostTimeController@addRecord');
            //Update Record
            Route::post('update_record', 'Api\Eng\DefrostTimeController@updateRecord');
            //Delete Record
            Route::get('delete_record/{id}', 'Api\Eng\DefrostTimeController@deleteRecord');
        });
    });
    /*Others*/
    Route::prefix('other')->group(function () {
        Route::prefix('supplier')->group(function () {
            Route::get('get_all', 'Api\Other\SupplierController@getAllSupplier');
            //Add Supplier
            Route::post('add_supplier', 'Api\Other\SupplierController@addSupplier');
            //Update Supplier
            Route::post('update_supplier', 'Api\Other\SupplierController@updateSupplier');
        });
    });

    /*Auth*/
    Route::prefix('auth')->group(function () {
        Route::post('custom_logout', 'Auth\CustomLogOutController@logout');
    });
});
