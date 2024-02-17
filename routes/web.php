<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[ChatController::class,'loadChatView']);
Route::post('/broadcast-message',[ChatController::class,'broadcastMessage'])->name('broadcastMessage');

Route::get('/dashboard',[UserController::class,'loadDashboard'])->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::post('/save-chat',[UserController::class,'saveChat'])->name('saveChat');
Route::post('/load-chats',[UserController::class,'loadChats'])->name('loadChats');
Route::post('/delete-chat',[UserController::class,'deleteChat'])->name('deleteChat');
Route::post('/update-chat',[UserController::class,'updateChat'])->name('updateChat');
