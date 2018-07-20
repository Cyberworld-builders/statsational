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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => 'auth'],function(){
  // user nav items
  Route::get('settings', 'UserController@showSettings');

  // Route::get('auctions', 'AuctionController@list');
  Route::get('auction/{id}', 'AuctionController@show');
  Route::get('auction/data/{id}', 'AuctionController@getAuctionData');
  Route::post('auction/bidder/data', 'AuctionController@getBidderData');
  Route::get('auctions/new', 'AuctionController@new');

  Route::post('auctions/store','AuctionController@store');
  Route::post('auctions/join','AuctionController@join');
  Route::post('auctions/addItem','AuctionController@addItem');
  Route::post('auctions/items/next','AuctionController@startNextItem');

  Route::get('auctions/item/switch/{id}','ItemController@switchItem');



  Route::post('auctions/bid','AuctionController@bid');

  Route::post('auctions/timer','AuctionController@resetTimer');
  Route::post('auctions/status','AuctionController@setStatus');



  Route::get('auction/messages/{id}', 'ChatsController@fetchMessages');
  Route::post('messages', 'ChatsController@sendMessage');

  Route::get('auction/users/{id}', 'AuctionController@getUsers');

  Route::get('grid-test', function(){
    return View::make('pages.grid');
  });



});




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
