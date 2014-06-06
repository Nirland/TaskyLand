<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Welcome, {{$user['firstname']}}!</h2>

		<div>
                    Your username: {{$user['username']}}<br>
                    Your password: {{$password}}
		</div>
	</body>
</html>
