@extends('layouts.main')

@section('container')
    <div>
        <h1 class="text-2xl text-gray-700 font-bold">KELOLA TAUTAN OUTLET</h1>

        @can('super-admin')
            <h1 class="text-md text-gray-500">Outlet: {{ $outlet['nama'] }}</h1>
        @endcan

        {{-- Chart disini --}}
        <button class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 mt-5 rounded-lg transition duration-300" id="sync-chart">Sinkronisasi Diagram</button>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-5 mb-8">
            <div class="bg-gray-100 p-4 rounded-xl flex flex-col">
                <h1 class="text-lg text-gray-700 font-bold">Jenis Perangkat Pengunjung</h1>
                <p class="mb-6">Distribusi perangkat pengunjung.</p>
                <div class="flex-grow">
                    <canvas id="device-distribute-chart"></canvas>
                </div>
            </div>
            <div class="bg-gray-100 p-4 rounded-xl flex flex-col">
                <h1 class="text-lg text-gray-700 font-bold">Top Tautan Terpopuler</h1>
                <p class="mb-5">5 tautan dengan klik terbanyak.</p>
                <div class="flex-grow">
                    <canvas id="top-click-chart"></canvas>
                </div>
            </div>
            <div class="bg-gray-100 p-4 rounded-xl lg:col-span-2 xl:col-span-1 flex flex-col">
                <h1 class="text-lg text-gray-700 font-bold">Trend Klik Tautan</h1>
                <p class="mb-8">Jumlah klik harian selama 7 hari terakhir.</p>
                <div class="flex-grow">
                    <canvas id="daily-click-chart"></canvas>
                </div>
            </div>
        </div>

        {{-- Kelola disini --}}
        <button class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 mt-6 rounded-lg transition duration-300"
            onclick="document.getElementById('create-outlet-link-modal').classList.remove('hidden')">Tambah</button>

        <div class="mt-5 rounded-lg">
            <table class="w-full bg-white" id="outlet-links-table" data-outlet-uuid="{{ $outlet['id'] }}">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">No.</th>
                        <th class="px-4 py-2 text-left">Judul</th>
                        <th class="px-4 py-2 text-left">Tautan</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                </tbody>
            </table>
        </div>

        @can('super-admin')
            <a href="{{ route('tautan-outlet.index') }}"
                class="inline-block bg-gray-600 hover:bg-gray-700 text-white mt-5 py-2 px-4 rounded-lg transition duration-300">Kembali</a>
        @endcan

        @include('modals.outlet_links.create_modal')
        @include('modals.outlet_links.edit_modal')
    </div>
@endsection
