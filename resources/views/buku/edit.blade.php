@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Edit Buku</h4>

                <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- KATEGORI --}}
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="idkategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->idkategori }}"
                                    {{ $k->idkategori == $buku->idkategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- KODE --}}
                    <div class="form-group mt-3">
                        <label>Kode</label>
                        <input type="text"
                               name="kode"
                               class="form-control"
                               value="{{ $buku->kode }}"
                               required>
                    </div>

                    {{-- JUDUL --}}
                    <div class="form-group mt-3">
                        <label>Judul</label>
                        <input type="text"
                               name="judul"
                               class="form-control"
                               value="{{ $buku->judul }}"
                               required>
                    </div>

                    {{-- PENGARANG --}}
                    <div class="form-group mt-3">
                        <label>Pengarang</label>
                        <input type="text"
                               name="pengarang"
                               class="form-control"
                               value="{{ $buku->pengarang }}"
                               required>
                    </div>

                    <button type="submit"
                            class="btn btn-gradient-primary mt-3">
                        Update
                    </button>

                    <a href="{{ route('buku.index') }}"
                       class="btn btn-light mt-3">
                        Kembali
                    </a>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
