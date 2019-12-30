function search() {
	var search = document.getElementById('search');
	search.innerHTML = "<input type='text' id='dataSearch' placeholder='검색하고 싶은 팀명을 입력하세요.'/><li id='inputform' class='menu' onclick='desearch();''><a href='javascript:void(0)'><img src='images/search.png' alt='' /></a></li>";
	$('#dataSearch').focus(function() {
		focus();
	});
	$('#dataSearch').focusout(function() {
		outfocus();
	});
}
function desearch() {
	var search = document.getElementById('search');
	search.innerHTML = "<li id='inputform' class='menu' onclick='search();''><a href='javascript:void(0)'><img src='images/search.png' alt='' /></a></li>";
}
function focus() {
	$('#searchlist').css("display", "block");
}
function outfocus() {
	$('#searchlist').css("display", "none");
}
