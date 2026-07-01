<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/folders/{folder}', [DashboardController::class, 'folder'])->name('folders.show');
    Route::get('/favorites', [DashboardController::class, 'favorites'])->name('favorites');
    Route::get('/trash', [DashboardController::class, 'trash'])->name('trash');

    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::patch('/folders/{folder}', [FolderController::class, 'update'])->name('folders.update');
    Route::patch('/folders/{folder}/favorite', [FolderController::class, 'favorite'])->name('folders.favorite');
    Route::patch('/folders/{folder}/move', [FolderController::class, 'move'])->name('folders.move');
    Route::post('/folders/{folder}/copy', [FolderController::class, 'copy'])->name('folders.copy');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/trash/folders/{folder}/restore', [FolderController::class, 'restore'])->name('folders.restore');
    Route::delete('/trash/folders/{folder}/force', [FolderController::class, 'forceDestroy'])->name('folders.force-delete');

    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/{file}', [FileController::class, 'show'])->name('files.show');
    Route::get('/files/{file}/content', [FileController::class, 'content'])->name('files.content');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::patch('/files/{file}', [FileController::class, 'update'])->name('files.update');
    Route::patch('/files/{file}/favorite', [FileController::class, 'favorite'])->name('files.favorite');
    Route::patch('/files/{file}/move', [FileController::class, 'move'])->name('files.move');
    Route::post('/files/{file}/copy', [FileController::class, 'copy'])->name('files.copy');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::post('/trash/files/{file}/restore', [FileController::class, 'restore'])->name('files.restore');
    Route::delete('/trash/files/{file}/force', [FileController::class, 'forceDestroy'])->name('files.force-delete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
