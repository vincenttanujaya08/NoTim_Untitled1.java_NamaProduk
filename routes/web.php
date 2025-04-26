<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('base.header');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/katalog', function () {
    return view('katalog');
});