var ias = null;

function heightResize () {
	var mainPage = document.getElementById('main');
	mainPage.style.height = window.innerHeight + 'px';
	mainPage.style.width = window.innerWidth + 15 +'px';
	mainPage.style.padding = "0 0 0 10px";
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

	$('#register').modal('hide');
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
        left: -Math.round(scaleX * selection.x1),
        top: -Math.round(scaleY * selection.y1),
    });

    $('#register input[name="x1"]').val(selection.x1);
    $('#register input[name="y1"]').val(selection.y1);
    $('#register input[name="size"]').val(selection.width);
}

function onEditFinished () {
	$('#image_edit').modal('hide');
	ias.cancelSelection();

	setTimeout(function () {
		$('#register').modal('show');
	}, 500);
}

var inputPlaceHolders = {
    'name' : '이름',
    'nickname' : '닉네임',
    'email' : '이메일',
    'phone' : '휴대전화[연락처]',
    'user_id' : '아이디',
    'password' : '비밀번호',
    'password_confirmation' : '비밀번호 확인'
};

function onFocusInputTag () {
	if(this.type == 'file' || this.value != inputPlaceHolders[this.name]) { return; }

	this.value = '';
	if(this.name.indexOf('password') != -1) {
		this.type = 'password';
	}
}

function onFocusOutInputTag () {
	if(this.type == 'file' || this.value) { return; }
	
	if(this.name.indexOf('password') != -1) {
		this.type = 'text';
	}
	this.value = inputPlaceHolders[this.name];
}

$(document).ready(function () {
	heightResize();
	window.addEventListener('resize', heightResize);

	/* input tags focus */
	$('input').focus(onFocusInputTag);
	$('input').focusout(onFocusOutInputTag);	

	$('input[name^=password]').attr('type', 'text');
	
	/* profile image */
	var $inputFile = $('input[name="profile_image"]');
	$inputFile.change(function () {
		onImageChanged(this)
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
