@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin Absensi NFC</h4>

                {{-- Tabs --}}
                <ul class="nav nav-tabs" id="adminTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-mahasiswa-tab" data-bs-toggle="tab" href="#tab-mahasiswa" role="tab">Data Mahasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-kartu-tab" data-bs-toggle="tab" href="#tab-kartu" role="tab">Kartu NFC</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-rekap-tab" data-bs-toggle="tab" href="#tab-rekap" role="tab">Rekap Kehadiran</a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="adminTabContent">

                    {{-- Tab Data Mahasiswa --}}
                    <div class="tab-pane fade show active" id="tab-mahasiswa" role="tabpanel">
                        <button class="btn btn-primary btn-sm mb-3" onclick="showModalMahasiswa()">
                            <i class="mdi mdi-plus"></i> Tambah Mahasiswa
                        </button>

                        <div class="table-responsive">
                            <table class="table table-striped" id="tabel-mahasiswa">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Kartu NFC</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mahasiswa">
                                    @forelse($mahasiswas as $mhs)
                                    <tr id="mhs-row-{{ $mhs->id }}">
                                        <td>{{ $mhs->nim }}</td>
                                        <td>{{ $mhs->nama }}</td>
                                        <td>{{ $mhs->kelas ?? '-' }}</td>
                                        <td>{{ $mhs->kartuNfc->uid ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="editMahasiswa({{ $mhs->id }}, '{{ $mhs->nim }}', '{{ $mhs->nama }}', '{{ $mhs->kelas }}')">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="hapusMahasiswa({{ $mhs->id }})">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr id="mhs-kosong">
                                        <td colspan="5" class="text-center text-muted">Belum ada data mahasiswa</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab Kartu NFC --}}
                    <div class="tab-pane fade" id="tab-kartu" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Mahasiswa</label>
                                <select class="form-control" id="kartu-mahasiswa-id">
                                    <option value="">-- Pilih Mahasiswa --</option>
                                    @foreach($mahasiswas as $mhs)
                                        @if(!$mhs->kartuNfc)
                                        <option value="{{ $mhs->id }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>UID Kartu</label>
                                <input type="text" class="form-control" id="kartu-uid" placeholder="Scan atau ketik UID">
                            </div>
                            <div class="col-md-3">
                                <label>Label (opsional)</label>
                                <input type="text" class="form-control" id="kartu-label" placeholder="e.g. KTP, e-Money">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-success btn-sm btn-block" onclick="scanKartuNfc()">
                                    <i class="mdi mdi-nfc"></i> Scan
                                </button>
                                <button class="btn btn-primary btn-sm btn-block ms-1" onclick="simpanKartu()">
                                    <i class="mdi mdi-content-save"></i> Simpan
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>UID</th>
                                        <th>Label</th>
                                        <th>Mahasiswa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-kartu">
                                    @forelse($kartus as $kartu)
                                    <tr id="kartu-row-{{ $kartu->id }}">
                                        <td><code>{{ $kartu->uid }}</code></td>
                                        <td>{{ $kartu->label ?? '-' }}</td>
                                        <td>{{ $kartu->mahasiswa->nim }} - {{ $kartu->mahasiswa->nama }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="hapusKartu({{ $kartu->id }})">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr id="kartu-kosong">
                                        <td colspan="4" class="text-center text-muted">Belum ada kartu terdaftar</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab Rekap Kehadiran --}}
                    <div class="tab-pane fade" id="tab-rekap" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label>Mata Kuliah</label>
                                <input type="text" class="form-control" id="rekap-matkul" placeholder="Nama mata kuliah">
                            </div>
                            <div class="col-md-3">
                                <label>Pertemuan Ke</label>
                                <input type="number" class="form-control" id="rekap-pertemuan" min="1" value="1">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary btn-sm" onclick="loadRekap()">
                                    <i class="mdi mdi-magnify"></i> Tampilkan Rekap
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Waktu Scan</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-rekap">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Pilih mata kuliah dan pertemuan untuk melihat rekap</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Mahasiswa --}}
<div class="modal fade" id="modalMahasiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMahasiswaTitle">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="mhs-edit-id">
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" class="form-control" id="mhs-nim" placeholder="Nomor Induk Mahasiswa">
                </div>
                <div class="form-group mt-2">
                    <label>Nama</label>
                    <input type="text" class="form-control" id="mhs-nama" placeholder="Nama lengkap">
                </div>
                <div class="form-group mt-2">
                    <label>Kelas</label>
                    <input type="text" class="form-control" id="mhs-kelas" placeholder="Kelas/Prodi (opsional)">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanMahasiswa()">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
const csrfToken = '{{ csrf_token() }}';
const baseUrl = '{{ url("/absensi/admin") }}';

