<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pasarelas de Pago</title>
    <link rel="stylesheet" href="{{asset("css/app.css")}}">
    {{-- <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.css" /> --}}
    @stack("styles")
    @livewireStyles()
</head>
<body>
    @yield("content")
    @livewireScripts
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{asset("js/app.js")}}"></script>
    @stack("scripts")
</body>
</html>
