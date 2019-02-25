<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Philips Hue - By Dennis Smink</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 50vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
        }

        .title {
            font-size: 84px;
            text-align: center;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        code {
            color: #c7254e;
            background-color: #f9f2f4;
            border-radius: 4px;
            padding: 2px 4px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            Philips Hue
        </div>

        <div class="links">
            <p>
                Enter this username in your <code>.env</code> with this variable: <code>PHILIPS_HUE_USERNAME</code>
            </p>
            <strong>Username:</strong> {{ request('username')  }}

            <p>
                If you entered the username in your <code>.env</code> file, click the bottom link to view your lights.<br/>
                <a href="{{ action('\Philips\Hue\Http\Controllers\HueController@receive') }}">View Lights</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
