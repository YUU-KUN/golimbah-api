<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\UserGameSessionController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\LeaderboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:passport')->get('/user', function (Request $request) {
    return $request->user();
});

// Public Can Access
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Guest
Route::post('search-game', [GameSessionController::class, 'searchGameSession']);
Route::post('join-game', [GameSessionController::class, 'joinGame']);
Route::post('finish-game', [GameSessionController::class, 'finishGame']);
Route::get('get-participants/{game_session_id}', [GameSessionController::class, 'getParticipants']);
Route::get('game-sessions/{game_session_id}', [GameSessionController::class, 'show']);
Route::get('user-game-sessions/{game_session_id}', [UserGameSessionController::class, 'show']);
Route::get('guest-leaderboard/{game_session_id}', [LeaderboardController::class, 'getGuestLeaderboard']);
Route::get('get-trash/{game_session_id}', [TrashController::class, 'getRandomTrashByGameMode']);
Route::resource('trash', TrashController::class);

// All Authenticated User Can Access
Route::middleware(['auth:api'])->group(function () {
    Route::get('profile', [AuthController::class, 'getProfile']);
    Route::resource('game-sessions', GameSessionController::class)->except(['show']);
    Route::resource('leaderboard', LeaderboardController::class);
});

// Only Admin Can Access
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('start-game', [GameSessionController::class, 'startGame']);
});

// Only User Can Access

