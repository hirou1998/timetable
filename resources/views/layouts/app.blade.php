<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ session()->get('api_token') ?? '' }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @yield('link')
    <div id="app">
        <app />
    </div>
</body>
</html>

<script>
window.AuthUser = '{!! auth()->user() !!}';
window.__auth = function(){
    try{
        return JSON.parse(AuthUser);
    }catch(err){
        return null;
    }
}
</script>