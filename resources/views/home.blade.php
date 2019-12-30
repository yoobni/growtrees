@extends('layouts/master')

@push('style')
	<link rel="stylesheet" href="css/home.css">
	<link rel="stylesheet" href="/css/imagecrop/imgareaselect-animated.css">
@endpush

@push('script')
	<script src="/js/imagecrop/jquery.imgareaselect.pack.js"></script>
	<script src="js/home.js"></script>
	<script src="js/home_btn.js"></script>
@endpush

@section('meta')
	<meta name="_token" content="{{ csrf_token() }}">
@endsection

@section('header')
	<div id="header" class="container-fluid">
		<div id="logo">
			<a href="{{ route('home') }}">
				<img src="{{ asset('images/title.png') }}" alt="자라나라나무나무" width="150">
			</a>
		</div>
		<ul id="menuList">
			<div id="search" class="dropdown">
				<li class="menu" onclick="search();">
					<a href="javascript:void(0)"><img src="{{ asset('images/search.png') }}" alt="프로젝트 검색" /></a>
				</li>
			</div>
			<div id="searchlist">
				
			</div>
			<div id="profile" class="dropdown">
				<li class="menu dropdown-toggle" data-toggle="dropdown">
					<a href=""><img src="{{ asset('images/profile.png') }}" alt="" /></a>
				</li>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="#" data-toggle="modal" data-target="#profile_info">프로필 정보</a></li>
					<li><a href="#" data-toggle="modal" data-target="#profile_update">프로필 수정</a></li>
				</ul>
			</div>
			<div id="menu" class="dropdown">
				<li class="menu dropdown-toggle" data-toggle="dropdown">
					<a href=""><img src="{{ asset('images/menu.png') }}" alt="" /></a>
				</li>
				<ul class="dropdown-menu dropdown-menu-right">
					<li><a href="#" data-toggle="modal" data-target="#show_requests">요청 확인</a></li>
					<li><a href="#" data-toggle="modal" data-target="#help">도움말</a></li>
					<li><a href="{{ route('session.destroy') }}">로그아웃</a></li>
				<!--
					<li><a href="#">ㅁㄴㅁㄴㅇㅁㄴ</a></li>
					<li><a href="#">ㅁㄴㅇㅁㄴㅇㅁㄴㅇ</a></li>
				-->
				</ul>
			</div>
		</ul>
	</div>
@stop

@section('content')
	<div id="main" class="container-fluid">
		<?php
			$cnt = count($projects);
		?>
		@for ($i=0; $i<=$cnt; $i+=3)
			@if($cnt-$i <= 3)
				<div class="row @if ($i == 0) {{ 'margin' }} @endif">
					<div class="col-md-10 section">
						@for ($j=0; $j<$cnt-$i; $j++)
							<div class="col-md-4 flowerpot">
								<a href="{{ route('projects.show', $projects[$i+$j]['token']) }}">
									<img src="{{ asset('images/pot.png') }}" class="pot" alt="">
									<div class="potname">{{ $projects[$i+$j]['name'] }}</div>
								</a>
							</div>
						@endfor

						@if($cnt-$i < 3)
							<div class="col-md-4 flowerpot">
								<a href="#" data-toggle="modal" data-target="#create_project">
									<img src="{{ asset('images/addpot.png') }}" class="pot" alt="">
									<div class="potname">Add Pot!</div>
								</a>
							</div>
						@endif
					</div>
				</div>
			@else
				<div class="row @if ($i == 0) {{ 'margin' }} @endif">
					<div class="col-md-10 section">
						@for ($j=0; $j<3; $j++)
							<div class="col-md-4 flowerpot">
								<a href="{{ route('projects.show', $projects[$i+$j]['token']) }}">
									<img src="{{ asset('images/pot.png') }}" class="pot" alt="">
									<div class="potname">{{ $projects[$i+$j]['name'] }}</div>
								</a>
							</div>
						@endfor
					</div>
				</div>
			@endif
		@endfor
	</div>
@stop

