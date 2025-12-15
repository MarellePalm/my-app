<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use PHPUnit\Metadata\PostCondition;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified']) ->group(function(){
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::resource('authors', AuthorController::class);
    Route::post('/add-comment/{post}', [CommentController:: class, 'store']) ->name('comments.add');

});



Route:: get('/test', function () {
    return 'tere';

});

Route::middleware('auth')->post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store');



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/posts.php';
require __DIR__. '/authors.php';

