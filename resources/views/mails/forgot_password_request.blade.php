<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
</head>
<body>
<table>
    <tr>
        <td>{{ $subject }}</td>
    </tr>
    <tr>
        <td>Hi Mr, {{ $body['name'] }}</td>
    </tr>
    <tr>
        <td>
            <p>We're receive a request to change your password</p>
            Code: <strong>{{ $body['code'] }}</strong>
        </td>
    </tr>
</table>
</body>
</html>

