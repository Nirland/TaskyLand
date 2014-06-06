<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Hello, {{$user['firstname']}}!</h2>

		<div>
                    Your username: {{$user['username']}}<br>
                    Your NEW password: {{$password}}
		</div>
	</body>
</html>
