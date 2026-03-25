<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Mail\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


use App\Http\Controllers\MarkerController;


Route::post('/markers', [MarkerController::class, 'store']);
Route::delete('/markers/{marker}', [MarkerController::class, 'destroy']);
Route::put('/markers/{marker}', [MarkerController::class, 'update']);

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified']) ->group(function(){
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::resource('authors', AuthorController::class);
    Route::post('/add-comment/{post}', [CommentController:: class, 'store']) ->name('comments.add');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});





Route:: get('/test', function () {
    return 'tere';

});

Route::middleware('auth')->post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('posts.comments.store');

Route::get('/mailable', function () {
    $startDate = now()->startOfWeek();
    $endDate = now()->endOfWeek();

    $response = Http::get('https://tahveltp.edu.ee/hois_back/timetableevents/timetableSearch', [
        'from' => $startDate->toISOString(),
        'lang' => 'ET',
        'page' => 0,
        'schoolId' => 38,
        'size' => 50,
        'studentGroups' => '39248890-7051-4182-9a9a-8a82259b862b',
        'thru' => $endDate->toISOString(),
    ]);

    $data = $response->json();

    $timetableEvents = collect(data_get($data, 'content', []))
        ->sortBy(['date', 'timeStart'])
        ->groupBy(function ($event) {
            return Carbon::parse($event['date'])
                ->locale('et_EE')
                ->dayName;
        });

    return new Timetable($timetableEvents, $startDate, $endDate);
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/posts.php';
require __DIR__. '/authors.php';

