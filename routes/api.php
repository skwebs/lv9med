<?php

use App\Http\Controllers\Api\CompanyController;
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

Route::get("company/trashed", [CompanyController::class, "trashed"]);
Route::get("company/with-trashed", [CompanyController::class, "withTrashed"]);
Route::post("company/restore/{id}", [CompanyController::class, "restore"]);
Route::post("company/restore", [CompanyController::class, "restoreAll"]);
Route::apiResource('company', CompanyController::class);
