@extends('layouts.main')

@section('container')
    <div>
        <h1 class="text-2xl text-gray-700 font-bold mb-6">PENGGUNA</h1>

        <div class="flex flex-wrap gap-2">
            <button class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition duration-300"
                onclick="document.getElementById('create-user-modal').classList.remove('hidden')">Tambah</button>

            <button class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition duration-300" id="sync-user-btn">Sinkronisasi</button>
        </div>

        <div class="mt-5">
            <table class="w-full bg-white" id="users-table">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">No.</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Posisi</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                </tbody>
            </table>
        </div>

        @include('modals.users.create-modal')
        @include('modals.users.edit-modal')
    </div>
@endsection
