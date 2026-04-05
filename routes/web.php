<?php

use App\Models\JenisSurat;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $sekolah = Sekolah::first() ?? new Sekolah();
    return view('welcome', compact('sekolah'));
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
