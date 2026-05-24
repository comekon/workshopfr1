@extends('layouts.antrian-public')

@section('title', 'Papan Antrian')

@section('style-page')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Nunito:wght@400;600;700;800&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Nunito', sans-serif;
        background: #0a0e1a;
        overflow: hidden;
        height: 100vh;
    }

    .papan-container {
        height: 100vh;
        display: grid;
        grid-template-columns: 1fr 300px;
        grid-template-rows: 64px 1fr;
        color: #fff;
    }

    .header-bar {
        grid-column: 1 / -1;
        background: linear-gradient(90deg, #0f1629 0%, #1a2340 100%);
        border-bottom: 2px solid #e63946;
        padding: 0 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .header-bar::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #e63946, transparent);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-logo {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #e63946;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .header-bar h4 {
        font-family: 'Nunito', sans-serif;
        font-weight: 800;
        font-size: 16px;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .header-time {
        font-family: 'Orbitron', monospace;
        font-size: 14px;
        color: #8892b0;
        letter-spacing: 1px;
    }

    .sound-toggle {
        padding: 6px 16px;
        border-radius: 6px;
        border: 1px solid #2d3561;
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Nunito', sans-serif;
        transition: all 0.2s;
    }

    .sound-off { background: rgba(230, 57, 70, 0.15); color: #e63946; border-color: #e63946; }
    .sound-on { background: rgba(76, 201, 134, 0.15); color: #4cc986; border-color: #4cc986; }
    .sound-toggle:hover { opacity: 0.8; }

    .main-display {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        background: radial-gradient(ellipse at center, #111631 0%, #0a0e1a 70%);
    }

    .main-display::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            repeating-linear-gradient(0deg, transparent, transparent 3px, rgba(255,255,255,0.008) 3px, rgba(255,255,255,0.008) 4px);
        pointer-events: none;
    }

    .idle-state {
        text-align: center;
        opacity: 0.4;
    }

    .idle-state i {
        font-size: 56px;
        color: #2d3561;
        margin-bottom: 12px;
    }

    .idle-state p {
        font-size: 18px;
        color: #4a5580;
        letter-spacing: 1px;
    }

    .calling-state {
        text-align: center;
        animation: fadeInScale 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .calling-label {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 6px;
        color: #e63946;
        margin-bottom: 8px;
    }

    .calling-divider {
        width: 80px;
        height: 3px;
        background: #e63946;
        margin: 0 auto 24px;
        border-radius: 2px;
    }

    .calling-nomor {
        font-family: 'Orbitron', monospace;
        font-size: 180px;
        font-weight: 900;
        line-height: 1;
        color: #fff;
        text-shadow: 0 0 60px rgba(230, 57, 70, 0.3), 0 0 120px rgba(230, 57, 70, 0.1);
        margin-bottom: 12px;
    }

    .calling-nama {
        font-family: 'Nunito', sans-serif;
        font-size: 32px;
        font-weight: 700;
        color: #c5cee0;
        margin-bottom: 20px;
    }

    .calling-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 28px;
        background: rgba(230, 57, 70, 0.12);
        border: 1px solid rgba(230, 57, 70, 0.3);
        border-radius: 8px;
        color: #e63946;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 1px;
        animation: softPulse 2s ease-in-out infinite;
    }

    @keyframes softPulse {
        0%, 100% { border-color: rgba(230, 57, 70, 0.3); }
        50% { border-color: rgba(230, 57, 70, 0.7); }
    }

    .waiting-sidebar {
        background: linear-gradient(180deg, #0f1629 0%, #111d3a 100%);
        border-left: 1px solid #1e2a4a;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sidebar-header {
        padding: 20px 20px 16px;
        border-bottom: 1px solid #1e2a4a;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-header h6 {
        font-size: 13px;
        font-weight: 700;
        color: #8892b0;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
    }

    .waiting-count {
        background: #1e2a4a;
        color: #e63946;
        font-family: 'Orbitron', monospace;
        font-size: 13px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .waiting-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0;
    }

    .waiting-list::-webkit-scrollbar { width: 4px; }
    .waiting-list::-webkit-scrollbar-track { background: transparent; }
    .waiting-list::-webkit-scrollbar-thumb { background: #2d3561; border-radius: 2px; }

    .waiting-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        gap: 14px;
        border-bottom: 1px solid rgba(30, 42, 74, 0.6);
        transition: background 0.2s;
    }

    .waiting-item:hover { background: rgba(45, 53, 97, 0.3); }

    .waiting-item-num {
        font-family: 'Orbitron', monospace;
        font-size: 14px;
        font-weight: 700;
        color: #e63946;
        background: rgba(230, 57, 70, 0.1);
        padding: 4px 10px;
        border-radius: 4px;
        min-width: 56px;
        text-align: center;
    }

    .waiting-item-nama {
        font-size: 14px;
        color: #8892b0;
        font-weight: 600;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .waiting-empty {
        text-align: center;
        padding: 40px 20px;
        color: #2d3561;
        font-size: 14px;
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.92); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endsection

@section('content')
<div class="papan-container">
    <div class="header-bar">
        <div class="header-left">
            <div class="header-logo">
                <i class="mdi mdi-hospital-box"></i>
            </div>
            <h4>Papan Antrian</h4>
        </div>
        <div class="header-time" id="clock"></div>
        <button id="soundToggle" class="sound-toggle sound-off" onclick="toggleSound()">
            <i class="mdi mdi-volume-off me-1"></i> Suara
        </button>
    </div>

    <div class="main-display" id="calling-display">
        @if($sedangDipanggil)
        <div class="calling-state">
            <div class="calling-label">Sedang Memanggil</div>
            <div class="calling-divider"></div>
            <div class="calling-nomor">{{ $sedangDipanggil->nomor_antrian }}</div>
            <div class="calling-nama">{{ $sedangDipanggil->nama }}</div>
            <div class="calling-action">
                <i class="mdi mdi-arrow-right-bold-box"></i>
                Silakan menuju loket
            </div>
        </div>
        @else
        <div class="idle-state">
            <i class="mdi mdi-clock-outline"></i>
            <p>Belum ada yang dipanggil</p>
        </div>
        @endif
    </div>

    <div class="waiting-sidebar">
        <div class="sidebar-header">
            <h6>Antrian Menunggu</h6>
            <span class="waiting-count" id="waiting-count">{{ $menunggu->count() }}</span>
        </div>
        <div class="waiting-list" id="waiting-list-items">
            @foreach($menunggu as $a)
            <div class="waiting-item" data-nomor="{{ $a->nomor_antrian }}">
                <span class="waiting-item-num">{{ $a->nomor_antrian }}</span>
                <span class="waiting-item-nama">{{ $a->nama }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<audio src="{{ asset('audio/dingdong.mp3') }}" id="audio"></audio>
@endsection

@push('js-page')
<script>
function updateClock() {
    var now = new Date();
    var h = String(now.getHours()).padStart(2, '0');
    var m = String(now.getMinutes()).padStart(2, '0');
    var s = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('clock').textContent = h + ':' + m + ':' + s;
}
updateClock();
setInterval(updateClock, 1000);

let soundEnabled = false;

function toggleSound() {
    soundEnabled = !soundEnabled;
    var btn = document.getElementById('soundToggle');
    if (soundEnabled) {
        btn.className = 'sound-toggle sound-on';
        btn.innerHTML = '<i class="mdi mdi-volume-high me-1"></i> Suara';
    } else {
        btn.className = 'sound-toggle sound-off';
        btn.innerHTML = '<i class="mdi mdi-volume-off me-1"></i> Suara';
    }
}

function playNotification(nomor, nama) {
    if (!soundEnabled) return;
    var audio = document.getElementById('audio');
    var pesan = new SpeechSynthesisUtterance(
        'Nomor antrian ' + nomor + ', ' + nama + '. Silakan masuk.'
    );
    pesan.lang = 'id-ID';
    pesan.rate = 0.85;
    pesan.pitch = 1.0;
    pesan.volume = 1.0;

    audio.currentTime = 0;
    audio.play();
    audio.onended = function() {
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(pesan);
    };
}

function createCallingDisplay(nomor, nama) {
    var wrapper = document.createElement('div');
    wrapper.className = 'calling-state';

    var label = document.createElement('div');
    label.className = 'calling-label';
    label.textContent = 'SEDANG MEMANGGIL';

    var divider = document.createElement('div');
    divider.className = 'calling-divider';

    var numEl = document.createElement('div');
    numEl.className = 'calling-nomor';
    numEl.textContent = nomor;

    var namaEl = document.createElement('div');
    namaEl.className = 'calling-nama';
    namaEl.textContent = nama;

    var actionEl = document.createElement('div');
    actionEl.className = 'calling-action';
    var iconEl = document.createElement('i');
    iconEl.className = 'mdi mdi-arrow-right-bold-box';
    var textEl = document.createTextNode(' Silakan menuju loket');
    actionEl.appendChild(iconEl);
    actionEl.appendChild(textEl);

    wrapper.appendChild(label);
    wrapper.appendChild(divider);
    wrapper.appendChild(numEl);
    wrapper.appendChild(namaEl);
    wrapper.appendChild(actionEl);
    return wrapper;
}

function createIdleDisplay() {
    var wrapper = document.createElement('div');
    wrapper.className = 'idle-state';
    var icon = document.createElement('i');
    icon.className = 'mdi mdi-clock-outline';
    var p = document.createElement('p');
    p.textContent = 'Belum ada yang dipanggil';
    wrapper.appendChild(icon);
    wrapper.appendChild(p);
    return wrapper;
}

function createWaitingItem(nomor, nama) {
    var div = document.createElement('div');
    div.className = 'waiting-item';
    div.dataset.nomor = nomor;
    var numSpan = document.createElement('span');
    numSpan.className = 'waiting-item-num';
    numSpan.textContent = nomor;
    var namaSpan = document.createElement('span');
    namaSpan.className = 'waiting-item-nama';
    namaSpan.textContent = nama;
    div.appendChild(numSpan);
    div.appendChild(namaSpan);
    return div;
}

function getCurrentNomor() {
    var numEl = document.querySelector('#calling-display .calling-nomor');
    return numEl ? numEl.textContent : null;
}

var eventSource = new EventSource('/sse/antrian');

eventSource.addEventListener('antrian_dipanggil', function(e) {
    var data = JSON.parse(e.data);
    playNotification(data.nomor_antrian, data.nama);
    var display = document.getElementById('calling-display');
    display.textContent = '';
    display.appendChild(createCallingDisplay(data.nomor_antrian, data.nama));
    var items = document.querySelectorAll('.waiting-item[data-nomor="' + data.nomor_antrian + '"]');
    items.forEach(function(item) { item.remove(); });
});

eventSource.addEventListener('antrian_baru', function(e) {
    var data = JSON.parse(e.data);
    var list = document.getElementById('waiting-list-items');
    list.appendChild(createWaitingItem(data.nomor_antrian, data.nama));
    var countEl = document.getElementById('waiting-count');
    countEl.textContent = parseInt(countEl.textContent) + 1;
});

eventSource.addEventListener('antrian_selesai', function(e) {
    var data = JSON.parse(e.data);
    if (getCurrentNomor() == data.nomor_antrian) {
        var display = document.getElementById('calling-display');
        display.textContent = '';
        display.appendChild(createIdleDisplay());
    }
});

eventSource.addEventListener('antrian_terlewat', function(e) {
    var data = JSON.parse(e.data);
    if (getCurrentNomor() == data.nomor_antrian) {
        var display = document.getElementById('calling-display');
        display.textContent = '';
        display.appendChild(createIdleDisplay());
    }
    var items = document.querySelectorAll('.waiting-item[data-nomor="' + data.nomor_antrian + '"]');
    items.forEach(function(item) { item.remove(); });
});
</script>
@endpush
