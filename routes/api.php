<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('tasks', TaskController::class);
// });
Route::get('tasks', [TaskController::class, 'index']); // GET all tasks (public)

Route::middleware(['admin'])->group(function () {
    Route::post('/tasks', [TaskController::class, 'store']); // CREATE task (admin only)
    Route::put('/tasks/{id}', [TaskController::class, 'update']); // UPDATE task (admin only)
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']); // DELETE task (admin only)
});
Route::get('/tasks/{id}', [TaskController::class, 'show']); // GET single task (public)

// Route::get('test', function () {
//     return response()->json(['message' => 'API working']);
// });
