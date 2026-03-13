<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Mail\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;



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

