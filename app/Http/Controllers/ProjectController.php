<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Project;
use App\User;
use App\Chatting;
use App\Roll;
use App\Http\Requests;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function randomString()
    {
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
    	$flag = false;

    	do {
            for ($i = 0; $i < 10; $i++) {
            	$randstring .= $characters[rand(0, strlen($characters)-1)];
            }

            if(Project::get()->where('token', '=', $randstring)->all()) {
                $flag = true;
    	    } 
    	    else {
                $flag = false;
    	    }
    	} while($flag);

        return $randstring;
    }

    private function filterBlankStore(Request $request)
    {
	    if(! strcmp($request->input('project_name'), '프로젝트 이름')) {
            return response()->json([
                'project_name' => array('프로젝트 이름 입력')
            ], 422);
        }
        else if(! strcmp($request->input('due_date'), '마감 날짜')) {
            return response()->json([
                'due_date' => array('마감 날짜 선택')
            ], 422);
        }
        else if(! strcmp($request->input('description'), '프로젝트 내용')) {
            return response()->json([
                'description' => array('프로젝트 내용 입력')
            ], 422);
        }
	    else {
	        return null;
	    }
    }

    private function validateStore(Request $request, User $user)
    {
	    $this->validate($request, [
            'project_name' => 'required|min:4|max:20',
            'due_date' => 'required|date_format:Y-m-d',
            'description' => 'required|min:10|max:500',
        ]);

	    // project project name duplication per one user
    	$flag = Project::where([
    		['name', '=', $request->input('project_name')],
    		['author', '=', $user->id],
    	])
        ->first();
    	
        if($flag != null) {
    	    return response()->json([
                'project_name' => array('중복된 이름')
            ], 422);	
	    }
    }

    private function createRoll(int $project_id, int $user_id, string $roll = '')
    {
	$newRoll = new Roll;
	
	$newRoll->project_id = $project_id;
	$newRoll->user_id = $user_id;
	$newRoll->roll = $roll;
	
	$newRoll->save();
    }

    private function createProject(Request $request, User $user)
    {
	    $project = new Project;

        $project->name = $request->input('project_name');
        $project->author = $user->id;
        $project->members = $user->id . 'n';
        $project->description = $request->input('description');
        $project->token = $this->randomString();
        $project->due_date = date('Y-m-d H:i:s', strToTime($request->input('due_date')) + 60 * 60 * 24 - 1);

        $project->save();

	$this->createRoll($project->id, $user->id);
    }

    public function store(Request $request)
    {
    	$response = $this->filterBlankStore($request);
    	if($response != null) { 
    		return $response; 
    	}

    	$user = Auth::user();

    	$response = $this->validateStore($request, $user);
    	if($response != null) {
    		return $response;
    	}

    	$this->createProject($request, $user);

    	return 'success';
    }

    // project_list/{str}
    public function getList($str) {
    	$items = Project::select(['id', 'name', 'author'])->where('name', 'like', $str . '%')->get();
    	for($i=0; $i<count($items); $i++) {
    		$user_id = $items[$i]['author'];
    		$result = User::select(['name', 'nickname'])->where('id', $user_id)->first();	

    		$profileImagePath = public_path('storage/profile_imgs/'.$user_id);
    		if(!file_exists($profileImagePath)) {
    			$profileImagePath = 'storage/profile_imgs/default';
    		}
    		else {
    			$profileImagePath = 'storage/profile_imgs/'.$user_id;
    		}

    		$items[$i]['author'] = $result['name'];
    		$items[$i]['nickname'] = $result['nickname'];
    		$items[$i]['profileImagePath'] = $profileImagePath;
    	}
    	return response()->json($items, 200);
    }
    // project_info/{projectId}
    public function getInfo($projectId) {
    	$info = Project::select(['name', 'description', 'author', 'members', 'requests'])
    			->where('id', '=', $projectId)
    			->first();
    	
    	if(strpos($info['members'], Auth::user()->id.'n') !== false) {
    		$info['joined'] = true;
    	}
    	else {
    		if(strpos($info['requests'], Auth::user()->id.'n') !== false) {
    			$info['joined'] = 'wating';
    		}
    		else {
    			$info['joined'] = false;
    		}
    	}

    	return $info;
    }

    // join_request
    public function joinRequest(Request $request) {
    	$user_id = Auth::user()->id;
    	$project = Project::where('id', $request->input('id'))->first();
    	$project->requests .= $user_id.'n';
    	$project->save();

    	return response()->json('success', 200);
    }

    // allow_request
    public function allowRequest(Request $request) {
    	$user = Auth::user();
    	$projectName = $request->input('projectName');
        $requestUid = $request->input('userId');

    	$project = Project::where([
    		['name', '=', $projectName],
    		['author', '=', $user->id]
    	])->first();

    	$requests = $project['requests'];
    	$idx = strpos($requests, $requestUid.'n');
    	$requests = substr($requests, 0, $idx) . substr($requests, $idx+strlen($requestUid.'n'));

    	$project['requests'] = $requests;
    	$project['members'] .= $requestUid . 'n';
    	$project->save();
	
	$this->createRoll($project->id, $requestUid);
    	
    	return 'success';
    }

    // deny_request
    public function denyRequest(Request $request) {
	    $user = Auth::user();
        $projectName = $request->input('projectName');
        $requestUid = $request->input('userId');

	    $project = Project::where([
                ['name', '=', $projectName],
                ['author', '=', $user->id]
        ])->first();

        $requests = $project['requests'];
        $idx = strpos($requests, $requestUid.'n');
        $requests = substr($requests, 0, $idx) . substr($requests, $idx+strlen($requestUid.'n'));

        $project['requests'] = $requests;
	    $project->save();
	
	    return 'success';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
	$this->authorize('access', $project);
	$user = Auth::user();
	
	$members = $project->members;
	$members = explode('n', $members);
	array_pop($members);

	for($i=0; $i<count($members); $i++) {
		$members[$i] = User::find($members[$i]);
		$roll = Roll::where([
			['user_id', '=', $members[$i]->id],
			['project_id', '=', $project->id],
		])->first();
	
		$roll = $roll->roll;
		$roll = explode('/*/', $roll);
		array_pop($roll);

		if($members[$i]->id == $user->id) {
			$user['roll'] = $roll;
		}

		$members[$i]['roll'] = $roll;
	}

    	/*
    	$chatting = Chatting::select(['user_id', 'message'])->where('project_id', '=', $project->id)->get();
    	for($i=0; $i<count($chatting); $i++) {
    	    $user_id = $chatting[$i]['user_id'];
    	    $result = User::select(['name'])->where('id', $user_id)->first();
    	
    	    $chatting[$i]['name'] = $result['name'];
    	}
    	*/

    	return view('project', compact('user', 'project', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
	$user = Auth::user();

	if(!Hash::check($request->input('password'), $user->password)) {
		return response()->json([
			'password' => '비밀번호가 틀렸습니다'
		], 422);	
	}

	$project = Project::find(decrypt($request->input('key_p')));
	$name = trim($request->input('project_name'));
	$due_date = trim($request->input('due_date'));
	$description = trim($request->input('description'));

	if($name == '') { $request->merge(['project_name' => $project->name]); }
	if($due_date == '') { $request->merge(['due_date' => substr($project->due_date, 0, 10)]); }
	if($description == '') { $request->merge(['description' => $project->description]); }
	
	$this->validateStore($request, $user);

	$project->name = $request->project_name;
	$project->due_date = $request->due_date;
	$project->description = $request->description;
	$project->save();
        
	return 'success';
    }

    private function hasNickname($project, $nickname) 
    {
        $members = $project->members;
        $members = explode('n', $members);
        array_pop($members);

        foreach($members as $member) {
                if($nickname == User::find($member)->nickname) {
                        return true;
                }
        }

	return false;
    }

    public function alterAdmin(Request $request) 
    {
	$user = Auth::user();
        if(!Hash::check($request->password, $user->password)) {
                return response()->json([
                        'password' => '비밀번호가 틀렸습니다'
                ], 422);
        }

        $pid = decrypt($request->key_p);
	$project = Project::find($pid);
	$nickname = $request->nickname;

	if(!$this->hasNickname($project, $nickname)) {
		return response()->json([
			'nickname' => '프로젝트의 멤버가 아닙니다'
		], 422);	
	}
	
	$newAdmin = User::where('nickname', $nickname)->first();
	$project->author = $newAdmin->id;
	$project->save();
	
	return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
    	$user = Auth::user();
        if(!Hash::check($request->password, $user->password)) {
                return response()->json([
                        'password' => '비밀번호가 틀렸습니다'
                ], 422);
        }

	$pid = decrypt($request->key_p);
	$project = Project::find($pid);
	if(strcmp($project->name, $request->name)) {
		return response()->json([
			'name' => '일치하지 않습니다'
		], 422);
	}

        Project::where([
		['id', '=', $pid]
	])->delete();
	
	return 'success';
    }
}
