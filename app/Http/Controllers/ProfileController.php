<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $name = auth()->user()->user_fullname;
        $title = "Profil " . $name;
        return view('dashboard.profile.index', compact('title'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'user_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->user_password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->update([
            'user_password' => Hash::make($request->user_password)
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }
}
