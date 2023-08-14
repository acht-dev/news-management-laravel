<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LogHistoryController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group( function () {
    Route::prefix("comments")->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('/{id}', [CommentController::class, 'show']);
    });

    Route::prefix("news")->group(function () {
        Route::post('/', [NewsController::class, 'create']);
    });

    Route::prefix("log-histories")->group(function () {
        Route::get('/', [LogHistoryController::class, 'index']);
        Route::get('/{id}', [LogHistoryController::class, 'show']);
    });
});

Route::prefix("news")->group(function () {
    Route::get('/', [NewsController::class, 'index']);
    Route::get('/{id}', [NewsController::class, 'show']);
});

Route::prefix("categories")->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
});
