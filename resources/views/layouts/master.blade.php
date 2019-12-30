<!DOCTYPE html>
<html>
<head>
	<title>자라나라나무나무</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta http-equiv="Cache-control" content="no-cache">

	<!-- kakao link preview -->
	<meta property="og:url" content="http://growtrees.nefus.kr/">
	<meta property="og:title" content="자라나라나무나무">  
	<meta property="og:type" content="website">
	<meta property="og:image" content="./images/pot.png">
	<meta property="og:description" content="프로젝트와 함께 자라나는 나무">
	<meta name="description" content="프로젝트와 함께 자라나는 나무">

	@hasSection('meta')
		@yield('meta')
	@endif

	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="icon" href="/favicon.ico">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	@stack('style')
</head>
<body>
	@section('header')@show
	@section('content')@show
	@section('footer')@show
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	@stack('script')
</body>
</html>
