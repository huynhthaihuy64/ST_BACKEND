<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\TechnologyController;

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

Route::name('api.check')->group(function(){
    Route::get('v1/check', [CampaignController::class, 'showActiveCampaign'])->name('check');
    Route::get('check/{id}', [CampaignController::class, 'showActiveCampaignDetail'])->name('showcheck');
    Route::get('muc/{id}', [FormController::class, 'show'])->name('muc');
    Route::put('muc/{id}', [FormController::class, 'update'])->name('updatemuc');
    Route::post('thongtin', [ProfileController::class, 'submitTypeform'])->name('thongtin');
});

Route::name('api.users.')->group(function () {
    Route::get('v1/users', [CampaignController::class, 'showActiveCampaign'])->name('index');
    Route::post('v1/users', [UserController::class, 'store'])->name('store');
    Route::get('v1/users/{id}', [UserController::class, 'show'])->name('show');
    Route::put('v1/users/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('v1/users/{id}', [UserController::class, 'destroy'])->name('destroy');
});

Route::name('api.posts.')->group(function () {
    Route::get('v1/posts', [UserController::class, 'index'])->name('index');
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('password-reset-to-mail', [AuthController::class, 'resetPasswordToMail'])->name('sent_mail_password');
    Route::post('password-reset', [AuthController::class, 'resetPassword'])->name('reset_password');

    Route::group(['prefix' => 'public', 'as' => 'public.'], function () {
        Route::get('campaigns', [CampaignController::class, 'showActiveCampaign'])->name('showActiveCampaign');
        Route::get('campaigns/{id}', [CampaignController::class, 'showActiveCampaignDetail'])->name('showActiveCampaignDetail');
        Route::get('form/{id}', [FormController::class, 'show'])->name('show');
        Route::put('form/{id}', [FormController::class, 'update'])->name('update');
        Route::post('profiles', [ProfileController::class, 'submitTypeform'])->name('submitTypeform');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::delete('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
        Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refreshToken');

        Route::group(['prefix' => 'profiles', 'as' => 'profiles.'], function () {
            Route::get('', [ProfileController::class, 'index'])->name('index');
            Route::get('status', [ProfileController::class, 'getProfileStatusType'])->name('get_profile_status_type');
            Route::get('{id}', [ProfileController::class, 'show'])->name('show');
            Route::get('campaignlist/{campaign_id}', [ProfileController::class, 'listByCampaignId'])->name('list_by_campaign_id');
            Route::post('', [ProfileController::class, 'store'])->name('store');
            Route::post('update/{id}', [ProfileController::class, 'update'])->name('update');
            Route::put('set-status/{id}', [ProfileController::class, 'setStatus'])->name('setStatus');
            Route::get('report/group-by-status', [ProfileController::class, 'groupByStatus'])->name('profiles_by_status');
            Route::get('report/group-by-percent', [ProfileController::class, 'groupByPercent'])->name('profiles_by_percent');
        });

        Route::group(['prefix' => 'employees', 'as' => 'employees.'], function () {
            Route::get('status', [EmployeeController::class, 'getEmployeeStatusType'])->name('get_employee_status_type');
            Route::put('delete', [EmployeeController::class, 'setStatus'])->name('setStatus');
            Route::get('', [EmployeeController::class, 'index'])->name('index');
            Route::post('update/{id}', [EmployeeController::class, 'update'])->name('update');
            Route::get('{id}', [EmployeeController::class, 'show'])->name('show');
            Route::get('report/group-by-status', [EmployeeController::class, 'groupByStatus'])->name('employees_by_status');
            Route::post('', [EmployeeController::class, 'store'])->name('store');
        });

        Route::group(['prefix' => 'campaigns', 'as' => 'campaigns.'], function () {
            Route::get('', [CampaignController::class, 'index'])->name('index');
            Route::get('status', [CampaignController::class, 'getCampaignStatusType'])->name('campaigns_status');
            Route::post('update/{id}', [CampaignController::class, 'update'])->name('update');
            Route::post('', [CampaignController::class, 'store'])->name('store');
            Route::get('{id}', [CampaignController::class, 'show'])->name('show');
            Route::get('report/group-by-status', [CampaignController::class, 'groupByStatus'])->name('campaigns_by_status');
            Route::delete('', [CampaignController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'positions', 'as' => 'positions.'], function () {
            Route::get('', [PositionController::class, 'index'])->name('index');
        });  

        Route::group(['prefix' => 'forms', 'as' => 'forms.'], function () {
            Route::get('', [FormController::class, 'index'])->name('index');
            Route::put('{id}', [FormController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::get('employees-by-status', [EmployeeController::class, 'groupByStatus'])->name('employees_by_status');
            Route::get('employees-by-positions/{year}', [EmployeeController::class, 'groupByPosition'])->name('employees_by_position');
            Route::get('campaign-cv/{year}',[ChartController::class,'getTotalCvByCampaignWithYear'])->name('campaign_cv');
            Route::get('campaign/{year}', [CampaignController::class, 'getCampaignChart'])->name('campaign');
            Route::get('profile/{year}', [ProfileController::class, 'getProfileChart'])->name('profile');
            Route::get('totals',[ChartController::class,'getTotalProfileEmployeeCampaign'])->name('totals_campaign_profile_employee');
        });

        Route::group(['prefix' => 'technologies', 'as' => 'technologies.'], function () {
            Route::get('', [TechnologyController::class, 'index'])->name('index');
        });
    });
});
