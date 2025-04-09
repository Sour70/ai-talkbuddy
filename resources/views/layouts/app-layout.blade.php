<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AI Talk Buddy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
@include('inlcudes.app-header')
@yield('content')
@include('inlcudes.app-footer')
</body>
<script src="{{ asset('js/main.js') }}"></script>
<script>
  const CSRF_TOKEN = '{{ csrf_token() }}';
</script>
@stack('scripts')
</html>