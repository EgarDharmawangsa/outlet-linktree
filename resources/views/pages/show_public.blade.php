<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Outlet Linktree | {{ $outlet_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-200 m-0 p-5">

    <div
        class="bg-red-600 w-32 h-32 mx-auto mt-5 rounded-full overflow-hidden flex items-center justify-center shadow-xl">
        <img src="{{ asset('images/apotekku-logo.png') }}" alt="Logo ApotekKu" class="w-full h-full object-cover">
    </div>

    <p class="text-center mt-3 text-gray-600 italic">#SolusiKesehatanKeluarga</p>
    <h1 class="text-2xl text-gray-700 font-bold mt-2 mb-6 text-center">{{ $outlet_name }}</h1>

    <hr class="border-gray-400">

    <div class="mt-8 flex flex-wrap justify-center gap-4">
        @forelse ($outlet_links as $outlet_link)
            <a href="{{ $outlet_link['link'] }}"
                data-uuid="{{ $outlet_link['uuid'] }}"
                class="store-click block w-full sm:w-[calc(33.333%-1rem)] p-4 bg-gradient-to-tl from-red-700 to-red-500 text-white rounded-tl-xl rounded-br-xl shadow-lg font-bold hover:to-red-700 text-lg transition duration-300"
                target="_blank">
                <div class="flex items-center">
                    <span>{{ $outlet_link['title'] }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 ml-auto">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </div>
            </a>
        @empty
            <p class="text-gray-500 text-center w-full mt-10">Tidak ada tautan tersedia.</p>
        @endforelse
    </div>
</body>

</html>
