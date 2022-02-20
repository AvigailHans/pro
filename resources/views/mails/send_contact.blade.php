<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
</head>
<body>
    <h6>New contact added:</h6>
    <p>
    @foreach ($details as $key => $value)
     @if($key!='captcha_token')
     {{$key}}:{{$value}},<br /> 
     @endif 
    @endforeach
    </p>
    <p>Thank you</p>
</body>
</html>