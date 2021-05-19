<?php

Route::view('/', 'pages.registration')->name('registration');
Route::view('/winner', 'pages.winner')->name('page.winner');
//Route::view('/codes', 'pages.codes');
Route::view('/delete-participants', 'pages.delete-participants');