@section('footer')
	<div id="profile_info" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-sm-offset-1">
	                	<button type="button" class="close" data-dismiss="modal">&times;</button>
                    	<h4 class="modal-title">내 프로필</h4>
	                </div>
				</div>
				<div class="modal-body row">
					<div class="col-sm-offset-1 col-sm-3 col-xs-12">
						<img id="profile_image" src="{{ file_exists(public_path('storage/profile_imgs/'.$user->id)) ? asset('storage/profile_imgs/'.$user->id) : asset('storage/profile_imgs/default') }}" alt="프로필 사진">
					</div>
					<div class="col-sm-8 col-xs-12">
						<div>
							<span class="green col-xs-3">이름</span> 
							<span class="col-xs-9">{{ $user->name }}</span>
						</div>
						<div>
							<span class="green col-xs-3">닉네임</span>
                        	<span class="col-xs-9">{{ $user->nickname }}</span>
                        </div>
                        <div>
                        	<span class="green col-xs-3">이메일</span> 
							<span class="col-xs-9">{{ $user->email }}</span>
                        </div>
                        <div>
                        	<span class="green col-xs-3">휴대전화</span> 
							<span class="col-xs-9">{{ $user->phone }}</span>
                        </div>
					</div>
					<!--
					<div>
						<span class="green col-sm-offset-1 col-sm-2">이름</span> 
						<span class="col-sm-5">{{ $user->name }}</span>
					</div>
					<div>
						<span class="green col-sm-offset-1 col-sm-2">닉네임</span>
                        <span class="col-sm-5">{{ $user->nickname }}</span>
					</div>
					<div>
						<span class="green col-sm-offset-1 col-sm-2">이메일</span> 
						<span class="col-sm-5">{{ $user->email }}</span>
					</div>
					<div>
						<span class="green col-sm-offset-1 col-sm-2">휴대전화</span> 
						<span class="col-sm-5">{{ $user->phone }}</span>
					</div>
					-->
				</div>
			</div>
		</div>
	</div>
	<div id="profile_update" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-sm-offset-1">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">내 프로필(수정)</h4>
                    </div>
				</div>
				<div class="modal-body">
					<form class="row">
						<label for="profile_image" class="col-sm-offset-1 col-xs-12 notice control-label"></label>
						<div id="preview_wrapper" class="col-sm-offset-1 col-sm-10 col-xs-12">
							<input type="file" name="profile_image" accept="image/*">
							<img id="preview" src="{{ file_exists(public_path('storage/profile_imgs/'.$user->id)) ? asset('storage/profile_imgs/'.$user->id) : asset('storage/profile_imgs/default') }}" alt="프로필 사진">
							<div id="tooltip">
								<span class="glyphicon glyphicon-camera"></span>
								&nbsp;클릭
							</div>
						</div>
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
							<input type="text" class="form-control" value="이름 : {{ $user->name }}" disabled>
						</div>
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
							<label for="nickname" class="col-xs-12 notice control-label"></label>
							<input type="text" class="form-control" value="기존 닉네임 : {{ $user->nickname }}" name="nickname">
						</div>
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
							<label for="email" class="notice control-label"></label>
							<input type="text" class="form-control" value="기존 이메일 : {{ $user->email }}" name="email">
						</div>
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
							<label for="phone" class="notice control-label"></label>
							<input type="text" class="form-control" value="기존 연락처 : {{ $user->phone }}" name="phone">
						</div>
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
							<label for="password" class="notice control-label"></label>
							<input type="text" class="form-control" value="비밀번호 입력" name="password">
						</div>
	
						<input type="hidden" name="x1" value="0">
						<input type="hidden" name="y1" value="0">
						<input type="hidden" name="size" value="250">

                      	<button type="button" class="btn btn-default col-xs-12">
                           	완료
                       	</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="image_edit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-sm-offset-1">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">이미지 편집</h4>
                        * 모바일 환경에서는 편집이 불가능합니다.
                    </div>
                </div>
                <div class="modal-body row">
                	<div class="col-sm-offset-1 col-sm-10 col-xs-12 text-center">
                		<img id="selected_image" src="" alt="profile_image" width="250" height="250">
                	</div>
                        
					<button class="btn btn-default col-xs-12">
                    	완료
                    </button>
	            </div>
            </div>
	    </div>
	</div>

	<div id="create_project" class="modal fade" role="dialog">
    	<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-sm-offset-1">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">프로젝트 생성</h4>
                    </div>
                </div>
                <div class="modal-body">
                	<form class="row">
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
                            <label for="project_name" class="notice control-label"></label>
                            <input type="text" class="form-control" value="프로젝트 이름" name="project_name">
                        </div>
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
                            <label for="due_date" class="notice control-label"></label>
                            <input type="text" class="form-control" value="마감 날짜" name="due_date" min="{{ date('Y-m-d') }}">
                        </div>
						<div class="form-group col-sm-offset-1 col-sm-10 col-xs-12">
							<label for="description" class="notice control-label"></label>
							<textarea class="form-control" name="description" rows="5" value="프로젝트 내용">프로젝트 내용</textarea>
						</div>

						<button type="button" class="btn btn-default col-xs-12">
                            생성
                        </button>
					</form>
                </div>
            </div>
	    </div>
    </div>
	
	<div id="project_info" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-sm-offset-1">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">프로젝트 정보</h4>
                    </div>
				</div>
				<div class="modal-body row">
					
				</div>
			</div>	
		</div>
	</div>

	<div id="show_requests" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-sm-offset-1">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">요청 확인</h4>
                    </div>
                </div>
	        <div class="modal-body row">
			<div class="col-sm-offset-1 col-sm-10">
				@for ($i=0, $cnt=0; $i<count($projects); $i++)
					@if (isset($projects[$i]['requests']))
						<h2 class="green">{{ $projects[$i]->name }}</h2>
						<div class="project_wrapper">
						@for ($j=0, $cnt++; $j<count($projects[$i]['requests']); $j++)
							<img src="{{ file_exists(public_path('storage/profile_imgs/'.$projects[$i]['requests'][$j]['id'])) ? asset('storage/profile_imgs/'.$projects[$i]['requests'][$j]['id']) : asset('storage/profile_imgs/default') }}">
							<div>
								<span class="green">{{ $projects[$i]['requests'][$j]['name'] }}</span><br>
								{{ $projects[$i]['requests'][$j]['nickname'] }}
							</div>
							<div>
								<button type="button" onclick="allowResponse('{{ $projects[$i]['name'] }}', {{ $projects[$i]['requests'][$j]['id'] }});">
									승인
								</button>
								<button type="button" onclick="denyResponse('{{ $projects[$i]['name'] }}', {{ $projects[$i]['requests'][$j]['id'] }});">
									거절
								</button>
							</div>
							<br>
						@endfor
						</div>
						
						@if ($cnt != 0 && $cnt != count($projects))
							<hr>
						@endif
					@endif
				@endfor

				<script>
					window.onload = function () {
						var wrapper = document.getElementsByClassName('project_wrapper');
						var len = wrapper.length;
	
						$(wrapper[len-1].nextSibling.nextSibling).remove();
					};
				</script>
			</div>
		</div>
        </div>
	</div>
	</div>

	<div class="modal fade" id="help" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header modal-header-style">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">도움말</h4>
				</div>
				<div class="modal-body modal-body-style">
					<h4>자라나라 나무나무는 프로젝트 관리 사이트입니다.</h4>
					<img src="{{ asset('images/help_img.png') }}" alt="사이트 설명" class="modal-image">
					
					<div class="modal-divider"></div>
					
					<h3>프로젝트 메뉴</h3>
					<h4>프로젝트 메뉴는 자신의 프로필 및 프로젝트 관리를 할 수 있는<br />4가지 기능을 가진 메뉴바 입니다.</h4>
					<div class="modal-divider"></div>
					
					<h3>자라나는 나무</h3>
					<h4>프로젝트가 진행상황에 따라 6가지 단계로 나무가 자라나<br />프로젝트의 진행상황을 한눈에 볼 수 있습니다.</h4>
			
					<div class="modal-divider"></div>
		
					<h3>현재 페이지 설명</h3>
	
					<div class="modal-divider"></div>

					<h3>프로필 정보</h3>
					<img src="{{ asset('images/help_profile_info.PNG') }}" alt="프로필 정보" class="modal-image-sm">
					<h4>회원가입할 때에 업로드한 정보를 확인할 수 있습니다.</h4>

					<div class="modal-divider"></div>
	
					<h3>프로필 수정</h3>
					<img src="{{ asset('images/help_profile_update.PNG') }}" alt="프로필 수정" class="modal-image-sm">
					<h4>닉네임, 이메일, 휴대전화 번호를 수정할 수 있습니다.<br> 비밀번호를 입력해야만 프로필 수정이 가능합니다.</h4>

					<div class="modal-divider"></div>

					<h3>프로젝트 생성</h3>
					<img src="{{ asset('images/help_create_project.PNG') }}" alt="프로젝트 생성" class="modal-image-sm">
					<h4>위의 영역을 클릭하면 새로운 프로젝트를 생성할 수 있습니다.</h4>

					<div class="modal-divider"></div>

					<h3>요청 확인</h3>
					<img src="{{ asset('images/help_confirm_req.PNG') }}" alt="요청 확인" class="modal-image-sm">
					<h4>다른 사용자들의 가입 요청을 확인할 수 있습니다. 프로젝트에 기여<br>할 수 있는 새로운 사용자를 추가하거나 요청을 거절할 수 있습니다.</h4>

					<div class="modal-divider"></div>

					<h3>프로젝트 검색</h3>
					<img src="{{ asset('images/help_search.PNG') }}" alt="프로젝트 검색" class="modal-image-sm">
					<h4>프로젝트 이름을 이용해서 다른 사람이 만든 프로젝트들을<br>확인할 수 있습니다. 리스트에서 하나를 선택하면 해당 프로젝트에대한<br>정보를 확인할 수 있습니다.</h4>

					<div class="modal-divider"></div>
				
					<h3>가입 요청</h3>
					<img src="{{ asset('images/help_req.PNG') }}" alt="가입 요청" class="modal-image-sm">	
					<h4>프로젝트 검색창에서 리스트를 선택하면 나오는 화면입니다.<br>맨 밑의 버튼을 클릭하면 가입을 요청할 수 있습니다.</h4>
					
					<div class="modal-divider"></div>
				</div>
			</div>
		</div>
	</div>
@stop
