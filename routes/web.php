<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/create', function () {
    return view('create');
})->name('create');

Route::get('/p/{slug}', function (string $slug) {
    return view('poll', ['slug' => $slug]);
})->name('poll.view');

Route::get('/p/{slug}/edit/{token}', function (string $slug, string $token) {
    return view('poll-edit', ['slug' => $slug, 'token' => $token]);
})->name('poll.edit');
