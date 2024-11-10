<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $title = "Manajemen User";
        return view('admin.users.index', compact('title'));
    }

    public function getData()
    {
        $users = User::select(['id', 'user_code', 'user_fullname', 'department', 'data_status'])->where('id', '!=', 1);

        return DataTables::of($users)
            ->addColumn('status', function ($user) {
                return $user->data_status
                    ? '<span class="badge badge-success">Aktif</span>'
                    : '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($user) {
                return '
                    <button class="btn btn-sm btn-primary" onclick="editUser(' . $user->id . ')" data-id="' . $user->id . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(' . $user->id . ')" data-id="' . $user->id . '">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                ';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_code' => 'required|unique:users',
            'user_fullname' => 'required',
            'department' => 'required',
            'user_password' => 'required|min:6',
        ]);

        $user = User::create([
            'user_code' => $request->user_code,
            'user_fullname' => $request->user_fullname,
            'department' => $request->department,
            'user_password' => Hash::make($request->user_password),
            'data_status' => $request->has('data_status'),
        ]);

        return $this->returnJson(true, 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $app = User::findOrFail($id);
        return response()->json($app);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_code' => 'required|unique:users,user_code,' . $id,
            'user_fullname' => 'required',
            'department' => 'required',
        ]);

        $data = [
            'user_code' => $request->user_code,
            'user_fullname' => $request->user_fullname,
            'department' => $request->department,
            'data_status' => $request->has('data_status'),
        ];

        if ($request->filled('user_password')) {
            $data['user_password'] = Hash::make($request->user_password);
        }

        User::find($id)->update($data);

        return $this->returnJson(true, 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return $this->returnJson(true, 'User berhasil dihapus');
    }
}
