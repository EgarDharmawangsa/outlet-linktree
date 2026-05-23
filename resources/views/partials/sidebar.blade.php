<div class="fixed z-50 sm:z-30 w-55 h-screen bg-gray-800 text-white flex flex-col p-5 -translate-x-full sm:translate-x-0 transition-transform duration-300" id="sidebar">
    <button id="close-sidebar" class="mb-10">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ms-auto">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </button>
    <ul class="flex-1">
        <li>
            <a href="{{ route('beranda') }}" class="block px-3 py-2 rounded-lg hover:bg-red-900 transition duration-300 {{ request()->routeIs('beranda') ? 'bg-red-600' : '' }}">Beranda</a>
        </li>

        @can('super-admin')
            <li>
                <a href="{{ route('pengguna.index') }}" class="block px-3 py-2 rounded-lg hover:bg-red-900 transition duration-300 {{ request()->routeIs('pengguna') ? 'bg-red-600' : '' }}">Pengguna</a>
            </li>
            
            <li>
                <a href="{{ route('tautan-outlet.index') }}" class="block px-3 py-2 rounded-lg hover:bg-red-900 transition duration-300 {{ request()->routeIs('tautan-outlet.*') ? 'bg-red-600' : '' }}">Tautan Outlet</a>
            </li>
        @endcan

        @can('admin')
            <li>
                <a href="{{ route('tautan-outlet.show', auth()->user()->uuid_outlet) }}" class="block px-3 py-2 rounded-lg hover:bg-red-900 transition duration-300 {{ request()->routeIs('tautan-outlet.*') ? 'bg-red-600' : '' }}">Tautan Outlet</a>
            </li>
        @endcan
    </ul>

    <div>
        <hr class="mb-2">
        <form action="{{ route('keluar') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left block px-3 py-2 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out">Keluar</button>
        </form>
    </div>
</div>
