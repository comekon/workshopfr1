@extends('layouts.antrian-public')

@section('title', 'Tiket Antrian ' . $antrian->nomor_antrian)

@section('style-page')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Nunito:wght@400;600;700;800&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Nunito', sans-serif;
        background: #0a0e1a;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tiket-wrapper {
        width: 100%;
        max-width: 400px;
        padding: 20px;
        animation: ticketEntry 0.5s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @keyframes ticketEntry {
        from { opacity: 0; transform: translateY(20px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .tiket-card {
        background: linear-gradient(160deg, #151b33 0%, #0f1629 100%);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #1e2a4a;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    .tiket-header {
        background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
        padding: 20px 24px;
        text-align: center;
        position: relative;
    }

    .tiket-header::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 24px;
        right: 24px;
        height: 24px;
        background: #151b33;
        border-radius: 12px 12px 0 0;
    }

    .tiket-header p {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 4px;
    }

    .tiket-nomor {
        font-family: 'Orbitron', monospace;
        font-size: 72px;
        font-weight: 900;
        color: #fff;
        line-height: 1.1;
        text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
    }

    .tiket-body {
        padding: 36px 28px 28px;
        text-align: center;
    }

    .tiket-nama {
        font-size: 22px;
        font-weight: 700;
        color: #c5cee0;
        margin-bottom: 24px;
    }

    .tiket-nama span {
        color: #8892b0;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 4px;
    }

    .tiket-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #2d3561, transparent);
        margin: 0 0 20px;
    }

    .status-row {
        display: flex;
        justify-content: center;
        margin-bottom: 8px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .status-badge i { font-size: 16px; }

    .status-menunggu {
        background: rgba(245, 166, 35, 0.12);
        color: #f5a623;
        border: 1px solid rgba(245, 166, 35, 0.25);
    }

    .status-dipanggil {
        background: rgba(76, 201, 134, 0.12);
        color: #4cc986;
        border: 1px solid rgba(76, 201, 134, 0.25);
        animation: statusPulse 1.5s ease-in-out infinite;
    }

    .status-selesai {
        background: rgba(100, 149, 237, 0.12);
        color: #6495ed;
        border: 1px solid rgba(100, 149, 237, 0.25);
    }

    .status-terlewat {
        background: rgba(230, 57, 70, 0.12);
        color: #e63946;
        border: 1px solid rgba(230, 57, 70, 0.25);
    }

    @keyframes statusPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(76, 201, 134, 0.2); }
        50% { box-shadow: 0 0 0 8px rgba(76, 201, 134, 0); }
    }

    .notifikasi {
        display: none;
        margin-top: 20px;
        padding: 16px 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(76, 201, 134, 0.15), rgba(76, 201, 134, 0.05));
        border: 1px solid rgba(76, 201, 134, 0.3);
        color: #4cc986;
        font-size: 15px;
        font-weight: 700;
        animation: notifBounce 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .notifikasi i { font-size: 18px; margin-right: 6px; }

    @keyframes notifBounce {
        0% { opacity: 0; transform: translateY(10px); }
        60% { transform: translateY(-4px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .tiket-footer {
        padding: 0 28px 24px;
        text-align: center;
    }

    .tiket-footer p {
        font-size: 11px;
        color: #3d4663;
        letter-spacing: 1px;
    }
</style>
@endsection

@section('content')
<div class="tiket-wrapper">
    <div class="tiket-card">
        <div class="tiket-header">
            <p>Nomor Antrian Anda</p>
            <div class="tiket-nomor">{{ $antrian->nomor_antrian }}</div>
        </div>

        <div class="tiket-body">
            <div class="tiket-nama">
                <span>Nama</span>
                {{ $antrian->nama }}
            </div>

            <div class="tiket-divider"></div>

            <div class="status-row">
                <div id="status-badge" class="status-badge status-{{ $antrian->status }}">
                    <i class="mdi mdi-circle-medium"></i>
                    <span>{{ ucfirst($antrian->status) }}</span>
                </div>
            </div>

            <div id="notifikasi" class="notifikasi">
                <i class="mdi mdi-bell-ring-outline"></i>
                Nomor Anda sedang dipanggil! Silakan menuju loket.
            </div>
        </div>

        <div class="tiket-footer">
            <p>Sistem Antrian Digital</p>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
var antrianId = {{ $antrian->id }};

var eventSource = new EventSource('{{ route("antrian.sse") }}');

var statusIcons = {
    menunggu: 'mdi-clock-outline',
    dipanggil: 'mdi-bullhorn-outline',
    selesai: 'mdi-check-circle-outline',
    terlewat: 'mdi-close-circle-outline'
};

function updateStatus(status, text) {
    var badge = document.getElementById('status-badge');
    badge.className = 'status-badge status-' + status;
    var icon = badge.querySelector('i');
    icon.className = 'mdi ' + (statusIcons[status] || 'mdi-circle-medium');
    badge.querySelector('span').textContent = text;
}

eventSource.addEventListener('antrian_dipanggil', function(e) {
    var data = JSON.parse(e.data);
    if (data.id === antrianId) {
        updateStatus('dipanggil', 'Dipanggil');
        document.getElementById('notifikasi').style.display = 'block';
    }
});

eventSource.addEventListener('antrian_selesai', function(e) {
    var data = JSON.parse(e.data);
    if (data.id === antrianId) {
        updateStatus('selesai', 'Selesai');
        document.getElementById('notifikasi').style.display = 'none';
    }
});

eventSource.addEventListener('antrian_terlewat', function(e) {
    var data = JSON.parse(e.data);
    if (data.id === antrianId) {
        updateStatus('terlewat', 'Terlewat');
        document.getElementById('notifikasi').style.display = 'none';
    }
});
</script>
@endpush
