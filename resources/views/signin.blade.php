<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>
</head>
<body>
    @if($errors->any())
    <div class="message error-message">
        <h4>{{$errors->first()}}</h4>
    </div>
    @endif
    <form action="/login" method="post">
        @csrf
        <input type="text" name="username" id="username">
        <input type="password" name="password" id="password">

        <input type="submit" value="Login" />
    </form>
</body>
</html>