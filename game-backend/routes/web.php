<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Route login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::get('/main', function (){
    return redirect('http://localhost:3000');
});

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin,developer'])->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
        //game manaagement
        Route::get('/game', [GameController::class, 'game']);
        Route::post('/add-game', [GameController::class, 'gamePost']);
        Route::get('/get-game/{id}', [GameController::class, 'getGame']);
        Route::put('/update-game', [GameController::class, 'updateGame']);
        Route::delete('/delete-game', [GameController::class, 'gameDelete']);
    });
    Route::middleware('role:admin')->group(function (){
        //log login
        Route::get('/log-login', [AuthController::class, 'loginHistory']);

        //user management
        Route::get('/users', [ManagementController::class, 'users']);
        Route::post('/add-user', [ManagementController::class, 'addUserPost']);
        Route::put('/update-role',[ManagementController::class, 'updateRole']);
        Route::put('/update-status',[ManagementController::class, 'updateStatus']);
        Route::delete('/delete-user', [ManagementController::class, 'userDelete']);

        //caterory management
        Route::get('/category', [ManagementController::class, 'category']);
        Route::post('/add-category', [ManagementController::class, 'addCategory']);
        Route::put('/update-category', [ManagementController::class, 'updateCategory']);
        Route::delete('/delete-category', [ManagementController::class, 'deleteCategory']);
    });
    Route::get('/logout', [AuthController::class, 'logout']);
});
// Route logout







// // Add a named login route that redirects to React
// Route::get('/login', function() {
//     return redirect('http://localhost:5173/#login');
// })->name('login');

// // Route for unauthorized access
// Route::get('/unauthorized', function (){
//     return response()->json([
//         'status' => 'error',
//         'message' => 'Unauthorized',
//         'redirect' => 'http://localhost:5173/#login'
//     ], 401);
// });

// // Tambahkan route check-session di sini
// Route::get('/check-session', function() {
//     if (Auth::check()) {
//         return response()->json([
//             'authenticated' => true,
//             'user' => Auth::user()
//         ]);
//     } else {
//         return response()->json([
//             'authenticated' => false
//         ]);
//     }
// });

// // Redirect all unauthorized access to login page
// Route::fallback(function () {
//     return redirect('http://localhost:5173/#login');
// });
