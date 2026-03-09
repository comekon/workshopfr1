@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')

<div class="card">
    <div class="card-body">
        <h4>Tambah Barang</h4>

        <form action="{{ route('barang.store') }}" method="POST" id="formCreateBarang">
            @csrf

            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" id="harga" class="form-control" required>
            </div>

        </form>

        <button class="btn btn-success mt-3" type="button" id="btnSubmitCreate" onclick="submitForm('formCreateBarang', 'btnSubmitCreate')">Simpan</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>

@endsection