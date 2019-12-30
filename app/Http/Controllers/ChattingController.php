<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chatting;
use App\User;
use App\Http\Requests;

class ChattingController extends Controller
{
    public function store(Request $request)
    {
		$msg = $request->input('message');
		if(!$msg) {
			return response()->json('failed', 422);
		}
		else if(mb_strlen($msg) > 255) {
			$msg = mb_substr($msg, 0, 255);
		}

		$chat = new Chatting;
		$chat->project_id = decrypt($request->input('key_p'));
		$chat->user_id = decrypt($request->input('key_u'));
		$chat->message = $msg;
		$chat->save();

	   	return response()->json([
		    'project_id' => decrypt($request->input('key_p')),
		    'user_id' => decrypt($request->input('key_u')),
		    'message' => $request->input('message')
		], 200);
    }

    public function getNewList(Request $request)
    {
		$project_id = decrypt($request->input('key_p'));
		$list = Chatting::select(['user_id', 'message'])->where('project_id', '=', $project_id)->get();
		for($i=0; $i<count($list); $i++) {
			$user_id = $list[$i]['user_id'];
			$result = User::select(['name'])->where('id', $user_id)->first();
			
			$list[$i]['name'] = $result['name'];
		}
		
		return response()->json($list, 200);
    }
}
