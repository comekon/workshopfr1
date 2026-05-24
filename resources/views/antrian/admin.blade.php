@extends('layouts.app')

@section('title', 'Admin Antrian')

@section('content')
<div class="row">
    {{-- Menunggu --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="mdi mdi-clock-outline me-2"></i>Menunggu (<span id="count-menunggu">{{ $menunggu->count() }}</span>)</h5>
            </div>
            <div class="card-body p-0">
                <div id="list-menunggu">
                    @forelse($menunggu as $a)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom antrian-item" data-id="{{ $a->id }}">
                        <div>
                            <strong class="me-2">#{{ $a->nomor_antrian }}</strong>
                            <span>{{ $a->nama }}</span>
                        </div>
                        <button class="btn btn-sm btn-primary btn-panggil" data-id="{{ $a->id }}">
                            <i class="mdi mdi-bullhorn"></i> Panggil
                        </button>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">Tidak ada antrian menunggu</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Sedang Dipanggil --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="mdi mdi-volume-high me-2"></i>Sedang Dipanggil</h5>
            </div>
            <div class="card-body p-0">
                <div id="list-dipanggil">
                    @forelse($dipanggil as $a)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom antrian-item" data-id="{{ $a->id }}">
                        <div>
                            <strong class="me-2">#{{ $a->nomor_antrian }}</strong>
                            <span>{{ $a->nama }}</span>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-success btn-selesai me-1" data-id="{{ $a->id }}">Selesai</button>
                            <button class="btn btn-sm btn-outline-warning btn-terlambat" data-id="{{ $a->id }}">Terlambat</button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">Tidak ada yang dipanggil</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Terlambat --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="mdi mdi-alert-circle me-2"></i>Terlambat (<span id="count-terlewat">{{ $terlewat->count() }}</span>)</h5>
            </div>
            <div class="card-body p-0">
                <div id="list-terlewat">
                    @forelse($terlewat as $a)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom antrian-item" data-id="{{ $a->id }}">
                        <div>
                            <strong class="me-2">#{{ $a->nomor_antrian }}</strong>
                            <span>{{ $a->nama }}</span>
                        </div>
                        <button class="btn btn-sm btn-warning btn-panggil-ulang" data-id="{{ $a->id }}">
                            <i class="mdi mdi-refresh"></i> Panggil Ulang
                        </button>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">Tidak ada yang terlambat</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function postAction(url) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        }
    });
}

document.addEventListener('click', function(e) {
    const btn = e.target.closest('button');
    if (!btn) return;

    if (btn.classList.contains('btn-panggil')) {
        postAction('/antrian/' + btn.dataset.id + '/panggil');
    } else if (btn.classList.contains('btn-selesai')) {
        postAction('/antrian/' + btn.dataset.id + '/selesai');
    } else if (btn.classList.contains('btn-terlambat')) {
        postAction('/antrian/' + btn.dataset.id + '/terlambat');
    } else if (btn.classList.contains('btn-panggil-ulang')) {
        postAction('/antrian/' + btn.dataset.id + '/panggil');
    }
});

const eventSource = new EventSource('/sse/antrian');

eventSource.addEventListener('antrian_baru', function() { location.reload(); });
eventSource.addEventListener('antrian_dipanggil', function() { location.reload(); });
eventSource.addEventListener('antrian_selesai', function() { location.reload(); });
eventSource.addEventListener('antrian_terlewat', function() { location.reload(); });
</script>
@endpush
