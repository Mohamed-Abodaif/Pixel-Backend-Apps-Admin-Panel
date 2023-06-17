<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}}</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
        .container{
            margin: auto;
            width: 95%;
            height: 100vh;
        }
        h1 {
            font-family: 'Roboto', sans-serif;
            width:100% ;
            text-align:center
        }
    </style>
    @yield("external-css-links")
    @yield("head-css-style")
</head>
<body>
<div class="container">
    <h1>{{$title}}</h1>
    @yield("content")
    @yield("scripts")
</div>
</body>
</html>
