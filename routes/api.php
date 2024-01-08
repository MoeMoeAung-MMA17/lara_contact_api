<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SearchRecordController;
use App\Http\Middleware\ApiTokenCheck;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::apiResource('contact',ContactController::class)->middleware(ApiTokenCheck::class);

Route::prefix("v1")->group(function () {

        Route::middleware('auth:sanctum')->group(function () { 
                Route::apiResource('contact', ContactController::class);

                
                Route::controller(ContactController::class)->group(function(){
                        Route::get("contact-trash","trash");
                        Route::post("contact-restore/{id}","trash");
                        Route::delete("contact-force-delete/{id}","forceDelete");
                        Route::post("contact-restore-all","restoreAll");
                        Route::delete("contact-empty-bin","emptyBin");
                        Route::post("multiple-delete","multipleDelete");

        
                });

                Route::controller(FavoriteController::class)->group(function () {
                        // Route::post("favorite/{id}", "store");
                        Route::post("favorite/{id}", "markAsFavorite");
                        Route::delete("favorite/{id}", "destroy");
                        Route::get("favorite", "index");
                        Route::get("favorite/{id}", "show");
                        Route::delete("favorite/{id}", "destroy");
                    });
            
                    Route::controller(SearchRecordController::class)->group(function () {
                        Route::get("search-record", "index");
                        Route::delete("search-record/{id}", "destroy");
                    });
                
                Route::post("logout", [ApiAuthController::class, 'logout']);
                Route::post("logout-all", [ApiAuthController::class, 'logoutAll']);
                Route::get("devices", [ApiAuthController::class, 'devices']);
        });

                Route::post("register", [ApiAuthController::class, 'register']);
                Route::post("login", [ApiAuthController::class, 'login']);

    });
