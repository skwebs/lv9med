<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\MedTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// company
Route::get("company/trashed", [CompanyController::class, "trashed"]);
Route::get("company/with-trashed", [CompanyController::class, "withTrashed"]);
Route::post("company/restore/{id}", [CompanyController::class, "restore"]);
Route::delete("company/delete/{id}", [CompanyController::class, "deleteForever"]);
Route::post("company/restore", [CompanyController::class, "restoreAll"]);
Route::apiResource('company', CompanyController::class);
// med-type
Route::delete("med-type/trash/{id}", [MedTypeController::class, "trash"]);
Route::get("med-type/trashed", [MedTypeController::class, "trashed"]);
Route::get("med-type/with-trashed", [MedTypeController::class, "withTrashed"]);
Route::post("med-type/restore/{id}", [MedTypeController::class, "restore"]);
Route::post("med-type/restore", [MedTypeController::class, "restoreAll"]);
Route::apiResource('med-type', MedTypeController::class);
// medicine
Route::post("medicine/restore/{id}", [MedicineController::class, "restore"]);
Route::post("medicine/restore", [MedicineController::class, "restoreAll"]);
Route::get("medicine/trashed", [MedicineController::class, "trashed"]);
Route::apiResource('medicine', MedicineController::class);
// user
Route::get("auth/trashed", [AuthController::class, "trashed"]);
Route::post("auth/restore/{id}", [AuthController::class, "restore"]);
Route::post("auth/restore", [AuthController::class, "restoreAll"]);
Route::post("auth/login", [AuthController::class, "login"]);
Route::apiResource('auth', AuthController::class);
