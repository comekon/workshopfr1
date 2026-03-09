@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Tambah Buku</h4>

                <form action="{{ route('buku.store') }}" method="POST" id="formCreateBuku">
                    @csrf

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="idkategori" id="idkategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->idkategori }}">
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label>Kode</label>
                        <input type="text"
                               name="kode" id="kode"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group mt-3">
                        <label>Judul</label>
                        <input type="text"
                               name="judul" id="judul"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group mt-3">
                        <label>Pengarang</label>
                        <input type="text"
                               name="pengarang" id="pengarang"
                               class="form-control"
                               required>
                    </div>

                </form>

                <button type="button"
                        id="btnSubmitBukuAdd"
                        onclick="submitForm('formCreateBuku', 'btnSubmitBukuAdd')"
                        class="btn btn-gradient-primary mt-3">
                    Simpan
                </button>

            </div>
        </div>
    </div>
</div>

@endsection
