<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\VaultController;

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
Route::post('/import', [VaultController::class, 'import']);
Route::get('/export', [VaultController::class, 'export']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/create-folder', [FolderController::class, 'create']);
    Route::get('/get-folder', [FolderController::class, 'allFolder']);
    Route::post('/create-vault', [VaultController::class, 'store']);
    Route::put('/update-vault', [VaultController::class, 'update']);
    Route::get('/get-all-vault/{type}', [VaultController::class, 'getVaultItems']);
    Route::get('/get-item/{id}', [VaultController::class, 'getItem']);
    Route::delete('/delete-selected-vault-item/{itemId}', [VaultController::class, 'destroy']);
});


// public route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
