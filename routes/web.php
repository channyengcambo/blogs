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


use App\Http\Controllers\AuthorPostController;

Route::get('/dashboard', function () {
    if (auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('author.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Author Portal for writing and managing personal articles
    Route::get('/author/dashboard', [AuthorPostController::class, 'dashboard'])->name('author.dashboard');
    Route::get('/author/posts/create', [AuthorPostController::class, 'create'])->name('author.posts.create');
    Route::post('/author/posts', [AuthorPostController::class, 'store'])->name('author.posts.store');
    Route::get('/author/posts/{post}/edit', [AuthorPostController::class, 'edit'])->name('author.posts.edit');
    Route::put('/author/posts/{post}', [AuthorPostController::class, 'update'])->name('author.posts.update');
    Route::delete('/author/posts/{post}', [AuthorPostController::class, 'destroy'])->name('author.posts.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
