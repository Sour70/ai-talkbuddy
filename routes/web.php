<?php

use App\Http\Controllers\AiResponseController;
use App\Http\Controllers\ConversationController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/',[ConversationController::class,'index'])->name('conversation');
Route::post('/ai-response',[AiResponseController::class,'index'])->name('ai-response');
