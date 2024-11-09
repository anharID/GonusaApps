@extends('dashboard.layouts.app')

@push('styles')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Manajemen User</h1>

<!-- Form User -->
<div class="card shadow mb-4" id="formCard" style="display: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" id="formTitle">Tambah User</h6>
    </div>
    <div class="card-body">
        <form id="userForm">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="id" id="userId">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode User</label>
                        <input type="text" class="form-control" id="user_code" name="user_code" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" id="user_fullname" name="user_fullname" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Departemen</label>
                        <select class="form-control" id="department" name="department" required>
                            <option value="">Pilih Departemen</option>
                            <option value="IT">IT</option>
                            <option value="Operation">Operation</option>
                            <option value="Finance">Finance</option>
                            <option value="Accounting">Accounting</option>
                            <option value="HR">HR</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="user_password" name="user_password">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="data_status" name="data_status" value="1">
                    <label class="custom-control-label" for="data_status">User Aktif</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="toggleForm(false)">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel User -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Daftar User</h6>
        <button class="btn btn-primary btn-sm float-right" onclick="toggleForm(true)">
            <i class="fas fa-plus"></i> Tambah User
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode User</th>
                        <th>Nama Lengkap</th>
                        <th>Departemen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    let table;

    $(document).ready(function() {
        table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.data') }}",
            columns: [
                {data: 'user_code', name: 'user_code'},
                {data: 'user_fullname', name: 'user_fullname'},
                {data: 'department', name: 'department'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        // Handle form submit
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#userId').val();
            const url = id ? `/users/${id}` : '/users';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload();
                    toggleForm(false);
                    resetForm();
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        });
    });

    function toggleForm(show) {
        if (show) {
            $('#formCard').slideDown();
            $('html, body').animate({
                scrollTop: $("#formCard").offset().top - 100
            }, 500);
        } else {
            $('#formCard').slideUp();
            resetForm();
        }
    }

    function resetForm() {
        $('#userForm')[0].reset();
        $('#userId').val('');
        $('#formTitle').text('Tambah User');
        $('input[name="_method"]').val('POST');
    }

    function editUser(id) {
        let row = table.row($(`button[data-id="${id}"]`).closest('tr')).data();

        $('#userId').val(id);
        $('#user_code').val(row.user_code);
        $('#user_fullname').val(row.user_fullname);
        $('#department').val(row.department);
        $('#user_password').val('');

        $('#formTitle').text('Edit User');
        $('input[name="_method"]').val('PUT');

        toggleForm(true);
    }

    function deleteUser(id) {
        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
            $.ajax({
                url: `/users/${id}`,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        }
    }
</script>
@endpush
