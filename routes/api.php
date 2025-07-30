<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\FuelRequestController as OwnerFuelController;
use App\Http\Controllers\Owner\MaintenanceRequestController as OwnerMaintenanceController;
use App\Http\Controllers\Owner\ConditionUpdateController as OwnerConditionController;
use App\Http\Controllers\Owner\InspectionFileController as OwnerInspectionController;
use App\Http\Controllers\Manager\VehicleController as ManagerVehicleController;
use App\Http\Controllers\Manager\FuelRequestController as ManagerFuelController;
use App\Http\Controllers\Manager\MaintenanceRequestController as ManagerMaintenanceController;
use App\Http\Controllers\Auditor\AuditLogController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\FileUploadController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(
    [
       // 'auth:api'
        ]
    )
->group(function () {

Route::post('/upload-file', [FileUploadController::class, 'upload']);

    Route::prefix('owners/{ownerId}')
        //->middleware([CheckRole::class . ':owner'])
        ->group(function () {
            Route::get('fuel-requests', [OwnerFuelController::class, 'index']);
            Route::post('fuel-requests', [OwnerFuelController::class, 'store']);

            Route::get('maintenance-requests', [OwnerMaintenanceController::class, 'index']);
            Route::post('maintenance-requests', [OwnerMaintenanceController::class, 'store']);

            Route::get('condition-updates', [OwnerConditionController::class, 'index']);
            Route::post('condition-updates', [OwnerConditionController::class, 'store']);

            Route::get('inspection-files', [OwnerInspectionController::class, 'index']);
            Route::post('inspection-files', [OwnerInspectionController::class, 'store']);
        });

    Route::prefix('manager')
      //  ->middleware([CheckRole::class . ':manager'])
        ->group(function () {
            Route::get('vehicles', [ManagerVehicleController::class, 'index']);
            Route::put('vehicles/{vehicleId}', [ManagerVehicleController::class, 'update']);

            Route::patch('fuel-requests/{requestId}', [ManagerFuelController::class, 'approve']);
            Route::patch('maintenance-requests/{requestId}', [ManagerMaintenanceController::class, 'approve']);
        });
 
    Route::prefix('auditor')
        //->middleware([CheckRole::class . ':auditor'])
        ->group(function () {
            Route::get('logs', [AuditLogController::class, 'index']);
        });

    Route::prefix('admin')
        //->middleware([CheckRole::class . ':admin'])
        ->group(function () {
            Route::get('users', [AdminUserController::class, 'index']);
            Route::post('users', [AdminUserController::class, 'store']);
            Route::put('users/{userId}', [AdminUserController::class, 'update']);
            Route::delete('users/{userId}', [AdminUserController::class, 'destroy']);

            Route::post('vehicles', [AdminVehicleController::class, 'store']);
        });
});
