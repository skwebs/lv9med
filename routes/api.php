<?php

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
// med-types
Route::delete("med-types/trash/{id}", [MedTypeController::class, "trash"]);
Route::get("med-types/trashed", [MedTypeController::class, "trashed"]);
Route::get("med-types/with-trashed", [MedTypeController::class, "withTrashed"]);
Route::post("med-types/restore/{id}", [MedTypeController::class, "restore"]);
Route::post("med-types/restore", [MedTypeController::class, "restoreAll"]);
Route::apiResource('med-types', MedTypeController::class);
// medicine
Route::post("medicine/restore/{id}", [MedicineController::class, "restore"]);
Route::post("medicine/restore", [MedicineController::class, "restoreAll"]);
Route::get("medicine/trashed", [MedicineController::class, "trashed"]);
Route::apiResource('medicine', MedicineController::class);
