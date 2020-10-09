<?php

use Illuminate\Support\Facades\Route;

Route::get('/hue/auth', 'Philips\Hue\Http\Controllers\HueController@auth');
Route::get('/hue/auth/receive', 'Philips\Hue\Http\Controllers\HueController@receive');
