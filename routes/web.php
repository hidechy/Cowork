<?php

Route::get('/', 'Cowork\CoworkController@index');
Route::post('/hide', 'Cowork\CoworkController@hide');
Route::get('/test', 'Cowork\CoworkController@test');

Route::post('/callpref', 'Cowork\CoworkController@callpref');
Route::post('/callline', 'Cowork\CoworkController@callline');
Route::post('/callstation', 'Cowork\CoworkController@callstation');

Route::post('/searchresult', 'Cowork\CoworkController@searchresult');

Route::get('/pref/{pref}', 'Cowork\CoworkController@pref');

Route::get('/city/{city}', 'Cowork\CoworkController@city');
Route::get('/station/{station}', 'Cowork\CoworkController@station');
