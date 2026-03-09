@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Edit Kategori</h4>

                <form action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST" id="formEditKategori">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text"
                               name="nama_kategori"
                               id="nama_kategori"
                               class="form-control"
                               value="{{ $kategori->nama_kategori }}"
                               required>
                    </div>

                </form>

                <button type="button"
                        id="btnSubmitKategoriEdit"
                        onclick="submitForm('formEditKategori', 'btnSubmitKategoriEdit')"
                        class="btn btn-gradient-primary mt-3">
                    Update
                </button>

            </div>
        </div>
    </div>
</div>

@endsection
