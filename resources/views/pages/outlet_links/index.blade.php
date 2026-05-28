@extends('layouts.main')

@section('container')
    <div>
        <h1 class="text-2xl text-gray-700 font-bold mb-6">TAUTAN OUTLET</h1>

        {{-- Trigger API request --}}
        {{-- <button type="submit" class="inline-block bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition duration-300" id="sync-outlet-btn">
            <div class="flex items-center">
                Sinkronisasi
            </div>
        </button> --}}

        <div class="mt-5 rounded-lg">
            <table class="w-full bg-white" id="outlets-table">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">No.</th>
                        <th class="px-4 py-2 text-left">Tautan</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Alamat</th>
                        <th class="px-4 py-2 text-left">No. HP</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                </tbody>
            </table>
        </div>
    </div>
@endsection
