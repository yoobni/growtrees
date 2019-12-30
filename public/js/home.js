function allowSuccess () {
	alert('멤버가 추가되었습니다.');
	location.href = "/home";
}
function allowFailed () {
	alert('멤버 추가에 실패했습니다.');
	location.href = "/home";
}

function denySuccess () {
	alert('요청을 거절했습니다.');
	location.href = "/home";
}
function denyFailed () {
	alert('오류가 발생했습니다.');
	location.href = "/home";
}

function allowResponse(projectName, userId) {
	var formData = new FormData();
    formData.append('projectName', projectName);
    formData.append('userId', userId);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: 'allow_request',
        contentType: false,
        processData: false,
        data: formData,
        success: allowSuccess,
        error: allowFailed
    });	
}
function denyResponse(projectName, userId) {
	var formData = new FormData();
    formData.append('projectName', projectName);
	formData.append('userId', userId);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: 'deny_request',
        contentType: false,
        processData: false,
        data: formData,
        success: denySuccess,
        error: denyFailed
    });
}

function joinSuccess (response, status, t) {
	alert('프로젝트 가입 요청을 보냈습니다.');
	location.href="/home";
}
function joinFailed (response, status, errorCode) {
	alert('요청에 실패했습니다.');
	location.href="/home";
}

/* JunYoung code */
function sendJoinRequest(projectId) {
	var formData = new FormData();
	formData.append('id', projectId);

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: 'join_request',
        contentType: false,
        processData: false,
        data: formData,
        success: joinSuccess,
        error: joinFailed
    });
}
/*
function attemptImage(uid) {
	$.get('storage/profile_imgs/' + uid, function(returnedData) {                 

	}).fail(function(jqXHR, textStatus, errorThrown){
		if(jqXHR.status == 404) {
			console.log(404);
                                 //flag = false;         
		}
	});
}
*/

/*
var accessImage = true;
function hasImage(uid) {
	return $.when(function () {
		return $.get('storage/profile_imgs/' + uid, function(returnedData) {
		
		}).fail(function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status == 404) {
				accessImage = false;
				console.log(accessImage);
			}
		});
	
	}()).done(function () {
		accessImage = true;	
	});;
}
*/

function getProfileImgUrl(uid) {
	var status = $.ajax({
		type: "GET",
		url: "/storage/profile_imgs/" + uid,
		async: false,
	}).status;

	if(status == 200) {
		return "/storage/profile_imgs/" + uid;
	}
	else {
		return "/storage/profile_imgs/default";
	}
}

function onDataClicked(projectId, profileImagePath, userName, nickname, projectName) {
	$.get('project_info/' + projectId, function(data, status) {
		var $div = '';
		var members = data['members'].split('n');
	
		$div += '<div class="col-sm-offset-1 col-sm-10">';
			$div += '<h2 class="green">' + projectName + '</h2>';
			$div += '<p>' + data['description'] + '</p>';
			$div += '<hr>';

			$div += '<span class="green">' + "관리자" + '</span><br>';
			$div += '<img src="' + profileImagePath + '" alt="profile_img">';
			$div += '<div>';
				$div += '<span class="green">' + userName + '</span><br>';
				$div += '<span>' + nickname + '</span>';
			$div += '</div>';
			$div += '<hr>';
	
			$div += '<span class="green">멤버(' + (members.length-1)  + ')</span><br>';
			for(var i=0; i < members.length-1; i++) {
				var url = getProfileImgUrl(members[i]);
				$div += '<img class="members col-xs-1" src="' + url + '">';
			}
			$div += '<br>';
		$div += '</div>';

		$div += '<button type="button" class="btn btn-default col-xs-12" onclick="';
		if(data['joined'] === true) {
                        $div += "alert('이미 프로젝트의 멤버입니다.');";
                }
       	        else if(!data['joined']) {
                        $div += "sendJoinRequest('" + projectId + "');";
       	        }
	        else {
        	        $div += "alert('승인 대기중입니다.');";
                }
		$div += '">가입 요청</button>';
	
		$('#project_info .modal-body').html($div);
		$('#project_info').modal('show');
	});
}

