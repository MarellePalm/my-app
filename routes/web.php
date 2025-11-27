<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');


Route:: get('/test', function () {
    return 'tere';

});

Route::middleware('auth')->post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/posts.php';
require __DIR__. '/authors.php';

