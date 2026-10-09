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

Route::middleware('throttle:60,1')->group(function () {
    // Routes d'authentification
    Route::prefix('auth')->group(function () {
        Route::post('/login'		  , [AuthController::class, 'login'			]);
        Route::post('/register'		  , [AuthController::class, 'register'		]);
        Route::post('/demo'			  , [AuthController::class, 'demo'			]);
    });

    // Routes protégées par Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/user'  , [AuthController::class, 'user'  ]);
        
        // Routes pour User : gestion réservée au superAdmin, le détail est vérifié dans le controller
        Route::prefix('users')->group(function () {
            Route::get	 ('/all'			, [UserController::class, 'index'		  ])->middleware('check.role:superAdmin');
            Route::post	 ('/create'			, [UserController::class, 'store'		  ])->middleware('check.role:superAdmin');
            Route::get	 ('/{id}'			, [UserController::class, 'show'		  ]);
            Route::post	 ('/update/{id}'	, [UserController::class, 'update'		  ])->middleware('check.role:superAdmin');
            Route::delete('/delete/{id}'	, [UserController::class, 'destroy'		  ])->middleware('check.role:superAdmin');
            Route::post	 ('/update-pwd/{id}', [UserController::class, 'updatePassword'])->middleware('check.role:superAdmin');
        });

        // Routes pour Car : lecture pour tous, écriture réservée aux admins
        Route::prefix('cars')->group(function () {
            Route::get	 ('/all'		, [CarController::class, 'index' 			 ]);
            Route::get	 ('/{id}'		, [CarController::class, 'show'  			 ]);
            Route::get	 ('/detail/{id}', [CarController::class, 'getDetailsByCarId' ]);
            Route::get	 ('/user/{id}'  , [OwnController::class, 'getCarsByUserId'	 ]);

            Route::middleware('check.role:admin,superAdmin')->group(function () {
                Route::post	 ('/create'		, [CarController::class, 'store'  ]);
                Route::post	 ('/update/{id}', [CarController::class, 'update' ]);
                Route::delete('/delete/{id}', [CarController::class, 'destroy']);
            });
        });

        // Routes pour Own : un utilisateur gère sa propre collection (vérifié dans le controller)
        Route::prefix('owns')->group(function () {
            Route::post  ('/create'		            , [OwnController::class, 'store'  ]);
            Route::delete('/delete/{userId}/{carId}', [OwnController::class, 'destroy']);

            Route::middleware('check.role:admin,superAdmin')->group(function () {
                Route::get   ('/all'		, [OwnController::class, 'index'  ]);
                Route::get   ('/{id}'		, [OwnController::class, 'show'   ]);
                Route::post  ('/update/{id}', [OwnController::class, 'update' ]);
            });
        });

        // Routes pour les like
        Route::post('/like/{carId}',               [LikeController::class, 'like'            ]);
        Route::post('/unlike/{carId}',             [LikeController::class, 'unlike'          ]);
        Route::get('/likes-by-car',                [LikeController::class, 'getLikesByCar'   ]);
        Route::get('/likes-by-cars-user/{userId}', [LikeController::class, 'getLikesByUserId']);

        // Routes du dashboard : réservées aux admins
        Route::middleware('check.role:admin,superAdmin')->group(function () {
            // Routes pour Edition
            Route::prefix('editions')->group(function () {
                Route::get   ('/all'		, [EditionController::class, 'index'  ]);
                Route::post  ('/create'		, [EditionController::class, 'store'  ]);
                Route::get   ('/{id}'		, [EditionController::class, 'show'   ]);
                Route::post  ('/update/{id}', [EditionController::class, 'update' ]);
                Route::delete('/delete/{id}', [EditionController::class, 'destroy']);
            });

            // Routes pour Engine
            Route::prefix('engines')->group(function () {
                Route::get   ('/all'		, [EngineController::class, 'index'  ]);
                Route::post  ('/create'		, [EngineController::class, 'store'  ]);
                Route::get   ('/{id}'		, [EngineController::class, 'show'   ]);
                Route::post  ('/update/{id}', [EngineController::class, 'update' ]);
                Route::delete('/delete/{id}', [EngineController::class, 'destroy']);
            });

            // Routes pour Motorization
            Route::prefix('motorizations')->group(function () {
                Route::get   ('/all'		, [MotorizationController::class, 'index'  ]);
                Route::post  ('/create'		, [MotorizationController::class, 'store'  ]);
                Route::get   ('/{id}'		, [MotorizationController::class, 'show'   ]);
                Route::post  ('/update/{id}', [MotorizationController::class, 'update' ]);
                Route::delete('/delete/{id}', [MotorizationController::class, 'destroy']);
            });

            // Routes pour Power
            Route::prefix('powers')->group(function () {
                Route::get   ('/all'		, [PowerController::class, 'index'  ]);
                Route::post  ('/create'		, [PowerController::class, 'store'  ]);
                Route::get   ('/{id}'		, [PowerController::class, 'show'   ]);
                Route::post  ('/update/{id}', [PowerController::class, 'update' ]);
                Route::delete('/delete/{id}', [PowerController::class, 'destroy']);
            });
        });
    });
});