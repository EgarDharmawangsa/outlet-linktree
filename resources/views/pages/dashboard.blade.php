@extends('layouts.main')

@section('container')
    <div>
        <h1 class="text-2xl text-gray-700 font-bold mb-6">BERANDA</h1>
        
        @can('super-admin')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white">
                
                <div class="bg-gradient-to-tl from-green-700 to-green-500 p-3 rounded-lg">
                    <p>Jumlah Pengguna</p>
                    <p class="text-5xl font-bold mt-2">{{ $counted_users }}</p>
                </div>

                <div class="bg-gradient-to-tl from-blue-700 to-blue-500 p-3 rounded-lg">
                    <p>Jumlah Outlet</p>
                    <p class="text-5xl font-bold mt-2">{{ $counted_outlets }}</p>
                </div>
            </div>
        @endcan

        @can('admin')    
            <div class="bg-gradient-to-tl from-blue-700 to-blue-500 p-3 rounded-lg text-white">
                <p>Jumlah Tautan</p>
                <p class="text-5xl font-bold mt-2">{{ $counted_links }}</p>
            </div>
        @endcan
    </div>
@endsection