<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PictureController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index');
});



Route::prefix('albums')->group(function () {
    Route::get('/', [AlbumController::class, 'index'])->name('albums.index');

    Route::post('/', [AlbumController::class, 'store'])->name('albums.store');
    Route::get('/{album}', [AlbumController::class, 'show'])->name('albums.show');
    Route::get('/edit/{id}', [AlbumController::class, 'edit'])->name('albums.edit');
    Route::post('/update', [AlbumController::class, 'update'])->name('albums.update');

    Route::delete('delete/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy');
    Route::delete('{album}/deleteAllPictures', [AlbumController::class, 'deleteAllPictures'])->name('albums.deleteAllPictures');
    Route::post('{album}/movePictures',  [AlbumController::class, 'movePicturesToAnotherAlbum'])->name('albums.movePicturesToAnotherAlbum');

});

Route::prefix('pictures')->group(function () {

    Route::get('/', [PictureController::class, 'index'])->name('pictures.index');
    Route::get('/create', [PictureController::class, 'create'])->name('pictures.create');
    Route::post('/', [PictureController::class, 'store'])->name('pictures.store');
    Route::get('/edit/{id}', [PictureController::class, 'edit'])->name('pictures.edit');
    // Route::put('/update/{id}', [PictureController::class, 'update'])->name('pictures.update');

    Route::put('/update', [PictureController::class, 'update'])->name('pictures.update');


    Route::delete('delete/{picture}', [PictureController::class, 'destroy'])->name('pictures.destroy');


    Route::delete('/{picture}', [PictureController::class, 'destroy'])->name('pictures.destroy');
});
