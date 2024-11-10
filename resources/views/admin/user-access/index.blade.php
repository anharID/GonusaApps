@extends('dashboard.layouts.app')

@push('styles')
<link href="{{ asset('/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<style>
    .app-checkbox-container {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e3e6f0;
        padding: 15px;
        border-radius: 5px;
    }

    .app-checkbox-item {
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Manajemen Hak Akses</h1>

<!-- Form Pengaturan Akses -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pengaturan Hak Akses</h6>
    </div>
    <div class="card-body">
        <form id="accessForm">
            @csrf
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Pilih Pengguna</label>
                    <select class="form-control" name="user_id" id="userSelect" required>
                        <option value="">Pilih Pengguna</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->user_fullname }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tambahkan hidden input untuk memastikan app_ids selalu terkirim -->
            <input type="hidden" name="app_ids" value="">

            <div class="row mt-3">
                <div class="col-md-12">
                    <label>Pilih Aplikasi yang Dapat Diakses</label>
                    <div class="app-checkbox-container">
                        @foreach($apps as $app)
                        <div class="app-checkbox-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="app_ids[]"
                                    value="{{ $app->id }}" id="app{{ $app->id }}">
                                <label class="custom-control-label" for="app{{ $app->id }}">
                                    {{ $app->app_name }} ({{ $app->app_group }})
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Simpan Hak Akses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Hak Akses -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Hak Akses</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="accessTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Akses Aplikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = $('#accessTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user-access.data') }}",
            columns: [
                { data: 'user_name', name: 'user_name' },
                { data: 'app_access', name: 'app_access' },
                {
                    data: 'action',
                    name: 'action',
                    render: function(data, type, row) {
                        return `<button onclick="editUserAccess(${row.id})" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Ubah Akses
                                </button>`;
                    }
                }
            ]
        });

        // Load akses aplikasi ketika user dipilih
        $('#userSelect').change(function() {
            loadUserApps($(this).val());
        });

        // Handle form submit
        $('#accessForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('user-access.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload();
                    // Reset form setelah berhasil
                    $('#userSelect').val('').trigger('change');
                    $('input[name="app_ids[]"]').prop('checked', false);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });

    // Fungsi untuk load aplikasi user
    function loadUserApps(userId) {
        if (userId) {
            // Reset semua checkbox dulu
            $('input[name="app_ids[]"]').prop('checked', false);

            // Load data akses
            $.get(`/user-access/${userId}/apps`, function(data) {
                // Check aplikasi yang sudah diakses
                data.forEach(function(appId) {
                    $(`#app${appId}`).prop('checked', true);
                });
            });
        }
    }

    // Fungsi untuk edit akses user
    function editUserAccess(userId) {
        // Scroll ke form
        $('html, body').animate({
            scrollTop: $("#accessForm").offset().top - 100
        }, 500);

        // Set value user dan trigger change event
        $('#userSelect').val(userId).trigger('change');
    }
</script>
@endpush