<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'create'])->name('create.post');
Route::post('/',[PostController::class, 'store'])->name('store.post');
Route::get('/post', [PostController::class, 'index'])->name('show.post');
Route::get('/post/{id}', [PostController::class, 'edit'])->name('edit.post');
Route::put('/post/{id}', [PostController::class, 'update'])->name('update.post');