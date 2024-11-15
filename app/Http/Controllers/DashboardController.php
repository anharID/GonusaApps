<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\App;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Aplikasi';
        // Jika administrator, tampilkan semua apps
        if (auth()->user()->id === 1) {
            $apps = App::where('data_status', 1)
                ->orderBy('app_group')
                ->orderBy('app_name')
                ->get()
                ->groupBy('app_group');
        } else {
            // Jika user biasa, tampilkan apps sesuai hak akses
            $apps = auth()->user()->apps()
                ->where('apps.data_status', 1)
                ->wherePivot('data_status', 1)
                ->orderBy('app_group')
                ->orderBy('app_name')
                ->get()
                ->groupBy('app_group');
        }

        return view('dashboard.index', compact('title', 'apps'));
    }
}