// =============================================
// MAHASISWA CRUD
// =============================================
function showModalMahasiswa() {
    document.getElementById('mhs-edit-id').value = '';
    document.getElementById('mhs-nim').value = '';
    document.getElementById('mhs-nama').value = '';
    document.getElementById('mhs-kelas').value = '';
    document.getElementById('modalMahasiswaTitle').textContent = 'Tambah Mahasiswa';
    new bootstrap.Modal(document.getElementById('modalMahasiswa')).show();
}

function editMahasiswa(id, nim, nama, kelas) {
    document.getElementById('mhs-edit-id').value = id;
    document.getElementById('mhs-nim').value = nim;
    document.getElementById('mhs-nama').value = nama;
    document.getElementById('mhs-kelas').value = kelas;
    document.getElementById('modalMahasiswaTitle').textContent = 'Edit Mahasiswa';
    new bootstrap.Modal(document.getElementById('modalMahasiswa')).show();
}

async function simpanMahasiswa() {
    const id = document.getElementById('mhs-edit-id').value;
    const nim = document.getElementById('mhs-nim').value.trim();
    const nama = document.getElementById('mhs-nama').value.trim();
    const kelas = document.getElementById('mhs-kelas').value.trim();

    if (!nim || !nama) {
        alert('NIM dan Nama wajib diisi!');
        return;
    }

    const url = id
        ? `${baseUrl}/mahasiswa/${id}`
        : `${baseUrl}/mahasiswa`;
    const method = id ? 'PUT' : 'POST';

    try {
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nim, nama, kelas: kelas || null }),
        });

        const data = await res.json();

        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Gagal menyimpan');
        }
    } catch (e) {
        alert('Error: ' + e.message);
    }
}

async function hapusMahasiswa(id) {
    if (!confirm('Yakin hapus mahasiswa ini? Kartu NFC dan data absensi juga akan terhapus.')) return;

    try {
        const res = await fetch(`${baseUrl}/mahasiswa/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        });

        const data = await res.json();
        if (data.success) {
            document.getElementById(`mhs-row-${id}`)?.remove();
        } else {
            alert(data.message);
        }
    } catch (e) {
        alert('Error: ' + e.message);
    }
}

// =============================================
// KARTU NFC
// =============================================
async function scanKartuNfc() {
    if (!('NDEFReader' in window)) {
        alert('Browser tidak mendukung NFC. Gunakan Android Chrome 89+.');
        return;
    }

    try {
        const ndef = new NDEFReader();
        await ndef.scan();
        alert('NFC aktif. Dekatkan kartu...');

        ndef.addEventListener('reading', ({ serialNumber }) => {
            document.getElementById('kartu-uid').value = serialNumber;
            alert('UID terbaca: ' + serialNumber);
        });
    } catch (e) {
        alert('Gagal scan NFC: ' + e.message);
    }
}

async function simpanKartu() {
    const mahasiswa_id = document.getElementById('kartu-mahasiswa-id').value;
    const uid = document.getElementById('kartu-uid').value.trim();
    const label = document.getElementById('kartu-label').value.trim();

    if (!mahasiswa_id || !uid) {
        alert('Pilih mahasiswa dan isi UID kartu!');
        return;
    }

    try {
        const res = await fetch(`${baseUrl}/kartu`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ uid, mahasiswa_id, label: label || null }),
        });

        const data = await res.json();

        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Gagal menyimpan kartu');
        }
    } catch (e) {
        alert('Error: ' + e.message);
    }
}

async function hapusKartu(id) {
    if (!confirm('Yakin hapus kartu NFC ini?')) return;

    try {
        const res = await fetch(`${baseUrl}/kartu/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        });

        const data = await res.json();
        if (data.success) {
            document.getElementById(`kartu-row-${id}`)?.remove();
        } else {
            alert(data.message);
        }
    } catch (e) {
        alert('Error: ' + e.message);
    }
}

// =============================================
// REKAP KEHADIRAN
// =============================================
async function loadRekap() {
    const matkul = document.getElementById('rekap-matkul').value.trim();
    const pertemuan = document.getElementById('rekap-pertemuan').value;

    if (!matkul || !pertemuan) {
        alert('Isi mata kuliah dan pertemuan!');
        return;
    }

    try {
        const res = await fetch(`${baseUrl}/rekap?mata_kuliah=${encodeURIComponent(matkul)}&pertemuan=${pertemuan}`, {
            headers: { 'Accept': 'application/json' },
        });

        const data = await res.json();

        if (data.success) {
            const tbody = document.getElementById('tbody-rekap');
            tbody.innerHTML = '';

            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada data</td></tr>';
                return;
            }

            data.data.forEach((item, i) => {
                const statusBadge = item.status === 'hadir'
                    ? '<span class="badge badge-success">Hadir</span>'
                    : '<span class="badge badge-danger">Belum</span>';

                tbody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${item.nim}</td>
                        <td>${item.nama}</td>
                        <td>${statusBadge}</td>
                        <td>${item.waktu_scan || '-'}</td>
                    </tr>
                `;
            });
        }
    } catch (e) {
        alert('Error: ' + e.message);
    }
}
</script>
@endpush
