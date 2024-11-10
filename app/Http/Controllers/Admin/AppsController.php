<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\App;
use Yajra\DataTables\DataTables;

class AppsController extends Controller
{
    public function index()
    {
        $title = 'Manajemen Apikasi';
        return view('admin.apps.index', compact('title'));
    }

    public function getData()
    {
        $apps = App::query();
        return DataTables::of($apps)
            ->addColumn('action', function ($app) {
                return '
                    <button onclick="editApp(' . $app->id . ')" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteApp(' . $app->id . ')" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                ';
            })
            ->addColumn('status', function ($app) {
                return $app->data_status
                    ? '<span class="badge badge-success">Aktif</span>'
                    : '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'app_code' => 'required|unique:apps',
            'app_name' => 'required',
            'app_group' => 'required',
            'app_url' => 'required'
        ]);

        $data = $request->all();
        $data['data_status'] = $request->has('data_status');

        App::create($data);

        return $this->returnJson(true, 'Aplikasi berhasil ditambahkan');
        // return response()->json(['message' => 'Aplikasi berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $app = App::findOrFail($id);
        return response()->json($app);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'app_code' => 'required|unique:apps,app_code,' . $id,
            'app_name' => 'required',
            'app_group' => 'required',
            'app_url' => 'required'
        ]);

        $app = App::findOrFail($id);
        $data = $request->all();
        $data['data_status'] = $request->has('data_status');

        $app->update($data);

        // return response()->json(['message' => 'Aplikasi berhasil diperbarui']);
        return $this->returnJson(true, 'Aplikasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $app = App::findOrFail($id);
        $app->delete();

        return $this->returnJson(true, 'Aplikasi berhasil dihapus');
        // return response()->json(['message' => 'Aplikasi berhasil dihapus']);
    }
}
