@extends('layouts.app')

@section('title', 'Tambah Customer 2 - File')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tambah Customer 2 (Foto File)</h4>
        <p class="text-muted">Foto akan disimpan sebagai file dan path-nya disimpan dalam database</p>

        <form action="{{ route('customer.store2') }}" method="POST" id="formCustomer">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" required></textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="provinsi" class="form-control @error('provinsi') is-invalid @enderror" required>
                        @error('provinsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror" required>
                        @error('kota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" required>
                        @error('kecamatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kodepos (Kelurahan)</label>
                        <input type="text" name="kodepos" class="form-control @error('kodepos') is-invalid @enderror" required>
                        @error('kodepos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <div class="border rounded p-3 text-center" style="min-height: 200px;">
                            <img id="previewFoto" src="" alt="Preview" class="img-fluid d-none" style="max-height: 180px;">
                            <div id="noFoto" class="text-muted py-5">
                                <i class="mdi mdi-camera" style="font-size: 3rem;"></i><br>
                                Belum ada foto
                            </div>
                        </div>
                        <input type="hidden" name="foto" id="inputFoto" required>
                        @error('foto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKamera">
                            <i class="mdi mdi-camera"></i> Ambil Foto
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="mdi mdi-content-save"></i> Simpan Data
                </button>
                <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Modal Kamera --}}
<div class="modal fade" id="modalKamera" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ambil Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="border rounded bg-dark d-flex align-items-center justify-content-center" style="height: 300px; overflow: hidden;">
                            <video id="videoKamera" autoplay playsinline class="w-100 h-100" style="object-fit: cover;"></video>
                            <div id="videoPlaceholder" class="text-white">
                                <i class="mdi mdi-video-off" style="font-size: 3rem;"></i><br>
                                Kamera tidak aktif
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Pilih Kamera:</label>
                            <select id="selectKamera" class="form-select">
                                <option value="">Default</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 300px;">
                            <canvas id="canvasSnapshot" class="d-none"></canvas>
                            <img id="imgSnapshot" src="" alt="Snapshot" class="img-fluid d-none" style="max-height: 280px;">
                            <div id="snapshotPlaceholder" class="text-muted text-center">
                                <i class="mdi mdi-image" style="font-size: 3rem;"></i><br>
                                Snapshot akan muncul di sini
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnAmbilFoto" class="btn btn-primary">
                    <i class="mdi mdi-camera"></i> Ambil Foto
                </button>
                <button type="button" id="btnSimpanFoto" class="btn btn-success d-none" data-bs-dismiss="modal">
                    <i class="mdi mdi-check"></i> Simpan Foto
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
let stream = null;

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalKamera');
    const video = document.getElementById('videoKamera');
    const canvas = document.getElementById('canvasSnapshot');
    const btnAmbil = document.getElementById('btnAmbilFoto');
    const btnSimpan = document.getElementById('btnSimpanFoto');
    const imgSnapshot = document.getElementById('imgSnapshot');
    const inputFoto = document.getElementById('inputFoto');
    const previewFoto = document.getElementById('previewFoto');
    const noFoto = document.getElementById('noFoto');

    // Buka kamera saat modal dibuka
    modal.addEventListener('shown.bs.modal', async function() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            document.getElementById('videoPlaceholder').classList.add('d-none');
        } catch (err) {
            console.error('Error accessing camera:', err);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
        }
    });

    // Tutup kamera saat modal ditutup
    modal.addEventListener('hidden.bs.modal', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    });

    // Ambil foto
    btnAmbil.addEventListener('click', function() {
        if (!stream) return;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/png');
        imgSnapshot.src = dataUrl;
        imgSnapshot.classList.remove('d-none');
        document.getElementById('snapshotPlaceholder').classList.add('d-none');
        btnSimpan.classList.remove('d-none');
    });

    // Simpan foto ke form
    btnSimpan.addEventListener('click', function() {
        inputFoto.value = imgSnapshot.src;
        previewFoto.src = imgSnapshot.src;
        previewFoto.classList.remove('d-none');
        noFoto.classList.add('d-none');
    });

    // List available cameras
    async function listCameras() {
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const cameras = devices.filter(d => d.kind === 'videoinput');
            const select = document.getElementById('selectKamera');

            cameras.forEach((cam, idx) => {
                const option = document.createElement('option');
                option.value = cam.deviceId;
                option.text = cam.label || `Kamera ${idx + 1}`;
                select.appendChild(option);
            });

            select.addEventListener('change', async function() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                const constraints = {
                    video: this.value ? { deviceId: { exact: this.value } } : true
                };

                try {
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                    video.srcObject = stream;
                } catch (err) {
                    console.error('Error switching camera:', err);
                }
            });
        } catch (err) {
            console.error('Error listing cameras:', err);
        }
    }

    listCameras();
});
</script>
@endpush
