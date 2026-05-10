<?php

use App\Models\JenisSurat;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;

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

Route::get('/admin/optimize', function () {
    /** @var User|null $user */
    $user = Auth::user();

    try {

        try {
            Artisan::call('filament:optimize');
        } catch (Throwable $throwable) {
            $message = $throwable->getMessage();

            $isMissingIconsNamespace = str_contains($message, '"icons" namespace');
            $isFilamentOptimizeNotDefined = str_contains($message, 'Command "filament:optimize" is not defined');

            if (! $isMissingIconsNamespace && ! $isFilamentOptimizeNotDefined) {
                throw $throwable;
            }
        }

        Artisan::call('storage:link');
        Artisan::call('optimize:clear');
        Artisan::call('permission:cache-reset');

        Notification::make()
            ->title('Optimize selesai.')
            ->success()
            ->send();

        return redirect('/admin');
    } catch (Throwable $throwable) {
        Log::error('Optimize route command failed.', [
            'exception' => $throwable,
        ]);

        Notification::make()
            ->title('Optimize error')
            ->body($throwable->getMessage())
            ->danger()
            ->send();

        return redirect('/admin');
    }
})->middleware('auth')->name('optimize.route');
