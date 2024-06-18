<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="trial_login">
        @csrf
        <input type="text" name="uid" />
        <input type="password" name="password" />
        <button type="submit">Login</button>
       
</form>
</body>
</html>