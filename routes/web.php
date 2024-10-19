<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify', function () {
    return response()->json('Email Verification was sent to your email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json(['message' => 'Email successfully verified.']);
})->middleware(['auth:api', 'signed'])->name('verification.verify');
