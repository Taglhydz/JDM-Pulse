<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\MotorizationController;
use App\Http\Controllers\OwnController;
use App\Http\Controllers\PowerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;

use App\Http\Middleware\CheckApiKey;
use App\Http\Middleware\CheckRole;

Route::middleware('throttle:60,0.1')->group(function () {
    // Routes d'authentification
    Route::prefix('auth')->group(function () {
        Route::post('/login'		  , [AuthController::class, 'login'			]);
        Route::post('/register'		  , [AuthController::class, 'register'		]);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    });

    // Routes protégées par Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/user'  , [AuthController::class, 'user'  ]);
        
        Route::middleware('check.api.key')->group(function () {
            // Prefix pour User
            Route::prefix('users')->group(function () {
                Route::get	 ('/all'			, [UserController::class, 'index'		  ]);
                Route::post	 ('/create'			, [UserController::class, 'store'		  ]);
                Route::get	 ('/{id}'			, [UserController::class, 'show'		  ]);
                Route::post	 ('/update/{id}'	, [UserController::class, 'update'		  ]);
                Route::delete('/delete/{id}'	, [UserController::class, 'destroy'		  ]);
                Route::post	 ('/update-pwd/{id}', [UserController::class, 'updatePassword']);
            });
        });

        // Prefix pour Car
        Route::prefix('cars')->group(function () {
            Route::get	 ('/all'		, [CarController::class, 'index' 			 ]);		
            Route::get	 ('/{id}'		, [CarController::class, 'show'  			 ]);
            Route::post	 ('/create'		, [CarController::class, 'store'  			 ]);
            Route::post	 ('/update/{id}', [CarController::class, 'update'			 ]);
            Route::delete('/delete/{id}', [CarController::class, 'destroy'			 ]);
            Route::get	 ('/detail/{id}', [CarController::class, 'getDetailsByCarId' ]);
            Route::get	 ('/user/{id}'  , [OwnController::class, 'getCarsByUserId'	 ]);
        });

        // Prefix pour Edition
        Route::prefix('editions')->group(function () {
            Route::get   ('/all'		, [EditionController::class, 'index'  ]);
            Route::post  ('/create'		, [EditionController::class, 'store'  ]);
            Route::get   ('/{id}'		, [EditionController::class, 'show'   ]);
            Route::post  ('/update/{id}', [EditionController::class, 'update' ]);
            Route::delete('/delete/{id}', [EditionController::class, 'destroy']);
        });

        // Prefix pour Engine
        Route::prefix('engines')->group(function () {
            Route::get   ('/all'		, [EngineController::class, 'index'  ]);
            Route::post  ('/create'		, [EngineController::class, 'store'  ]);
            Route::get   ('/{id}'		, [EngineController::class, 'show'   ]);
            Route::post  ('/update/{id}', [EngineController::class, 'update' ]);
            Route::delete('/delete/{id}', [EngineController::class, 'destroy']);
        });

        // Prefix pour Motorization
        Route::prefix('motorizations')->group(function () {
            Route::get   ('/all'		, [MotorizationController::class, 'index'  ]);
            Route::post  ('/create'		, [MotorizationController::class, 'store'  ]);
            Route::get   ('/{id}'		, [MotorizationController::class, 'show'   ]);
            Route::post  ('/update/{id}', [MotorizationController::class, 'update' ]);
            Route::delete('/delete/{id}', [MotorizationController::class, 'destroy']);
        });

        // Prefix pour Own
        Route::prefix('owns')->group(function () {
            Route::get   ('/all'		            , [OwnController::class, 'index'  ]);
            Route::post  ('/create'		            , [OwnController::class, 'store'  ]);
            Route::get   ('/{id}'		            , [OwnController::class, 'show'   ]);
            Route::post  ('/update/{id}'            , [OwnController::class, 'update' ]);
            Route::delete('/delete/{userId}/{carId}', [OwnController::class, 'destroy']);
        });

        // Prefix pour Power
        Route::prefix('powers')->group(function () {
            Route::get   ('/all'		, [PowerController::class, 'index'  ]);
            Route::post  ('/create'		, [PowerController::class, 'store'  ]);
            Route::get   ('/{id}'		, [PowerController::class, 'show'   ]);
            Route::post  ('/update/{id}', [PowerController::class, 'update' ]);
            Route::delete('/delete/{id}', [PowerController::class, 'destroy']);
        });

        // Routes pour les like
        Route::post('/like/{carId}',               [LikeController::class, 'like'            ]);
        Route::post('/unlike/{carId}',             [LikeController::class, 'unlike'          ]);
        Route::get('/likes-by-car',                [LikeController::class, 'getLikesByCar'   ]);
        Route::get('/likes-by-cars-user/{userId}', [LikeController::class, 'getLikesByUserId']);
    });
});