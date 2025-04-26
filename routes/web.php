<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/katalog', function () {
    return view('katalog');
});

Route::get('/footer', function () {
    return view('base.footer');
});