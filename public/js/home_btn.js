function onProfileEditBtnClicked () {
	$('#profile_info').modal('hide');
	setTimeout(function () {
		$('#profile_update').modal('show');
	}, 500);
}

function clearUpdateNotice () {
	var names = ['nickname', 'phone', 'email', 'password', 'profile_image'];
        for(var i=0; i<names.length; i++) {
            var $notice = $('#profile_update .notice[for="' + names[i] + '"]');
            //$notice.parent().removeClass('has-error');
            $notice.html('');
        }
}

function updateSuccess (response, status, msg) {
	alert('프로필이 업데이트 되었습니다.');
	location.href = '/home';
}
function updateFailed (response, status, errorCode) {
	var errors = JSON.parse(response.responseText);
    var names = Object.keys(errors);

    for(var i=0; i<names.length; i++) {
        var $notice = $('#profile_update .notice[for="' + names[i] + '"]');
		/*
		if(names[i] != 'profile_image') {
			$notice.parent().addClass('has-error');
		}
		*/
        $notice.html('* ' + errors[names[i]][0]);
    }
}

function updateProfile () {
	var formData = new FormData();

	formData.append('nickname', $('#profile_update input[name="nickname"]').val());
	formData.append('email', $('#profile_update input[name="email"]').val());
	formData.append('phone', $('#profile_update input[name="phone"]').val());
	formData.append('password', $('#profile_update input[name="password"]').val());
	formData.append('profile_image', $("#profile_update input[name='profile_image']").prop('files')[0]);
	formData.append("x1", $("#profile_update input[name='x1']").val());
    formData.append("y1", $("#profile_update input[name='y1']").val());
    formData.append("size", $("#profile_update input[name='size']").val());
	formData.append('_method', 'PATCH');

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: 'update_profile',
        contentType: false,
        processData: false,
        data: formData,
        success: updateSuccess,
        error: updateFailed
    });
}
function onProfileEditFinished () {
	clearUpdateNotice();
	updateProfile();
}

function clearProjectNotice () {
	var list = ['project_name', 'due_date', 'description'];

	for(var i=0; i<list.length; i++) {
		var $notice = $('#create_project label[for="' + list[i] + '"]');
		//$notice.parent().removeClass('has-error');
		$notice.html('');
	}
}

function createSuccess () {
	alert('프로젝트가 생성되었습니다');
	location.href="/home";
}

function createFailed (response, status, errorCode) {
        var errors = JSON.parse(response.responseText);
        var names = Object.keys(errors);
        
        for(var i=0; i<names.length; i++) {
            var $notice = $('#create_project .notice[for="' + names[i] + '"]');
            //$notice.parent().addClass('has-error');
            $notice.html('* ' + errors[names[i]][0]);
        }
}

function createProject () {
	var formData = new FormData();
	
	formData.append('project_name', $('#create_project input[name="project_name"]').val());
	formData.append('description', $('#create_project textarea[name="description"]').val());
	formData.append('due_date', $('#create_project input[name="due_date"]').val());
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

	$.ajax({
        type: 'POST',
        url: 'projects',
        contentType: false,
        processData: false,
        data: formData,
        success: createSuccess,
        error: createFailed
    });
}

function onCreateProjectBtnClicked () {
	clearProjectNotice();
	createProject();
}

$(document).ready(function () {
	//$('#profile_info .modal-body button').click(onProfileEditBtnClicked);
	/*
	$('#profile_update .modal-header button').click(function () {
		$('#profile_update').modal('hide');
		setTimeout(function () {
			$('#profile_info').modal('show');
		}, 500);
	});
	*/
	$('#profile_update .modal-body button').click(onProfileEditFinished);

	$('#create_project .modal-body button').click(onCreateProjectBtnClicked);
});
