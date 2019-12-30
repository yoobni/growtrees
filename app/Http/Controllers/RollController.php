<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Roll;
use App\Http\Requests;

class RollController extends Controller
{
    private function validateText($text)
    {
        if(mb_strlen($text) > 13) {
                return response()->json('13자 미만 입력', 422);
        }

	return false;
    }

    public function store(Request $request)
    {
	$text = trim($request->input('roll'));
	$error = $this->validateText($text);
	if($error !== false) {
		return $error;
	}

	$project_id = decrypt($request->input('key_p'));
        $user_id = decrypt($request->input('key_u'));
	$roll = Roll::where([
		[ 'project_id', '=', $project_id ],
		[ 'user_id', '=', $user_id ],
	])->first();

	$len = count(explode('/*/', $roll->roll))-1;
	if($len == 5) {
		return response()->json('역할은 5개까지 추가 가능합니다', 422);
	}

	$roll->roll .= $text . '/*/';
	$roll->save();

	return $text;
    }

    public function updateRoll(Request $request) {
	$text = trim($request->input('roll'));
        $error = $this->validateText($text);
        if($error !== false) {
                return $error;
      	}

	$project_id = decrypt($request->input('key_p'));
        $user_id = decrypt($request->input('key_u'));
        $roll = Roll::where([
                [ 'project_id', '=', $project_id ],
                [ 'user_id', '=', $user_id ],
        ])->first();
	
	$rollArr = explode('/*/', $roll->roll);
	array_pop($rollArr);

	$rollArr[$request->input('idx')] = $text;
	$roll->roll = join('/*/', $rollArr) . '/*/';
	if($text == '') {
		$roll->roll = str_replace('/*//*/', '/*/', $roll->roll);
	}
	$roll->save();	

	return response()->json([
		'text' => $text,
		'idx' => $request->input('idx')
	]);
    }
}
