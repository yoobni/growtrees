<div style="background-image: url('{{ asset('images/background.png') }}'); width: 100%; height: 500px; text-align: center; position: relative;">
	<img src="{{ asset('images/logo.png') }}" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
</div>

<form action="reset_pw" method="POST">
	{{ csrf_field() }}

	id <input type="text" name="user_id"> <br>
	email <input type="email" name="email"> <br>
	<input type="submit">
</form>
