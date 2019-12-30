@extends('layouts.master')

@push('style')
	<link rel="stylesheet" href="/css/index.css">
	<link rel="stylesheet" href="/css/imagecrop/imgareaselect-animated.css">
@endpush

@push('script')
	<script src="/js/imagecrop/jquery.imgareaselect.pack.js"></script>
	<script src="/js/index.js"></script>
	<script src="/js/index_btn.js"></script>
@endpush

@section('content')
	<div id="main" class="container-fluid">
		<img src="{{ asset('images/logo.png') }}" alt="logo_image" id="main_img">
		
		<form id="login_form" action="{{ route('session.store') }}" method="POST">
			{{ csrf_field() }}

			<div class="inputform row form-group">
				@if (count($errors))
					<label for="user_id" class="col-md-offset-4 col-md-4 col-xs-offset-2 col-xs-8">
						* {{ $errors->first() }}
					</label>
				@endif
				<input type="text" class="col-md-offset-4 col-md-4 col-xs-offset-2 col-xs-8 maininput" name="user_id" value="{{ old('user_id') ? : '아이디' }}" >
				<input type="password" class="col-md-offset-4 col-md-4 col-xs-offset-2 col-xs-8 maininput" name="password" value="비밀번호">
			</div>
			<div class="row form-group">
				<div class="col-md-offset-4 col-md-4 col-xs-offset-2 col-xs-8"">
					<button type="submit" class="mainbutton btn-right">로그인</button>
					<button type="button" class="mainbutton btn-left" data-toggle="modal" data-target="#register">회원가입</button>
				</div>
			</div>
			<div class="row form-group">
				<a class="col-md-offset-4 col-md-4 col-xs-offset-2 col-xs-8" data-toggle="modal" data-target="#forgot_pw">
					비밀번호 찾기
				</a>
			</div>
		</form>
	</div>

	<div id="register" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-sm-offset-1">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">회원가입</h4>
					</div>
				</div>
				<div class="modal-body">
					<div class="col-xs-12 text-center">
						<img class="glyph" src="{{ asset('images/glyph/glyphicons-lock.png') }}" alt="정보 이용안내">
						<h4>정보 이용안내</h4>
					</div>
					<p class="col-sm-offset-1 col-sm-10">
					  아이디는 공개되지 않으며 닉네임만 공개됩니다.
					  이메일은 비밀번호 분실 시 필요합니다.
					  이메일과 휴대전화 번호는 팀원들 사이에서만 공유되며 다른 목적으로는 이용되지 않습니다.
					</p>
					<form class="form-horizontal">
						<div class="form-group">
							<label for="name" class="col-sm-offset-1 col-sm-10 col-xs-12 control-label notice"></label>
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<input type="text" class="form-control" name="name" value="이름">
							</div>
						</div>
						<div class="form-group">
                            <label for="nickname" class="col-sm-offset-1 col-sm-10 col-xs-12 control-label notice"></label>
                            <div class="col-sm-offset-1 col-sm-10 col-xs-12">
	                            <input type="text" class="form-control" name="nickname" value="닉네임">
                            </div>
                        </div>
						<div class="form-group">
							<label for="email" class="col-sm-offset-1 col-sm-10 col-xs-12 control-label notice"></label>
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<input type="email" class="form-control" name="email" value="이메일">
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-sm-offset-1 col-sm-10 col-xs-12 control-label notice"></label>
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<input type="text" class="form-control" name="phone" value="휴대전화[연락처]">
							</div>
						</div>
						<div class="form-group">
							<label for="user_id" class="col-sm-offset-1 col-sm-10 col-xs-12 control-label notice"></label>
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<input type="text" class="form-control" name="user_id" value="아이디">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-offset-1 col-sm-10 col-xs-12 control-label notice"></label>
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">                   
								<input type="password" class="form-control" name="password" value="비밀번호">
							</div>
						</div>
						<div class="form-group">
							<label for="password_confirmation" class="col-sm-offset-1 col-sm-10 col-xs-12 control-label notice"></label>
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<input type="password" class="form-control" name="password_confirmation" value="비밀번호 확인">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<label for="profile_image" class="control-label notice"></label>
								<label for="profile_image" class="control-label">프로필 사진(선택)</label>
							</div>
							<div id="profile_wrapper" class="col-sm-10 col-xs-12">
								<img id="preview" src="{{ asset('images/profile_none.png') }}" alt="profile_image" width="150" height="150">
								<input type="file" class="form-contorl" name="profile_image" accept="image/*">
							</div>
						</div>
	
						<input type="hidden" name="x1" value="0">
						<input type="hidden" name="y1" value="0">
						<input type="hidden" name="size" value="250">
						<div class="form-group">	
							<button id="register_btn" type="button" class="btn btn-default col-xs-12">
								완료
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div id="forgot_pw" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="col-sm-offset-1">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">비밀번호 찾기</h4>
					</div>
				</div>
				<div class="modal-body">
					<form class="form-horizontal">
						<div class="form-group">
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<input type="text" name="user_id" class="form-control" value="아이디">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-1 col-sm-10 col-xs-12">
								<input type="email" class="form-control" name="email" value="이메일">
							</div>
						</div>	
						<div class="form-group">
							<button id="find_pw_btn" type="button" class="btn btn-default col-xs-12">
								전송
							</button>
						</div>
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
                <div class="modal-body">
					<div class="row">
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
	</div>
@stop
