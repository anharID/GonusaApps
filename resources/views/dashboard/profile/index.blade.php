@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profil Saya</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kode User</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->user_code }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control @error('user_fullname') is-invalid @enderror"
                            name="user_fullname" value="{{ old('user_fullname', auth()->user()->user_fullname) }} "
                            readonly>
                    </div>
                    <div class="form-group">
                        <label>Departemen</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->department }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                name="current_password">
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" class="form-control @error('user_password') is-invalid @enderror"
                                name="user_password">
                            @error('user_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="user_password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection