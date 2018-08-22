<?php

namespace App\Http\Controllers;

use App\Message;
use App\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Events\MessageSent;

class ChatsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show chats
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('chat');
  }

  /**
   * Fetch all messages
   *
   * @return Message
   */
  public function fetchMessages($auction_id)
  {
    $messages = Message::with('user')->get();
    $response = array();
    foreach($messages as $message){
      if($auction_id == $message->auction_id){
          if($message->other_user_id == ""){
            $message->other_user_id = "Everyone";
          }
          $response[$message->other_user_id][] = $message;
      }
    }
    return $response;
  }

  /**
   * Persist message to database
   *
   * @param  Request $request
   * @return Response
   */
  public function sendMessage(Request $request)
  {
    $user = Auth::user();
    $auction = Auction::find($request->auction);
    $message = new Message();
    $message->message = $request->input('message');
    $message->auction()->associate($auction);
    $message_type = "chat";
    if($request->other_user_id != "Everyone"){
      $message->other_user_id = $request->other_user_id;
      $message_type = "private";
    }
    $new_message = $user->messages()->save($message);
    broadcast(new MessageSent($user, $message, $message_type))->toOthers();
    $message = Message::find($new_message->id);
    $message->user->name = $new_message->user->name;
    return $message;
  }
}
