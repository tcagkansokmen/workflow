<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Şifremi Unuttum</title>
</head>
<body>

<form action="{{route('define-password')}}" method="POST">
    @csrf
    <input type="hidden" value="{{$token}}">
   <span>Email: </span>  <input type="email" name="email" required>
   <span>Şifre: </span>  <input type="password" name="password" required>
   <span>Şifre tekrar: </span>  <input type="password" name="password_confirmation" required>
    <button class="btn btn-primary" type="submit">Kaydet</button>
</form>

</body>
</html>
