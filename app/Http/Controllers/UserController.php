<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Services\OutletApiService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // if (!Gate::allows('super-admin')) {
        //     abort(404);
        // }

        return view('pages.users.index', [
            'title' => 'Pengguna'
        ]);
    }

    public function getUsers()
    {
        return response()->json([
            'data' => User::latest()->get()
        ]);
    }


    public function userSync(OutletApiService $outletApiService)
    {
        // if (!Gate::allows('super-admin')) {
        //     abort(404);
        // }

        try {
            $outlets = $outletApiService->getOutlets();

            if ($outlets->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada outlet untuk disinkronkan.'
                ]);
            }

            $defaultPassword = Hash::make('Apotekku');
            $now = now();

            $rows = [];

            foreach ($outlets as $outlet) {
                $rows[] = [
                    'uuid' => Str::uuid(),
                    'uuid_outlet' => $outlet['id'],
                    'name' => $outlet['nama'],
                    'email' => Str::slug($outlet['nama'], '') . '@outlet',
                    'password' => $defaultPassword,
                    'is_super_admin' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            User::upsert(
                $rows,
                ['uuid_outlet'],
                ['name', 'email', 'is_super_admin', 'updated_at']
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengguna berhasil disinkronkan.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengguna gagal disinkronkan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (!Gate::allows('super-admin')) {
        //     abort(404);
        // }

        try {
            $user = $request->validate([
                'name' => 'required|string|min:1|max:100',
                'email' => 'required|string|unique:users,email|min:5|max:255',
                'password' => 'required|string|min:8|max:255'
            ]);

            $user['uuid'] = Str::uuid();
            $user['password'] = Hash::make($user['password']);
            $user['is_super_admin'] = true;
            $user['uuid_outlet'] = null;

            User::create($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Super Admin berhasil ditambahkan.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan Super Admin.',
                'error' => $e->getMessage()
            ], 500);
        }

        // return redirect()->route('pengguna.index')->with('success', 'Super Admin berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // if (!Gate::allows('super-admin')) {
        //     abort(404);
        // }

        try {
            $validated_user = $request->validate([
                'name' => 'required|string|min:1|max:100',
                'email' => "required|unique:users,email,{$user->id}|min:5|max:255",
                'password' => 'nullable|string|min:8|max:255'
            ]);

            $validated_user['password'] = $validated_user['password']
                ? Hash::make($validated_user['password'])
                : $user->password;

            $user->update($validated_user);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengguna berhasil diperbarui.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui pengguna.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // if (!Gate::allows('super-admin')) {
        //     abort(404);
        // }

        try {
            if ($user->is_super_admin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Super Admin tidak dapat dihapus.'
                ]);
            }

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengguna berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus pengguna.',
                'error' => $e->getMessage()
            ], 500);
        }
        // return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
};
