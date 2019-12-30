function uncheckedHover(element) {
    element.setAttribute('src', '/images/check_c.png');
}
function uncheckedUnhover(element) {
    element.setAttribute('src', '/images/check_u.png');
}
function xHover(element) {
    element.setAttribute('src', '/images/x_l.png');
}
function xUnhover(element) {
    element.setAttribute('src', '/images/x_f.png');
}
function checkedHover(element) {
    element.setAttribute('src', '/images/v_l.png');
}
function checkedUnhover(element) {
    element.setAttribute('src', '/images/v_f.png');
}
/* youben code */
function show(x) {
	$('#' + x).css("display", "block");
}
function closeflex() {
	$('#calender').css("display", "none");
}
function closetel() {
	$('#office').css("display", "none");
}
function showflex(x) {
	$('#' + x).css("display", "flex");
}
function rollon() {
	$('#roll').css("display", "flex");
}
function rollout() {
	$('#roll').css("display", "none");
}
function todoliston() {
	$('#todolist').css("display", "flex");
}
function todolistout() {
	$('#todolist').css("display", "none");
}
function chattingdown() {
	$('#chat').css("display", "none");
	$('#waterspread').css("bottom", "0");
	document.getElementById('waterspread').innerHTML = '<img src="/images/waterspread.png" data-toggle="tooltip" title="Click Me!" width="100" height="60"  onclick="chattingup();">';
	$('[data-toggle="tooltip"]').tooltip();
}
function chattingup() {
	$('#chat').css("display", "block");
	$('#waterspread').css("bottom", "380px");
	document.getElementById('waterspread').innerHTML = '<img src="/images/waterspread.png" width="100" height="60" data-toggle="tooltip" title="Click Me!" onclick="chattingdown();">';
	$('[data-toggle="tooltip"]').tooltip();
}

/* JunYoung */

var msgCnt = 0;

var entityMap = {
	"&": "&amp;",
	"<": "&lt;",
	">": "&gt;",
	'"': '&quot;',
	"'": '&#39;',
	"/": '&#x2F;'
};

function escapeHtml(string) {
	return String(string).replace(/[&<>"'\/]/g, function (s) {
		return entityMap[s];
	});
}

function autoLink(id) {
        var regURL = new RegExp('(^|[^"])(http|https|ftp|telnet|news|irc)://([-/.a-zA-Z0-9_~#%$?&=:200-377()]+)', 'gi');
        var regURL2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;

        $(id).html(
                $(id).html().replace(regURL, ' <a class="autoLink" href="://" target="_blank">$2://$3</a>')
                        .replace(regURL2, ' <a class="autoLink" href="http://" target="_blank">$2</a>')
        );

	
	return id;
}

function getNew (response) {
	for(var i=msgCnt; i<response.length; i++) {
		var msg = '<div>' +
				'<div class="name green">' + escapeHtml(response[i].name) + '</div>' + 
				'<div class="message">' + escapeHtml(response[i].message) + '</div>' +
			  '</div>';

		$('#message_wrapper').append(msg);
		autoLink($('#message_wrapper > div:last-child .message'));
	}

	if(msgCnt < response.length) {
		$('#message_wrapper').animate({
			scrollTop: $('#message_wrapper').get(0).scrollHeight
		});
	
		msgCnt = response.length;
	}
}

function noNew (response) {
}

function getNewList () {
	var formData = new FormData();
	
	formData.append('key_p', $('meta[name="key_p"]').attr('content'));

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/chat_list',
        contentType: false,
        processData: false,
        data: formData,
        success: getNew,
		error: noNew,
	});
}

function hideSideMenu() {
	$('.sidemenu').hide();
}

function setCurMonth(month) {
	$('.month-active').html(month + '월');
}

function prevClicked (month) {
	var year = $('meta[name="year"]').attr('content');

	if(month == 12) {
		year--;
		$('meta[name="year"]').attr('content', year);
	}

	displayMonth(year, month);
}
function nextClicked (month) {
	var year = $('meta[name="year"]').attr('content');

        if(month == 1) {
                year++;
                $('meta[name="year"]').attr('content', year);
        }

        displayMonth(year, month);
}
function setPrevMonth(month) {
	if(month == 0) { 
		month = 12; 
	}

	$('.month:first-child').html('<span onclick="prevClicked(' + month + ');">' + month + '월<span>');
}
function setNextMonth(month) {
	if(month == 13) { 
		month = 1; 
	}

	$('.month:last-child').html('<span onclick="nextClicked(' + month + ');">' + month + '월<span>');
}

function displayMonth(year, month) {
	$('#year').html($('meta[name="year"]').attr('content'));	

	setCurMonth(month);
	setPrevMonth(month-1);
	setNextMonth(month+1);

	var curMonth = new Date(year, month-1);
	var nextMonth = new Date(year, month);
	
	var offset = curMonth.getDay();

	$('#days').html('');
	var ul = '<ul><li>일</li><li>월</li><li>화</li><li>수</li><li>목</li><li>금</li><li>토</li></ul>';
	$('#days').html(ul);

	nextMonth.setDate(nextMonth.getDate() - 1);
	ul = "<ul class='offset-" + offset + "'>";
		for(var i=1; i <= 7-offset; i++) {
			ul += '<li>' + i + '</li>';
		}
	
	var cnt = nextMonth.getDate();
	for(var i = 8-offset; i<=cnt; i++) {
		var flag = false;

		switch(offset)
		{
		case 0 :
			if(i%7 == 1) { flag = true; }
			break;
		case 1 :
			if(i%7 == 0) { flag = true; }
			break;
		case 2 :
			if(i%7 == 6) { flag = true; }
			break;
		case 3 :
			if(i%7 == 5) { flag = true; }
			break;
		case 4 :
			if(i%7 == 4) { flag = true; }
			break;
		case 5 :
			if(i%7 == 3) { flag = true; }
			break;
		case 6 :
			if(i%7 == 2) { flag = true; }
			break;
		}
	
		if(flag) {
			ul += "</ul>";
			$('#days').append(ul);
	
			ul = "<ul>";
		}

		ul += "<li>" + i + "</li>";
	}

	ul += "</ul>";
	$('#days').append(ul);
}

function displayDate(year, month, date) {

}

$(document).ready(function () {
	var today = new Date();
	var curYear = today.getFullYear();
	var curMonth = today.getMonth() + 1;
	var curDate = today.getDate();
	
	displayMonth(curYear, curMonth);
	displayDate(curYear, curMonth, curDate);

	$('head').append('<meta name="year" content="' + curYear + '">');

	setInterval(getNewList, 300);
	$('[data-toggle="tooltip"]').tooltip();
	
	$(document).on('mouseenter', '#todolist .green-box img, .normal-15 img:first-child', function () {
		checkedHover(this);
	});
	$(document).on('mouseleave', '#todolist .green-box img, .normal-15 img:first-child', function () {
		checkedUnhover(this);	
	});
	$(document).on('mouseenter', '#todolist .white-box img', function () {
		uncheckedHover(this);
	});
	$(document).on('mouseleave', '#todolist .white-box img', function () {
		uncheckedUnhover(this);
	});
	$(document).on('mouseenter', '.normal-15 img:last-child', function () {
                xHover(this);
        });
        $(document).on('mouseleave', '.normal-15 img:last-child', function () {
                xUnhover(this);
        });
});
