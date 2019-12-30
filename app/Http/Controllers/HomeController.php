<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\ImageCropUtils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\User;
use App\Project;
use Validator;

class HomeController extends Controller
{
	private function getJoinRequests(User $user, & $projects) {
		for ($i=0; $i<count($projects); $i++) {
			if($user->id == $projects[$i]['author']) {
				$result = Project::where([
					['name', '=', $projects[$i]['name']],
					['author', '=', $projects[$i]['author']],
				])->first();

				$request_id = $result['requests'];
				if(!$request_id) {
					continue;
				}

				$request_id = explode('n', $request_id);
				$requests = array();
				for($j=0; $j<count($request_id)-1; $j++) {
					$requests[$j] = User::select(['id', 'name', 'nickname'])
											->where('id', $request_id[$j])->first();
				}

				$projects[$i]['requests'] = $requests;
			}
		}	
	}

    public function index () {
		$user = Auth::user();
		$projects = Project::select(['name', 'author', 'members', 'token'])
						->where('members', 'like', '%'.$user->id.'n%')->get();
		
		$this->getJoinRequests($user, $projects);
		
		return view('home', compact('user', 'projects'));
    }

    private function rule (string $name) {
		switch($name) {
		    case 'nickname':
				return ['nickname' => 'required|unique:users|min:4|max:30'];
		    case 'email':
				return ['email' => 'required|email|max:200'];
	        case 'phone':
				return ['phone' => 'required|regex:[[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}]'];
		    case 'profile_image':
				return ['profile_image' => 'bail|image|dimensions:min_width=100,min_height=100|max:20480'];
		}	
    }

    private function validateUpdateData (Request $request, array $update) {
		foreach($update as $name => $value) {
	    	if($value) {
				$this->validate($request, $this->rule($name));
	    	}
		}
    }
    private function getUpdateFields(Request $request) {
    	$needUpdate = array(
		    'nickname' => true,
		    'phone' => true,
		    'email' => true,
		    'profile_image' => true,
		);

		if(strpos($request->input('nickname'), '기존 닉네임 : ') !== false) {
		    $needUpdate['nickname'] = false;
		}
		if(strpos($request->input('phone'), '기존 연락처 : ') !== false) {
		    $needUpdate['phone'] = false;
		}
		if(strpos($request->input('email'), '기존 이메일 : ') !== false ) {
		    $needUpdate ['email'] = false;
		}
		$needUpdate['profile_image'] = $request->hasFile('profile_image');

		return $needUpdate;
    }

    private function updateNickname (Request $request, User $user) {
		$oldNick = $user->nickname;
		$user->nickname = $request->input('nickname');
	
		$PATH = public_path('storage/profile_imgs/');
		rename($PATH . $oldNick.'.img', $PATH . $user->nickname.'.img');
    }
    private function updatePhone (Request $request, User $user) {
		$oldPhone = $user->phone;
		$user->phone = $request->input('phone');
    }
    private function updateEmail (Request $request, User $user) {
		$oldEmail = $user->email;
		$user->email = $request->input('email');
    }
    private function updateProfileImage (Request $request, User $user) {
    	ImageCropUtils::updateProfileImage($request, $user);
    }

    private function updateFields(User $user, Request $request, array $needUpdate) 
    {
    	if($needUpdate['nickname']) { $this->updateNickname($request, $user); }
		if($needUpdate['phone']) { $this->updatePhone($request, $user); }
		if($needUpdate['email']) { $this->updateEmail($request, $user); }
		if($needUpdate['profile_image']) { $this->updateProfileImage($request, $user); }

		$user->save();
    }

    public function updateUserInfo (Request $request) {
		// password confirmation
		$user = Auth::user();	
		if (!Hash::check($request->input('password'), Auth::user()->password)) { 
		    return response()->json([
				'password' => array('비밀번호가 일치하지 않음')
		    ], 422);
		}

		$needUpdate = $this->getUpdateFields($request);

		$this->validateUpdateData($request, $needUpdate);
		$this->updateFields($user, $request, $needUpdate);
		
		return response()->json([
			'id' => $user->id,
			'nickname' => $user->nickname,
			'phone' => $user->phone,
			'email' => $user->email
		], 200);
    }
}
