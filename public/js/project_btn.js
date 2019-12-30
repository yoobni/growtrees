function sendSuccess (response, status, msg) {
	$('#chat input[name="message"]').val('');
}

function sendFailed (response, status, errorCode) {
}

function onSendBtnClicked() {
	var formData = new FormData();

	formData.append('key_p', $('meta[name="key_p"]').attr('content'));
	formData.append('key_u', $('meta[name="key_u"]').attr('content'));
	formData.append('message', $('#chat input[name="message"]').val());

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/chattings',
        contentType: false,
        processData: false,
        data: formData,
        success: sendSuccess,
        error: sendFailed
    });
}

function updateSuccess(response, status, msg) {
	var roll = JSON.parse(msg.responseText);

	if(roll.text == '') {
		$('.rolldata[data-idx="' + roll.idx + '"]').remove();
		$('#user_roll > li:nth-child(' + (parseInt(roll.idx)+1) + ')').remove();
	} else {
		$('#user_roll > li:nth-child(' + (parseInt(roll.idx)+1) + ')').html(roll.text);
	}
}
function updateFailed(response, status, errorCode) {
	alert(response.responseText);
}

function updateRoll(idx, roll) {
	var formData = new FormData();

	formData.append('key_p', $('meta[name="key_p"]').attr('content'));
        formData.append('key_u', $('meta[name="key_u"]').attr('content'));
        formData.append('roll', roll);
	formData.append('idx', idx);

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/update_roll',
        contentType: false,
        processData: false,
        data: formData,
        success: updateSuccess,
        error: updateFailed
    });
}

function addRollSuccess(response, status, msg) {
	var idx = parseInt($('.rolldata:last-child').data('idx')) + 1;

	var li = '<li class="rolldata" data-idx="' + idx + '">';
		li += response;
	li += '</li>';
	$('#divisiontable > ul').append(li);

	li = '<li class="rollprofilelist">';
		li += response;
	li += '</li>';
	$('#user_roll').append(li);

	$('input[name="roll"]').val('');
}
function addRollFailed(response, status, errorCode) {
	alert(JSON.parse(response.responseText));
}

function addRoll() {
	var roll = $('input[name="roll"]').val().trim();
	if(roll == '') { return; }

	var formData = new FormData();

        formData.append('key_p', $('meta[name="key_p"]').attr('content'));
        formData.append('key_u', $('meta[name="key_u"]').attr('content'));
        formData.append('roll', roll);
        
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }   
    }); 
    
    $.ajax({
        type: 'POST',
        url: '/rolls',
        contentType: false,
        processData: false,
        data: formData,
        success: addRollSuccess,
        error: addRollFailed
    }); 

}

function authorize(author) {
	if(author == false) {
		alert('프로젝트 중요 정보를 수정할 수 있는 권한이 없습니다.');
	}
}

function updateProjectSuccess(response, status, msg) {
	alert('프로젝트 정보를 수정했습니다.');
	location.reload();
}
function updateProjectFailed(response, status, errorCode) {
	var list = ['project_name', 'due_date', 'description', 'password'];
	var errors = JSON.parse(response.responseText);

	for(var i=0; i<list.length; i++) {
		var $label = $('#setting label[for="' + list[i] + '"] .error');
		var msg = errors[list[i]];

		$label.html('');

		if(msg) {
			$label.html(' * ' + msg);
		}
	}
}

function updateProjectInfo() {
	var name = $('#setting input[name="project_name"]').val();
	var due_date = $('#setting input[name="due_date"]').val();
	var description = $('#setting textarea[name="description"]').val();
	var password = $('#setting input[name="password"]').val();

	if(name == '' && due_date == '' && description == '') { return; }

	var formData = new FormData();	

	formData.append('key_p', $('meta[name="key_p"]').attr('content'));
	formData.append('project_name', name);
	formData.append('due_date', due_date);
	formData.append('description', description);
	formData.append('password', password);

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/update_project',
        contentType: false,
        processData: false,
        data: formData,
        success: updateProjectSuccess,
        error: updateProjectFailed
    });
}

function alterAdminSuccess(response, status, msg) {
	alert('프로젝트 관리자가 변경되었습니다.');
	location.reload();
}
function alterAdminFailed(response, status, errorCode) {
	var list = ['nickname', 'password'];
        var errors = JSON.parse(response.responseText);

        for(var i=0; i<list.length; i++) {
                var $label = $('#setting label[for="' + list[i] + '"] .error');
                var msg = errors[list[i]];

                $label.html('');

                if(msg) {
                        $label.html(' * ' + msg);
                }
        }
}

function alterAdmin() {
	var nickname = $('#setting input[name="nickname"]').val();
	var password = $('#setting input[name="password"]').val();

	if(nickname == '') { return; }

	if(!confirm('정말 관리자 권한을 양도하시겠습니까?')) {
		return;
	}

	var formData = new FormData();

        formData.append('key_p', $('meta[name="key_p"]').attr('content'));
        formData.append('nickname', nickname);
        formData.append('password', password);

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/alter_admin',
        contentType: false,
        processData: false,
        data: formData,
        success: alterAdminSuccess,
        error: alterAdminFailed
    });
}

function deleteProjectSuccess(response, status, msg) {
	alert('프로젝트가 삭제되었습니다.');
	location.href = "/home";
}
function deleteProjectFailed(response, status, errorCode) {
	var list = ['name', 'password'];
        var errors = JSON.parse(response.responseText);

        for(var i=0; i<list.length; i++) {
                var $label = $('#setting label[for="' + list[i] + '"] .error');
                var msg = errors[list[i]];

                $label.html('');

                if(msg) {
                        $label.html(' * ' + msg);
                }
        }
}

function deleteProject() {
	var name = $('#setting input[name="name"]').val();
	var password = $('#setting input[name="password"]').val();

	if(name == '') { return; }

	if(!confirm('정말 삭제하시겠습니까?\n(삭제할 경우 영구적으로 되돌릴 수 없습니다.)')) 
	{ 
		return; 
	}

	var formData = new FormData();

        formData.append('key_p', $('meta[name="key_p"]').attr('content'));
        formData.append('name', name);
        formData.append('password', password);

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/delete_project',
        contentType: false,
        processData: false,
        data: formData,
        success: deleteProjectSuccess,
        error: deleteProjectFailed
    });
}

$(document).ready(function () {
	$('#chat button').click(onSendBtnClicked);
	$('#chat input[name="message"]').keypress(function(e) {
		if (e.which == 13) {/* 13 == enter key@ascii */
			onSendBtnClicked();
			e.preventDefault();
		}
	});
	$('input[name="roll"]').keypress(function(e) {
		if (e.which == 13) {
			addRoll();
			e.preventDefault();
		}
	});

	$(document).on('click', '.rolldata', function () {
		$(this).attr('contentEditable', true);
		$(this).focus();

	}).on('blur', '.rolldata', function () {
		var roll = $(this);
                updateRoll(roll.data('idx'), roll.html());
		
		$(this).attr('contentEditable', false);
        });

	$(document).on('keypress', '.rolldata', function(e) {
                if (e.which == 13) {
			var roll = $(this);
			updateRoll(roll.data('idx'), roll.html());
                     
                        e.preventDefault();
                }
        });

});
