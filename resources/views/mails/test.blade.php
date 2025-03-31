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
            <td>Hi Mr, {{ $data['name'] }}</td>
        </tr>
        <tr>
            <td>{{ $data['body'] }}</td>
        </tr>
    </table>
</body>
</html>
