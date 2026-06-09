@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Absensi NFC Scanner</h4>
                <p class="card-description">Scan kartu NFC mahasiswa untuk mencatat kehadiran</p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mata_kuliah">Mata Kuliah</label>
                            <input type="text" class="form-control" id="mata_kuliah" placeholder="Masukkan nama mata kuliah" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pertemuan">Pertemuan Ke</label>
                            <input type="number" class="form-control" id="pertemuan" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="button" class="btn btn-primary btn-block" id="btn-nfc" onclick="aktivasiNfc()">
                                <i class="mdi mdi-nfc"></i> Aktifkan NFC
                            </button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3" id="nfc-status">
                    <i class="mdi mdi-information-outline"></i> <span id="status-text">NFC belum aktif. Isi mata kuliah & pertemuan, lalu klik "Aktifkan NFC".</span>
                </div>

                <div class="alert alert-success mt-2 d-none" id="nfc-success">
                    <i class="mdi mdi-check-circle"></i> <span id="success-text"></span>
                </div>

                <div class="alert alert-danger mt-2 d-none" id="nfc-error">
                    <i class="mdi mdi-alert-circle"></i> <span id="error-text"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Kehadiran</h4>
                <div class="table-responsive">
                    <table class="table table-striped" id="tabel-hadir">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-hadir">
                            <tr id="row-kosong">
                                <td colspan="4" class="text-center text-muted">Belum ada yang absen</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
let nfcAktif = false;
let nomorUrut = 0;

async function aktivasiNfc() {
    const mataKuliah = document.getElementById('mata_kuliah').value.trim();
    const pertemuan = document.getElementById('pertemuan').value;

    if (!mataKuliah) {
        alert('Isi nama mata kuliah terlebih dahulu!');
        return;
    }
    if (!pertemuan || pertemuan < 1) {
        alert('Isi nomor pertemuan terlebih dahulu!');
        return;
    }

    if (!('NDEFReader' in window)) {
        setStatus('error', 'Browser tidak mendukung NFC. Gunakan Android Chrome versi 89+.');
        return;
    }

    try {
        const ndef = new NDEFReader();
        await ndef.scan();

        nfcAktif = true;
        document.getElementById('btn-nfc').classList.remove('btn-primary');
        document.getElementById('btn-nfc').classList.add('btn-success');
        document.getElementById('btn-nfc').innerHTML = '<i class="mdi mdi-nfc"></i> NFC Aktif';
        setStatus('info', 'NFC aktif, dekatkan kartu mahasiswa...');

        ndef.addEventListener('reading', ({ serialNumber }) => {
            prosesKartu(serialNumber);
        });

        ndef.addEventListener('readingerror', () => {
            setStatus('error', 'Gagal membaca kartu. Coba dekatkan lagi.');
        });

    } catch (error) {
        setStatus('error', 'Gagal mengaktifkan NFC: ' + error.message);
    }
}

async function prosesKartu(uid) {
    const mataKuliah = document.getElementById('mata_kuliah').value.trim();
    const pertemuan = document.getElementById('pertemuan').value;

    hideAlerts();

    try {
        const response = await fetch('{{ route("absensi.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                uid: uid,
                mata_kuliah: mataKuliah,
                pertemuan: parseInt(pertemuan),
            }),
        });

        const data = await response.json();

        if (data.success) {
            setStatus('success', data.message + ' — ' + data.mahasiswa.nama + ' (' + data.mahasiswa.nim + ')');
            tambahBaris(data.mahasiswa);
        } else {
            setStatus('error', data.message);
        }
    } catch (error) {
        setStatus('error', 'Gagal mengirim data: ' + error.message);
    }
}

function tambahBaris(mahasiswa) {
    const tbody = document.getElementById('tbody-hadir');
    const rowKosong = document.getElementById('row-kosong');
    if (rowKosong) rowKosong.remove();

    nomorUrut++;
    const waktu = new Date().toLocaleTimeString('id-ID');

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${nomorUrut}</td>
        <td>${mahasiswa.nim}</td>
        <td>${mahasiswa.nama}</td>
        <td>${waktu}</td>
    `;
    tbody.prepend(tr);
}

function setStatus(type, message) {
    hideAlerts();
    if (type === 'info') {
        document.getElementById('nfc-status').classList.remove('d-none');
        document.getElementById('status-text').textContent = message;
    } else if (type === 'success') {
        document.getElementById('nfc-success').classList.remove('d-none');
        document.getElementById('success-text').textContent = message;
    } else if (type === 'error') {
        document.getElementById('nfc-error').classList.remove('d-none');
        document.getElementById('error-text').textContent = message;
    }
}

function hideAlerts() {
    document.getElementById('nfc-success').classList.add('d-none');
    document.getElementById('nfc-error').classList.add('d-none');
}
</script>
@endpush
