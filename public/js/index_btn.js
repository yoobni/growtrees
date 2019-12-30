function clearRegisterNotice() {
	var names = ['name', 'nickname', 'phone', 'email', 'user_id', 'password', 'profile_image'];
	for(var i=0; i<names.length; i++) {
		var $notice = $('#register .notice[for="' + names[i] + '"]');
		$notice.parent().removeClass('has-error');
		$notice.html('');
	}
}

function registerSuccess(data, status, response) {
	alert('회원가입이 완료되었습니다.');
	location.href = 'logout';
}

function registerFailed(request, status, errorCode) {
	var errors = JSON.parse(request.responseText);
	var names = Object.keys(errors);

	for(var i=0; i<names.length; i++) {
		var $notice = $('#register .notice[for="' + names[i] + '"]');
		$notice.parent().addClass('has-error');
		$notice.html('* ' + errors[names[i]]);
	}
}

function requestRegister() {
	var formData = new FormData();
	var profileImage = undefined;

	clearRegisterNotice();

	formData.append('name', $("#register input[name='name']").val());
	formData.append('nickname', $("#register input[name='nickname']").val());
 	formData.append('email', $("#register input[name='email']").val());
	formData.append('phone', $("#register input[name='phone']").val());
	formData.append('user_id', $("#register input[name='user_id']").val());
	formData.append('password', $("#register input[name='password']").val());
	formData.append('password_confirmation', $("#register input[name='password_confirmation']").val());
	
	profileImage = $("#register input[name='profile_image']").prop('files')[0]
	if(profileImage) {
		formData.append("profile_image", profileImage);
		formData.append("x1", $("#register input[name='x1']").val());
		formData.append("y1", $("#register input[name='y1']").val());
		formData.append("size", $("#register input[name='size']").val());
	}
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

	$.ajax({
		type: 'POST',
		url: 'register',
		contentType: false,
		processData: false,
		data: formData,
		success: registerSuccess,
		error: registerFailed
	});
}

function requestFindPw() {

}

$(document).ready(function () {
	$('#register_btn').click(requestRegister);
	$('#find_pw_btn').click(requestFindPw);
});
