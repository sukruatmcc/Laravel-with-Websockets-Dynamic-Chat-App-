<?php

namespace App\Http\Controllers;

use App\Events\ChatSystemEvent;
use App\Events\MessageDeletedEvent;
use App\Events\MessageUpdatedEvent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loadDashboard()
    {
        $users = User::whereNotIn('id',[ auth()->user()->id])->get();
        return view('dashboard',compact('users'));
    }

    public function saveChat(Request $request)
    {
        try{
            $chat = Chat::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message
            ]);

            event(new ChatSystemEvent($chat));

            return response()->json([ 'success' => true, 'data' => $chat]);
        } catch(\Exception $e){
            return response()->json([ 'success' => false, 'msg' => $e->getMessage() ]);
        }
    }

    public function loadChats(Request $request)
    {
        try{
            $chats =Chat::where(function($query) use ($request){
                 $query->where('sender_id','=',$request->sender_id)
                   ->orWhere('sender_id','=',$request->receiver_id);
            })->where(function($query) use($request){
                $query->where('receiver_id','=',$request->sender_id)
                ->orWhere('receiver_id','=',$request->receiver_id);
            })->get();

            return response()->json([ 'success' => true, 'data' => $chats]);
        } catch(\Exception $e){
            return response()->json([ 'success' => false, 'msg' => $e->getMessage() ]);
        }
    }

    public function deleteChat(Request $request)
    {
        try{
            Chat::where('id',$request->id)->delete();

            event(new MessageDeletedEvent($request->id));

            return response()->json([ 'success' => true, 'msg' => 'Chat Deleted Successfully']);
        } catch(\Exception $e){
            return response()->json([ 'success' => false, 'msg' => $e->getMessage() ]);
        }
    }

    public function updateChat(Request $request)
    {
        try{
            Chat::where('id',$request->id)->update([
                'message' => $request->message
            ]);

            $chat = Chat::where('id',$request->id)->first();

            event(new MessageUpdatedEvent($chat));

            return response()->json([ 'success' => true, 'msg' => 'Chat Updated Successfully']);
        }catch(\Exception $e){
            return response()->json([ 'success' => false, 'msg' => $e->getMessage() ]);
        }
    }
}
