<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// user nav items

Route::get('settings', 'UserController@showSettings');

// Route::get('auctions', 'AuctionController@list');
Route::get('auction/{id}', 'AuctionController@show');
Route::get('auctions/new', 'AuctionController@new');

Route::post('auctions/store','AuctionController@store');
Route::post('auctions/join','AuctionController@join');


Route::get('chat', 'ChatsController@index');
Route::get('messages', 'ChatsController@fetchMessages');
Route::post('messages', 'ChatsController@sendMessage');


// Route::get('auctions',function(){
//   $auctions = Statsational\Auction::all();
//
//   return View::make('pages.auctions')->with('auctions',$auctions);
// });


// Route::get('/profile/{username}',function($username){
//   $user = Statsational\User::where('name',$username)->first();
//   // $user = Auth::User();
//   // var_dump($user);
//   return View::make('pages.profile')->with('user',$user);
// });
