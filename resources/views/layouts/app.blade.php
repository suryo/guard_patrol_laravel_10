<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Guard Patrol</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">
  <div class="container">
    <h3 class="mb-3">Guard Patrol</h3>
    @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
    @yield('content')
  </div>
</body>
</html>
