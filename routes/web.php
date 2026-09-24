<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

// Public Blog Frontend Routes
Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/posts/{slug}', [BlogController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/like', [BlogController::class, 'like'])->name('posts.like');
Route::post('/newsletter/subscribe', [BlogController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/category/{slug}', fn(string $slug) => redirect()->route('home', ['category' => $slug]))->name('frontend.category');
Route::get('/tag/{slug}', fn(string $slug) => redirect()->route('home', ['tag' => $slug]))->name('frontend.tag');


Route::get('/dashboard', function () {
    return view('/admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
