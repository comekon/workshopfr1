@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Tambah Kategori</h4>

                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text"
                               name="nama_kategori"
                               class="form-control"
                               required>
                    </div>

                    <button type="submit"
                            class="btn btn-gradient-primary mt-3">
                        Simpan
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
