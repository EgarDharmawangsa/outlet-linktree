<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Outlet Linktree | {{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="m-0 p-0">
    @if (session('success'))
        <div class="toast fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg" id="success-toast">
            {{ session('success') }}
        </div>
    @endif
        
    @if (session('error'))
        <div class="toast fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg mt-16" id="error-toast">
            {{ session('error') }}
        </div>
    @endif

    @include('partials.navbar')
    <div>
        @include('partials.sidebar')
        <div class="ps-0 sm:ps-55">
            <div class="bg-gray-200 min-h-screen px-3 md:px-6 pt-21 pb-6">
                <div class="bg-white rounded-xl shadow-lg p-5">
                    @yield('container')
                </div>
            </div>
        </div>
    </div>
</body>
</html>