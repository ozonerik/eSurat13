<?php

use App\Models\JenisSurat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/templates/{jenisSurat}/download', function (JenisSurat $jenisSurat) {
    $templatePath = $jenisSurat->template_path;

    if (blank($templatePath)) {
        abort(404);
    }

    $publicPath = storage_path('app/public/'.$templatePath);
    if (is_file($publicPath)) {
        return response()->download($publicPath, basename($templatePath));
    }

    $privatePath = storage_path('app/private/'.$templatePath);
    if (is_file($privatePath)) {
        return response()->download($privatePath, basename($templatePath));
    }

    abort(404);
})->middleware('auth')->name('templates.download');
