<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Truth QR UI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial; margin: 2rem; }
        nav a { margin-right: 1rem; }
        pre, code, textarea { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
        .card { border: 1px solid #e5e7eb; border-radius: .5rem; padding: 1rem; background: #fff; }
    </style>
</head>
<body>
<nav>
    <a href="{{ route('truthqr.ui.encode') }}">Encode</a>
    <a href="{{ route('truthqr.ui.decode') }}">Decode</a>
    <a href="{{ route('truthqr.ui.playground') }}">Playground</a>
</nav>
<hr>
@yield('content')
</body>
</html>
