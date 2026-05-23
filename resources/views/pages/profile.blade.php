@extends('layouts.main')

@section('container')
    <div>
        <h1 class="text-2xl text-gray-700 font-bold mb-6">PROFIL</h1>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
            <p>{{ $user->name }}</p>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
            <p>{{ $user->email }}</p>
        </div>

        <form action="{{ route('profil.update', $user->uuid) }}" method="POST" class="space-y-4 flex flex-col">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Kata Sandi</label>
                <div class="flex flex-wrap gap-2">
                    <input type="password" name="password" placeholder="Masukkan kata sandi"
                        class="flex-1 px-4 py-2 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('password') border border-red-500 @enderror">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">Perbarui</button>
                </div>
                @error('password')
                    <div>
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    </div>
                @enderror
                <p class="mt-2 text-[13px] text-gray-500">Kosongkan input kata sandi jika tidak ingin mengganti kata sandi.
                </p>
            </div>
        </form>
    </div>
@endsection
