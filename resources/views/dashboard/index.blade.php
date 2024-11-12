@extends('dashboard.layouts.app')

@push('styles')

<style>
    .app-card {
        transition: transform 0.2s;
        border: 1px solid rgba(0, 0, 0, .125);
    }

    .app-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Dashboard Aplikasi</h1>

@if($apps->count() > 0)
@foreach($apps as $groupName => $groupedApps)
<div class="mb-4">
    <h4 class="mb-3">{{ $groupName }}</h4>
    <div class="row">
        @foreach($groupedApps as $app)
        <div class="col-xl-3 col-md-4 col-sm-6 mb-4 app-card-wrapper">
            <a href="{{ $app->app_url }}" target="_blank" class="text-decoration-none">
                <div class="card h-100 app-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-window-maximize fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title text-dark app-name">{{ $app->app_name }}</h5>
                        <p class="card-text small text-muted app-group">{{ $app->app_group }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endforeach
@else
<div class="col-12 text-center">
    <p class="text-muted">Tidak ada aplikasi yang ditemukan</p>
</div>
@endif

@endsection

@push('scripts')

<script>
    function search(keyword) {
        keyword = keyword.toLowerCase();
        const cards = document.querySelectorAll('.app-card-wrapper');

        cards.forEach(card => {
            const appName = card.querySelector('.app-name').textContent.toLowerCase();
            const appGroup = card.querySelector('.app-group').textContent.toLowerCase();

            if (appName.includes(keyword) || appGroup.includes(keyword)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

// Sinkronkan input pencarian desktop dan mobile
    document.getElementById('searchInput').addEventListener('input', function(e) {
        document.getElementById('searchInputMobile').value = e.target.value;
    });

    document.getElementById('searchInputMobile').addEventListener('input', function(e) {
        document.getElementById('searchInput').value = e.target.value;
    });
</script>
@endpush