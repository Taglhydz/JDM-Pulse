<?php

use Illuminate\Support\Facades\Route;

// En production, le build React est copié dans public/ : toutes les URL hors /api
// renvoient son index.html, et React Router affiche la bonne page
Route::get('/{any?}', function () {
    $index = public_path('index.html');
    abort_unless(file_exists($index), 404, 'Front non déployé : copier le build React dans public/');

    return response()->file($index);
})->where('any', '^(?!api(/|$)).*');
