<?php

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

Route::get('/', function () {
    $page_id = rand(pow(10, 4 - 1), pow(10, 4) - 1);

    return redirect('/' . $page_id);
});

Route::get('/{page_id}', function($page_id) {
	return view('page', ['page_id' => $page_id] );
});

// Route for index page
Route::get('comments/{page_id}', 'CommentController@index');

// Route for store comment
Route::post('comments', 'CommentController@store');