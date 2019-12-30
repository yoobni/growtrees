<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Lib\ImageCropUtils;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{

    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
    	$rules = [
    	    'name' => 'required|regex:[^[가-힣]+$]|min:2|max:6',
            'nickname' => 'required|unique:users|min:4|max:30',
            'email' => 'required|email|max:200',
            'phone' => 'required|regex:[[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}]',
            'user_id' => 'required|unique:users|min:4|max:30',
            'password' => 'required|min:6|max:30|confirmed'
    	];

    	if(isset($data['profile_image'])) {
    	    $rules['profile_image'] = 'bail|image|dimensions:min_width=100,min_height=100|max:20480';
    	}

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
	        'nickname' => $data['nickname'],
            'email' => $data['email'],
	        'phone' => $data['phone'],
	        'user_id' => $data['user_id'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function registered(Request $request, User $user)
    {
        ImageCropUtils::updateProfileImage($request, $user);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        $this->guard()->login($user);
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
