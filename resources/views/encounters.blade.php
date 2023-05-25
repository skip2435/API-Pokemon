<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Other head elements -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <title>Pokemon Search</title>
    </head>
    <body>
        <div id="app">
            <encounter-component></encounter-component>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>