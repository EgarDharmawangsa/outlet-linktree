<nav class="fixed z-40 w-full bg-red-600 text-white ps-3 pe-5 py-2 flex items-center justify-between shadow-md">
    <div class="flex items-center">
        <button class="me-2 py-1 px-3 rounded-lg hover:bg-red-700 block sm:hidden transition duration-300" id="sidebar-toggle">
            ☰
        </button>
        <a class="text-lg font-bold">
            Linktree Apotekku
        </a>
    </div>

    <a href="{{ route('profil.index') }}" class="block hover:bg-red-700 px-2 py-1 rounded-lg">
        <p class="text-[15px]">{{ auth()->user()->name }}</p>
        <span class="block text-gray-300 text-[11px]">({{ auth()->user()->is_super_admin ? 'Super Admin' : 'Admin' }})</span>
    </a>
</nav>