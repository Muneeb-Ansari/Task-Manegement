<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, PusherController, TaskController, ProfileController};

require __DIR__ . '/auth.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {

    // Admin-only routes
    Route::resource('tasks', TaskController::class)
        ->only(['create', 'store', 'destroy'])->middleware('role:admin');

    Route::resource('tasks', TaskController::class)
        ->except(['create', 'store', 'destroy']);
});

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
});

// ChatController
Route::middleware('auth')->group(function () {
    Route::get('/chats', [PusherController::class, 'index'])->name('chat.index');
    Route::post('/broadcast', [PusherController::class, 'broadcast']);
    Route::post('/receive', [PusherController::class, 'receive']);
});

// notifications

Route::get('/notifications', function () {
    $notifications = auth()->user()->unreadNotifications()->paginate(10);
    return view('notifications.notification', compact('notifications'));
})->name('notifications.notification');

Route::post('/notifications/{id}/read', function ($id) {
    $n = auth()->user()->notifications()->where('id', $id)->firstOrFail();
    $n->markAsRead();
    return back();
})->name('notifications.read');
