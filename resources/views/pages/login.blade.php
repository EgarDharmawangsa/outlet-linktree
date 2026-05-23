<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outlet Linktree | Masuk</title>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-tl from-red-700 to-red-500 flex items-center justify-center min-h-screen m-0 p-0">
    @if (session('success'))
        <div  class="toast fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg" id="success-toast">
            {{ session('success') }}
        </div>
    @endif
        
    @if (session('error'))
        <div class="toast fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg mt-16" id="error-toast">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="w-full max-w-sm bg-white m-2 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-700">Masuk</h1>

        <form action="{{ route('autentikasi') }}" method="POST" class="space-y-4 mt-6 flex flex-col">
            @csrf
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Masukkan email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border border-red-500 @enderror"
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Kata Sandi</label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Masukkan kata sandi"
                    class="w-full px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('password') border border-red-500 @enderror"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col items-center mt-2 mb-5">
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-action="LOGIN"></div>
                @error('g-recaptcha-response')
                    <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">Masuk</button>
        </form>
    </div>
</body>

</html>
