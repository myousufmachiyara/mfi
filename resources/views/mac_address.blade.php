<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Information</title>
</head>
<body>

    <h1>Server Information</h1>

    {{-- @if(is_array($macAddress)) --}}
        <h2>Server Information:</h2>
        
    {{-- @else --}}
        <h2>MAC Address:</h2>
        <p>{{ $macAddress }}</p>
    {{-- @endif --}}

</body>
</html>
