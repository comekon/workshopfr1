@extends('vendor.layouts.app')

@section('title', $menu ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-{{ $menu ? 'pencil' : 'plus-circle' }}"></i>
                </span> {{ $menu ? 'Edit Menu' : 'Tambah Menu Baru' }}
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.menu.index') }}">Menu</a></li>
                    <li class="breadcrumb-item active">{{ $menu ? 'Edit' : 'Tambah' }}</li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Menu</h4>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST"
                      action="{{ $menu ? route('vendor.menu.update', $menu->idmenu) : route('vendor.menu.store') }}"
                      enctype="multipart/form-data"
                      id="formMenu">
                    @csrf
                    @if($menu) @method('PUT') @endif

                    <div class="mb-3">
                        <label for="nama_menu" class="form-label fw-semibold">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_menu" name="nama_menu"
                               value="{{ old('nama_menu', $menu->nama_menu ?? '') }}"
                               placeholder="Contoh: Nasi Goreng Spesial" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga" name="harga"
                                   value="{{ old('harga', $menu->harga ?? '') }}"
                                   placeholder="15000" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="path_gambar" class="form-label fw-semibold">Gambar Menu</label>
                        <input type="file" class="form-control" id="path_gambar" name="path_gambar"
                               accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">Format: JPG, PNG, WebP. Maks 2MB.</div>

                        @if($menu && $menu->path_gambar)
                        <div class="mt-2">
                            <p class="text-muted small mb-1">Gambar saat ini:</p>
                            <img src="{{ asset('storage/menu-images/' . $menu->path_gambar) }}"
                                 alt="{{ $menu->nama_menu }}"
                                 style="width:120px;height:120px;object-fit:cover;border-radius:12px;border:2px solid #eee;">
                        </div>
                        @endif
                    </div>

                    {{-- Preview gambar baru --}}
                    <div id="img-preview-wrap" class="mb-3" style="display:none;">
                        <p class="text-muted small mb-1">Preview gambar baru:</p>
                        <img id="img-preview" style="width:120px;height:120px;object-fit:cover;border-radius:12px;border:2px solid #6c63ff;">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gradient-primary" id="btnSubmit"
                                onclick="submitForm('formMenu', 'btnSubmit')">
                            <i class="mdi mdi-content-save"></i>
                            {{ $menu ? 'Update Menu' : 'Simpan Menu' }}
                        </button>
                        <a href="{{ route('vendor.menu.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script>
    document.getElementById('path_gambar').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('img-preview').src = ev.target.result;
                document.getElementById('img-preview-wrap').style.display = '';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
