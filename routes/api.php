<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileManagementController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Feedback\FeedbackController;
use App\Http\Controllers\Manga\MangaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verification']);


//Get all mangas
Route::get('/mangas',[MangaController::class,'index']);
// Get popular mangas
Route::get('/mangas/popular',[MangaController::class,'getPopularMangas']);
// Get latest mangas
Route::get('/mangas/latest',[MangaController::class,'getLatestMangas']);

// Get all category
Route::get('/categories',[CategoryController::class,'index']);
// Get mangas by category
Route::get('/categories/{category_id}/mangas', [CategoryController::class, 'getMangasByCategory']);

// Get all feedbacks
Route::get('/feedbacks',[FeedbackController::class,'index']);
// Store feedback
Route::post('/feedbacks/store',[FeedbackController::class,'store']);
// Delete feedback
Route::delete('/feedbacks/{id}/delete',[FeedbackController::class,'delete']);

// Get all contacts
Route::get('/contacts',[ContactController::class,'index']);
// Store contact
Route::post('/contacts/store',[ContactController::class,'store']);
// Delete contact
Route::delete('/contacts/{id}/delete',[ContactController::class,'delete']);

// Profile management 
Route::middleware('auth:api')->group(function () {
    // // Update profile
    Route::post('/profile',[ProfileManagementController::class, 'updateProfile']);
    // Verify OTP for old email
    Route::post('/email/verify-old', [ProfileManagementController::class, 'verifyOldEmailOtp']); 
    // Change email and send OTP to new email
    Route::post('/email/change', [ProfileManagementController::class, 'changeEmail']);  
    // Verify OTP for new email
    Route::post('/email/verify-new', [ProfileManagementController::class, 'verifyNewEmailOtp']);     

});
