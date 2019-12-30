@extends('layouts.master')

@push('style')
	<link rel="stylesheet" href="/css/project.css">
@endpush

@push('script')
	<script src="/js/project.js"></script>
	<script src="/js/project_btn.js"></script>
@endpush

@section('meta')
	<meta name="_token" content="{{ csrf_token() }}">
	<meta name="key_u" content="{{ encrypt($user->id) }}">
	<meta name="key_p" content="{{ encrypt($project->id) }}">
@stop

@section('header')
	<header class="container-fluid">
		<div id="logo">
			<a href="{{ route('home') }}" id="logo">
				<img src="{{ asset('images/title.png') }}" alt="자라나라나무나무" width="150">
			</a>
		</div>
	</header>
@stop

@section('content')
	<div id="content_wrapper" class="row">
		<div>
			<nav class="navbar">
				<div>
					<h4 class="green">{{ $project->name }}</h4>
				</div>
				<div id="profile">
					<img src="{{ file_exists(public_path('storage/profile_imgs/'.$user->id)) ? asset('storage/profile_imgs/'.$user->id) : asset('storage/profile_imgs/default') }}" alt="profile_image" width="60" height="60">
					<div>
						<span class="green">{{ $user->name }}</span> <br>
						{{ $user->nickname }}
					</div>
				</div>
				<div class="clearfix"></div>

				<div id="menubar">
					<ul class="nav navbar-nav">
						<li><a href="javascript:void(0);" onclick="hideSideMenu(); showflex('calender');">캘린더</a></li>
						<li><a href="javascript:void(0)" onclick="hideSideMenu(); rollon();">역할분담표</a></li>
						<li><a href="javascript:void(0)" onclick="hideSideMenu(); todoliston();">To do list</a></li>
						<li><a href="javascript:void(0);" onclick="hideSideMenu(); show('office');">연락처</a></li>
					</ul>
				</div>

				<div id="user_menubar">
					<ul class="nav navbar-nav">
						<li><a href="#" class="green" data-toggle="modal" data-target="#help">도움말</a></li>
						<li><a href="#" class="green" data-toggle="modal" data-target="#setting" onclick="authorize({{ $user->id == $project->author ? true:0 }})">설정</a></li>
						<li><a href="{{ route('session.destroy') }}" class="green">로그아웃</a></li>
					</ul>
				</div>
			</nav>
		</div>

		<!-- calender -->
		<div id="calender" class="side-window sidemenu">
			<div id="calender-side" class="green-box">
				<div id="calender-day" class="bold-50">03</div>
				<div class="thin-15 ">목요일</div>
				<div id="calender-deadline" class="normal-30">마감 24시간 전</div>
				<div class="normal-15">
					<img src="{{ asset('images/v_l.png') }}" alt="">
					<img src="{{ asset('images/x_l.png') }}" alt="">
				</div>
				<div id="chat-mini">
					<div class="chatting-mini">
						<img src="{{ asset('storage/profile_imgs/default') }}" alt="">
						<strong>user01</strong>text text text text
					</div>
					<div class="chatting-mini">
						<img src="{{ asset('storage/profile_imgs/default') }}" alt="">
						<strong>user01</strong>text text text text
					</div>
					<div class="chatting-mini">
						<img src="{{ asset('storage/profile_imgs/default') }}" alt="">
						<strong>user01</strong>text text text text
					</div>
					<div class="chatting-mini">
						<img src="{{ asset('storage/profile_imgs/default') }}" alt="">
						<strong>user01</strong>text text text text
					</div>
					<div class="chatting-mini">
						<img src="{{ asset('storage/profile_imgs/default') }}" alt="">
						<strong>user01</strong>text text text text
					</div>
					<div class="chatting-mini">
						<img src="{{ asset('storage/profile_imgs/default') }}" alt="">
						<strong>user01</strong>text text text text
					</div>
				</div>
				<div class="white-input">
					<input type="text" placeholder="댓글 입력" />
					<button>작성</button>
				</div>
			</div>
			<div id="calender-month" class="white-box">
				<a href="javascript:void(0);" class="x-button" onclick="closeflex();">x</a>
				<div id="year" class="bold-30">
					2016
				</div>
				<div class="monthlist">
					<div class="month"></div>
					<div class="month month-active"></div>
					<div class="month"></div>
				</div>
				<div id="days">
					<ul>
						<li>일</li>
						<li>월</li>
						<li>화</li>
						<li>수</li>
						<li>목</li>
						<li>금</li>
						<li>토</li>
					</ul>
				<!--
					<ul class="offset-2">
						<li class="day">1</li>
						<li class="block">2</li>
						<li>3</li>
						<li>4</li>
						<li>5</li>
					</ul>
					<ul>              
						<li>6</li>
                                                <li>7</li>      
                                                <li>8</li>
                                                <li>9</li>
                                                <li>10</li>
                                                <li>11</li>
                                                <li>12</li>
                                        </ul>
					<ul>                    
						<li>13</li>
                                                <li>14</li>
                                                <li>15</li>
                                                <li>16</li>
                                                <li>17</li>
                                                <li>18</li>
                                                <li>19</li>
                                        </ul>
					<ul>    
						<li>20</li>
                                                <li>21</li>                
                                                <li>22</li>
                                                <li>23</li>
                                                <li>24</li>
                                                <li>25</li>
                                                <li>26</li>
                                        </ul>
					<ul> 
						<li>27</li>
						<li>28</li>           
                                                <li>29</li>
                                                <li>30</li>
                                        </ul>	
				-->
				</div>
			</div>
		</div>

		<!--roll divide -->
		<div id="roll" class="sidemenu">
			<div id="divisiontable">
				<div class="divisiontitle">역할 분담표</div>
				<div class="divisionuser">
					<img src="{{ file_exists(public_path('storage/profile_imgs/'.$user->id)) ? asset('storage/profile_imgs/'.$user->id) : asset('storage/profile_imgs/default') }}" alt="프로필사진" class="userimg">
					<p><strong>{{ $user->name }}</strong><br />{{ $user->nickname }}</p>
				</div>
				<div id="rolldivider"></div>
				<ul>
					@for ($i=0; $i<count($user['roll']); $i++)
						<li class="rolldata" data-idx="{{ $i }}">{{ $user['roll'][$i] }}</li>
					@endfor
				</ul>
				<div class="divisioninputform">
					<input type="text" name="roll" class="divisioninput" placeholder="추가할 사항을 입력하세요.">
					<button class="divisionbutton" onclick="addRoll();">추가</button>
				</div>
			</div>
			<div id="userroll">
				<a href="javascript:void(0);" id="z" onclick="rollout();">x</a>
				
				@foreach ($members as $member)
					<div class="usersection">
						<div class="rollname">
							<img src="{{ file_exists(public_path('storage/profile_imgs/'.$member->id)) ? asset('storage/profile_imgs/'.$member->id) : asset('storage/profile_imgs/default') }}" alt="프로필 사진">
							<p><strong>{{ $member->name }}</strong><br />{{ $member->nickname }}</p>
						</div>
						<div class="rollprofile">
							<ul class="padding" @if ($member->id == $user->id) id="user_roll" @endif>
								@foreach ($member['roll'] as $roll)
									<li class="rollprofilelist">{{ $roll }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endforeach
			</div>
		</div>

		<!-- to do list -->
		<div id="todolist" class="side-window sidemenu">
			<div class="todolist-section green-box">
				<div class="todolist-title x-margin">To do list</div>
				<div class="todolist-subtitle">완료 목록</div>
				<div class="todolist-checklist">
					<div class="todolist-data">
						<img src="{{ asset('images/v_f.png') }}" alt="">
						<p><strong>메인페이지 구현 및 기능</strong><br />2016 11 03</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/v_f.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/v_f.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/v_f.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
				</div>
			</div>
			<div class="todolist-section white-box">
				<a href="javascript:void(0);" class="x-button" onclick="todolistout();">x</a>
				<div class="todolist-title">To do list</div>
				<div class="todolist-subtitle">완료 목록</div>
				<div class="todolist-checklist">
					<div class="todolist-data">
						<img src="{{ asset('images/check_u.png') }}" alt="" onclick="">
						<p><strong>메인페이지 구현 및 기능</strong><br />2016 11 03</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/check_u.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/check_u.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/check_u.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/check_u.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/check_u.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
					<div class="todolist-data">
						<img src="{{ asset('images/check_u.png') }}" alt="">
						<p><strong>test</strong><br />text text text</p>
					</div>
				</div>
				<div id="todolist-input" class="white-input">
					<input type="text" placeholder="추가할 사항을 입력해 주세요" />
					<button>등록</button>
				</div>
			</div>
		</div>		

		<!-- office -->
		<div id="office" class="sidemenu">
			<div id="office-header">
				연락처
				<a href="javascript:void(0);" id="x" onclick="closetel();">x</a>
			</div>
			<div id="office-body">
				<div class="office-list">내 연락처</div>
				<div class="offile-profile">
					<div class="office-profile-info">
						<img src="{{ file_exists(public_path('storage/profile_imgs/'.$user->id)) ? asset('storage/profile_imgs/'.$user->id) : asset('storage/profile_imgs/default') }}" alt="프로필사진" class="profileimg">
					</div>
					<div class="office-profile-text">
						<strong class="col-md-4 green">이메일</strong>
						<p class="col-md-8">{{ $user->email }}</p>
					</div>
					<div class="office-profile-text">
						<strong class="col-md-4 green">휴대전화</strong>
						<p class="col-md-8">{{ $user->phone }}</p>
					</div>
				</div>
				<div id="divider"></div>

				@for ($i=0, $cnt=0; $i<count($members); $i++)
				{{--
					@if ($members[$i]->id == $user->id)
						@continue;
					@endif
				--}}
					<?php $cnt++; ?>
	
					@if ($cnt == 1)
						<div class="office-list">그룹원 연락처</div>
					@endif
					
					<div class="office-profile">
						<div class="office-profile-info">
							<img src="{{ file_exists(public_path('storage/profile_imgs/'.$members[$i]->id)) ? asset('storage/profile_imgs/'.$members[$i]->id) : asset('storage/profile_imgs/default') }}" alt="프로필사진" class="profileimg">
							<p><strong>{{ $members[$i]->name }}</strong>
							{{ $members[$i]->nickname }}</p>
						</div>
						<div class="office-profile-text">
							<strong class="col-md-4 green">이메일</strong>
							<p class="col-md-8">{{ $members[$i]->email }}</p>
						</div>
						<div class="office-profile-text">
							<strong class="col-md-4 green">휴대전화</strong>
							<p class="col-md-8">{{ $members[$i]->phone }}</p>
						</div>	
					</div>
				@endfor
			</div>
<!--
			<div class="officelist">그룹원 연락처</div>
			<div class="profile">
				<img src="assets/img/sun.png" alt="" class="profileimg">
				<p><strong>User</strong>text</p>
			</div>
			<div class="profiletext">
				<strong class="col-md-2">title</strong><p class="col-md-10">alnksnlsdlknasnklslknas</p>
				<strong class="col-md-2">title</strong><p class="col-md-10">alnksnlsdlknasnklslknas</p>
			</div>
			<div class="officelist"></div>
			<div class="profile">
				<img src="assets/img/sun.png" alt="" class="profileimg">
				<p><strong>User</strong>text</p>
			</div>
			<div class="profiletext">
				<strong class="col-md-2">title</strong><p class="col-md-10">alnksnlsdlknasnklslknas</p>
				<strong class="col-md-2">title</strong><p class="col-md-10">alnksnlsdlknasnklslknas</p>
			</div>
			<div class="officelist"></div>
			<div class="profile">
				<img src="assets/img/sun.png" alt="" class="profileimg">
				<p><strong>User</strong>text</p>
			</div>
			<div class="profiletext">
				<strong class="col-md-2">title</strong><p class="col-md-10">alnksnlsdlknasnklslknas</p>
				<strong class="col-md-2">title</strong><p class="col-md-10">alnksnlsdlknasnklslknas</p>
			</div>
-->
		</div>

		<div id="tree">
			<img src="{{ asset('images/tree.png') }}" alt="나무" width="500" height="500">
		</div>

		<a href="javascript:void(0);" data-toggle="tooltip" title="Click Me!" id="sun">
			<div id="rays">
				<div class="set-one">
					<div class="triangle top"></div>
					<div class="triangle bottom"></div>
				</div>
				<div class="set-two">
					<div class="triangle top"></div>
					<div class="triangle bottom"></div>
				</div>
				<div class="set-three">
					<div class="triangle top"></div>
					<div class="triangle bottom"></div>
				</div>
				<div class="set-four">
					<div class="triangle top"></div>
					<div class="triangle bottom"></div>
				</div>
			</div>
			<div class="center"></div>
		</a>
		<a><div id="sunday" data-toggle="tooltip" title="Click Me!" class="center"><br/>
			D-<?php 
				$end_date = substr($project->due_date, 0, 10);
				$d_day = floor(( strtotime(substr($end_date,0,10)) - strtotime(date('Y-m-d')) )/86400);
			
				if($d_day < 0) {
					$d_day = 'Day'; //D-Day 가 -로 넘어 갔을때 00으로 맞춤
				}
				echo $d_day;
			?>
		</div></a>

		<a href="javascript:void(0);">
			<div id="waterspread">
				<img src="{{ asset('images/waterspread.png') }}" data-toggle="tooltip" title="Click Me!" width="100" height="60" onclick="chattingdown();">
			</div>
		</a>

		<div id="chat">
			<div id="chattitle">
				채팅
			</div>
			<div id="message_wrapper">
			
			</div>
			<form>
				<input type="text" name="message" class="form-control" autocomplete="off">
				<button type="button">
					 <span class="glyphicon glyphicon-send"></span>
				</button> 
			</form>
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
					<img src="{{ asset('images/help_img.png') }}" alt="메인 사진" class="modal-image">
					<div class="modal-divider"></div>
					<h3>프로젝트 메뉴</h3>
					<h4>프로젝트 메뉴는 자신의 프로필 및 프로젝트 관리를 할 수 있는<br />4가지 기능을 가진 메뉴바 입니다.</h4>
					<div class="modal-divider"></div>
					<h3>자라나는 나무</h3>
					<h4>프로젝트가 진행상황에 따라 6가지 단계로 나무가 자라나<br />프로젝트의 진행상황을 한눈에 볼 수 있습니다.</h4>
					<div class="modal-divider"></div>
					<h3>메인기능</h3>
					<div class="modal-divider"></div>
					<h3>캘린더</h3>
					<img src="{{ asset('images/help_cal.png') }}" alt="캘린더" class="modal-image-sm">
					<h4>캘린더 기능으로 현재 남은 기간과, 일정 마감시간 등<br />프로젝트 마감시간을 체크할 수 있습니다.</h4>
					<div class="modal-divider"></div>
					<h3>역할분담표</h3>
					<img src="{{ asset('images/help_roll.png') }}" alt="역할분담표" class="modal-image-sm">
					<h4>역할분담표 기능으로 자신의 분야와 일정을 수정하고<br />팀원들의 역할과 해야할 일을 볼 수 있습니다.</h4>
					<div class="modal-divider"></div>
					<h3>To Do List</h3>
					<img src="{{ asset('images/help_todolist.png') }}" alt="투두리스트" class="modal-image-sm">
					<h4>To do list 기능으로 지금까지 완료된 프로젝트 스케줄을<br />확인하고 완료해야될 목록을 추가합니다.</h4>
					<div class="modal-divider"></div>
					<h3>연락처</h3>
					<img src="{{ asset('images/help_tel.png') }}" alt="연락처" class="modal-image-sm">
					<h4>연락처로 팀원들의 이메일과 전화번호, 깃허브(파일관리페이지)<br />정보를 공유하여 보다 수월한 프로젝트 진행을 도웁니다.</h4>
					<div class="modal-divider"></div>
				</div>
			</div>
		</div>
	</div>
	@if ($user->id == $project->author)
	<div id="setting" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header modal-setting-style">
					<div class="col-sm-offset-1">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">설정</h4>
					</div>
				</div>
				<div class="modal-body row">
					<div class="form-group col-sm-offset-1 col-sm-10">
						<label for="project_name">프로젝트 이름 <span class="error"></span></label>
						<input type="text" class="form-control" id="usr" name="project_name">

						<label for="due_date">마감 날짜 <span class="error"></span></label>
						<input type="date" class="form-control" id="pwd" name="due_date">

						<label for="description">프로젝트 소개 <span class="error"></span></label>
						<textarea class="form-control" rows="4" id="comment" name="description"></textarea>

						<label for="nickname">관리자 변경 <span class="error"></span></label>
						<div class="setting-admin">
							<input type="text" class="form-control" id="nickname" name="nickname" placeholder="닉네임 입력">
							<button onclick="alterAdmin();">변경</button>
						</div>

						<label for="name">프로젝트 삭제 <span class="error"></span></label>
						<div class="setting-admin">
							<input type="text" class="form-control" id="name" name="name" placeholder="프로젝트명 입력">
							<button onclick="deleteProject();">삭제</button>
						</div>

						<label for="password">비밀번호 <span class="error"></span></label>
						<input type="password" class="form-control" name="password">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="updateProjectInfo();" class="btn btn-default">저장</button>
				</div>
			</div>
		</div>
	</div>
	@endif
@stop
