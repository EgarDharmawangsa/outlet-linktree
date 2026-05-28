@extends('layouts.main')

@section('container')
    <div>
        <h1 class="text-2xl text-gray-700 font-bold">BERANDA</h1>
        
        @can('admin')
            <h1 class="text-2xl text-gray-700 font-bold mt-2 mb-3">{{ $outlet['nama'] }}</h1> 
            <a href="http://outlet-linktree.test/{{ $outlet['slug'] }}" class="inline-block p-3 text-white bg-blue-500 rounded-lg">outlet-linktree/apotekku-9-renon</a>
        @endcan
        
        @can('super-admin')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white mt-6">
                
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
            <div class="bg-gradient-to-tl from-blue-700 to-blue-500 p-3 rounded-lg text-white mt-6">
                <p>Jumlah Tautan</p>
                <p class="text-5xl font-bold mt-2">{{ $counted_links }}</p>
            </div>
        @endcan
    </div>
@endsection