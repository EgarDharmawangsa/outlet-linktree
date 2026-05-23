@extends('layouts.main')

@section('container')
    <div>
        <h1 class="text-2xl text-gray-700 font-bold mb-6">EDIT TAUTAN OUTLET</h1>

        <form 
            action="{{ route('tautan-outlet.update', $outlet_link->id) }}" 
            method="POST" 
            class="space-y-4 flex flex-col"
        >
            @csrf
            @method('PUT')
            
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Judul</label>
                <input 
                    type="text" 
                    name="title" 
                    placeholder="Masukkan judul tautan"
                    class="w-full px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('title') border border-red-500 @enderror"
                    value="{{ old('title', $outlet_link->title) }}"
                    required
                >
                <div>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Tautan</label>
                <input 
                    type="text" 
                    name="link" 
                    placeholder="Masukkan tautan (contoh: https://www.example.com)"
                    class="w-full px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('link') border border-red-500 @enderror"
                    value="{{ old('link', $outlet_link->link) }}"
                    required
                >
                <div>
                    @error('link')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap justify-between gap-2 mt-3">
                <a href="{{ route('tautan-outlet.show', $outlet_link->uuid_outlet) }}" class="inline-block bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition duration-300">Kembali</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">Perbarui</button>
            </div>
        </form>
    </div>
@endsection