function getProjectList() {
	$.get("project_list/" + $('#dataSearch').val(), function(data, status){
    	var items = data;
		var $div = '';
		for(var i=0; i<items.length; i++) {
			items[i].author;
			items[i].nickname;
			items[i].name;

			var params = '';
			params += items[i].id + ', ';
			params += '"' + items[i].profileImagePath + '"' + ', ';
			params += '"' + items[i].author + '"' + ', ';
			params += '"' + items[i].nickname + '"' + ', ';
			params += '"' + items[i].name + '"';
			
			$div += '<a href="#" onclick=\'onDataClicked(' + params +');\'><div class="datalist"><img class="dataimg" src="' 
					+  items[i].profileImagePath + '" alt="">'
					+ '<strong>' + items[i].nickname + ' </strong>'
					+ ' &nbsp;&nbsp;&nbsp;&nbsp;' + items[i].name + '<br /></div></a>';
		}

		$('#searchlist').html($div);
 	});	
}

/* youben code */
function search() {
	var search = document.getElementById('search');
	search.innerHTML = "<input type='text' id='dataSearch' placeholder='검색하고 싶은 팀명을 입력하세요.'/><li id='inputform' class='menu' onclick='desearch();''><a href='javascript:void(0)'><img src='images/search.png' alt='' /></a></li>";
	$('#dataSearch').focus(function() {
		focus();
	});
	$('#dataSearch').focusout(function() {
		//outfocus();
	});

	$('#dataSearch').on('change keyup paste', getProjectList);
}
function desearch() {
	var search = document.getElementById('search');
	search.innerHTML = "<li id='inputform' class='menu' onclick='search();''><a href='javascript:void(0)'><img src='images/search.png' alt='' /></a></li>";
	outfocus();
}
function focus() {
	$('#searchlist').css("display", "block");
}
function outfocus() {
	$('#searchlist').css("display", "none");
}


/* Junyoung code */
var ias = null;
var inputPlaceHolders = {
    'name' : '이름',
    'nickname' : '닉네임',
    'email' : '이메일',
    'phone' : '연락처',
    'user_id' : '아이디',
    'password' : '비밀번호',
};

function onFocusInputTag () {
	if(this.callCnt === undefined) { 
		inputPlaceHolders[this.name] = this.value;
		this.callCnt = 0; 
	}
    if(this.type == 'file' || this.value.indexOf(inputPlaceHolders[this.name]) == -1) { 
		return; 
	}

	this.value = '';

	if(this.name.indexOf('password') != -1) {
       	this.type = 'password';
	} else if(this.name.indexOf('date') != -1) {
		this.type = 'date';
	}
}

function onFocusOutInputTag () {
    if(this.type == 'file' || this.value) { return; }

    if(this.name.indexOf('password') != -1 || this.name.indexOf('date') != -1) {
        this.type = 'text';
    }
    this.value = inputPlaceHolders[this.name];
}

function onMouseEnterPreview () {
	$('#tooltip').animate({
		top: '110px'
	}, 300);
}
function onMouseLeavePreview () {
	$('#tooltip').animate({
		top: '150px'
	}, 300);
}

function onImageChanged (input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
                $('#selected_image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }

    $('#profile_update').modal('hide');
    $('#image_edit').modal('show');
}

function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;

    var scaleX = 150 / selection.width;
    var scaleY = 150 / selection.height;

    $('#preview').css({
        width: Math.round(scaleX * 250),
        height: Math.round(scaleY * 250),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1),
    });

    $('#profile_update input[name="x1"]').val(selection.x1);
    $('#profile_update input[name="y1"]').val(selection.y1);
    $('#profile_update input[name="size"]').val(selection.width);
}
function onEditFinished () {
    $('#image_edit').modal('hide');
    ias.cancelSelection();

    setTimeout(function () {
       $('#profile_update').modal('show');
    }, 500);
}


$(document).ready(function () {
	$('input, textarea').focus(onFocusInputTag);
    $('input, textarea').focusout(onFocusOutInputTag);
	$('#preview_wrapper').mouseenter(onMouseEnterPreview);
	$('#preview_wrapper').mouseleave(onMouseLeavePreview);
	$('#tooltip').click(function () { $('input[name="profile_image"]').click(); });
	$('input[name="profile_image"]').change(function () {
		onImageChanged(this);
	});

	ias = $('#selected_image').imgAreaSelect({
        aspectRatio: '1:1',
        handles: true,
        fadeSpeed: 200,
        onSelectChange: preview,
        instance: true,
    });
	
	$('#image_edit .modal-body button').click(onEditFinished);
});	
