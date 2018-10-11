<?php

use App\Test;
use Illuminate\Http\Request;

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

Route::post('/', function (Request $request) {

	return json_encode($request->body);

   	$test = Test::create([
   		'body' => json_encode( $request->body )
   	]);

   	return $test->body;
});
