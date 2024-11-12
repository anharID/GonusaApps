<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\User;
use App\Models\MapUserApp;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserAccessController extends Controller
{
    public function index()
    {
        $title = "Manajemen Hak Akses";
        $users = User::where('data_status', 1)
            ->where('id', '!=', 1)
            ->get();

        // Urutkan apps berdasarkan app_name dan app_group
        $apps = App::where('data_status', 1)
            ->orderBy('app_group')
            ->orderBy('app_name')
            ->get();

        return view('admin.user-access.index', compact('users', 'apps', 'title'));
    }

    public function getData()
    {
        $users = User::where('id', '!=', 1)
            ->where('data_status', 1)->get();

        return DataTables::of($users)
            ->addColumn('user_name', function ($user) {
                return $user->user_fullname;
            })
            ->addColumn('app_access', function ($user) {
                $apps = $user->apps->pluck('app_name')->toArray();
                return empty($apps) ? '-' : implode(', ', $apps);
            })
            ->addColumn('action', function ($user) {
                return '<button onclick="editUserAccess(' . $user->id . ')" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Ubah Akses
                                </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getUserApps($userId)
    {
        $appIds = MapUserApp::where('user_id', $userId)
            ->where('data_status', 1)
            ->pluck('app_id');

        return response()->json($appIds);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'app_ids' => 'nullable|array'
        ]);

        MapUserApp::where('user_id', $request->user_id)
            ->update(['data_status' => 0]);

        // Tambah akses baru jika ada app_ids
        if ($request->has('app_ids') && !empty($request->app_ids)) {
            foreach ($request->app_ids as $appId) {
                // Cek apakah mapping sudah ada sebelumnya
                $existingMapping = MapUserApp::where('user_id', $request->user_id)
                    ->where('app_id', $appId)
                    ->first();

                if ($existingMapping) {
                    // Jika sudah ada, update data_status menjadi 1
                    $existingMapping->update(['data_status' => 1]);
                } else {
                    // Jika belum ada, buat baru
                    MapUserApp::create([
                        'user_id' => $request->user_id,
                        'app_id' => $appId,
                        'data_status' => 1
                    ]);
                }
            }
        }

        return $this->returnJson(true, 'Hak akses berhasil diperbarui');
    }

    public function destroy($id)
    {
        $mapping = MapUserApp::findOrFail($id);
        $mapping->update(['data_status' => 0]);

        return $this->returnJson(true, 'Hak akses berhasil dihapus');
    }
}
