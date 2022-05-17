<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');


Route::prefix('bucket')->group(function() {
    Route::get('/criar', [\App\Http\Controllers\BucketController::class, 'create'])->name('bucket.create');
    Route::post('/salvar', [\App\Http\Controllers\BucketController::class, 'store'])->name('bucket.store');
    Route::get('/{name}', [\App\Http\Controllers\BucketController::class, 'view'])->name('bucket.view');
    Route::get('/{name}/deletar', [\App\Http\Controllers\BucketController::class, 'delete'])->name('bucket.delete');
    Route::post('/{name}/objeto/salvar', [\App\Http\Controllers\ObjectController::class, 'store'])->name('bucket.object.store');
    Route::get('/{name}/objeto/{key}/download', [\App\Http\Controllers\ObjectController::class, 'download'])->name('bucket.object.download');
    Route::get('/{name}/objeto/{key}/deletar', [\App\Http\Controllers\ObjectController::class, 'delete'])->name('bucket.object.delete');
});
