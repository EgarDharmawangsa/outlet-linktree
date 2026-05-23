<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.profile', [
            'title' => 'Profil Saya',
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->uuid !== $user->uuid) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => 'nullable|string|min:8|max:255',
        ]);

        $validated['password'] = !empty($validated['password']) ? Hash::make($validated['password']) : $user->password;

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
