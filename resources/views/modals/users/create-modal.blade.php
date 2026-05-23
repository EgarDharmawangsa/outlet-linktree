<div class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[100]" id="create-user-modal">
    <div class="bg-white p-6 rounded-lg shadow-lg w-[25rem]">
        <div class="flex">
            <button
                onClick="document.getElementById('create-user-modal').classList.add('hidden'); document.getElementById('create-user-form').reset();"
                class="ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 ms-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <h1 class="text-2xl text-gray-700 font-bold mb-6">Tambah Pengguna</h1>

        <form method="POST" class="space-y-4 flex flex-col" id="create-user-form">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                <input 
                    id="create-name" type="text" name="name" placeholder="Masukkan nama"
                    class="w-full px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    required
                >
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input 
                    id="create-email" type="text" name="email" placeholder="Masukkan email"
                    class="w-full px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    required
                >
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Kata Sandi</label>
                <input 
                    id="create-password" type="password" name="password" placeholder="Masukkan kata sandi"
                    class="w-full px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    required
                >
            </div>

            <div class="flex justify-end mt-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">Tambah</button>
            </div>
        </form>
    </div>
</div>
