@extends('dashboard.layouts.app')

@push('styles')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Manajemen Aplikasi</h1>

<!-- Form Aplikasi -->
<div class="card shadow mb-4" id="formCard" style="display: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" id="formTitle">Tambah Aplikasi</h6>
    </div>
    <div class="card-body">
        <form id="appForm">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="id" id="app_id">

            <div class="form-group">
                <label>Kode Aplikasi</label>
                <input type="text" name="app_code" id="app_code" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nama Aplikasi</label>
                <input type="text" name="app_name" id="app_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Grup Aplikasi</label>
                <select name="app_group" id="app_group" class="form-control" required>
                    <option value="">Pilih Grup Aplikasi</option>
                    <option value="HR">HR</option>
                    <option value="Finance">Finance</option>
                    <option value="Operation">Operation</option>
                    <option value="Sales">Sales</option>
                </select>
            </div>

            <div class="form-group">
                <label>URL Aplikasi</label>
                <input type="text" name="app_url" id="app_url" class="form-control" required>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="data_status" name="data_status" value="1">
                    <label class="custom-control-label" for="data_status">Aktif</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="toggleForm(false)">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Aplikasi -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Daftar Aplikasi</h6>
        <button class="btn btn-primary btn-sm float-right" onclick="toggleForm(true)">
            <i class="fas fa-plus"></i> Tambah Aplikasi
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="appsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode Aplikasi</th>
                        <th>Nama Aplikasi</th>
                        <th>Grup</th>
                        <th>URL</th>
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
        table = $('#appsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("apps.data") }}',
            columns: [
                { data: 'app_code', name: 'app_code' },
                { data: 'app_name', name: 'app_name' },
                { data: 'app_group', name: 'app_group' },
                { data: 'app_url', name: 'app_url' },
                { data: 'status', name: 'status' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Handle form submit
        $('#appForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#app_id').val();
            const url = id ? `/apps/${id}` : '/apps';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    alert('Data berhasil disimpan');
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
        $('#appForm')[0].reset();
        $('#app_id').val('');
        $('#formTitle').text('Tambah Aplikasi');
        $('input[name="_method"]').val('POST');
    }

    function editApp(id) {
        $.get(`/apps/${id}/edit`, function(data) {
            $('#app_id').val(data.id);
            $('#app_code').val(data.app_code);
            $('#app_name').val(data.app_name);
            $('#app_group').val(data.app_group);
            $('#app_url').val(data.app_url);
            $('#data_status').prop('checked', data.data_status);

            $('#formTitle').text('Edit Aplikasi');
            $('input[name="_method"]').val('PUT');

            toggleForm(true);
        });
    }

    function deleteApp(id) {
        if (confirm('Apakah Anda yakin ingin menghapus aplikasi ini?')) {
            $.ajax({
                url: `/apps/${id}`,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert('Aplikasi berhasil dihapus');
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
