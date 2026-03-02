@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Barang</h4>

        <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">
            Tambah Barang
        </a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ALERT KALAU LUPA CENTANG CHECKBOX --}}
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM CETAK (Berdiri Sendiri, Langsung Ditutup) --}}
        <form id="formCetak" action="{{ route('barang.cetak') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-2">
                    <label>X (Kolom)</label>
                    <input type="number" name="start_x" min="1" max="5" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label>Y (Baris)</label>
                    <input type="number" name="start_y" min="1" max="8" class="form-control" required>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success">
                        Cetak Label
                    </button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($barang as $key => $item)
                <tr>
                    <td>
                        {{-- Atribut form="formCetak" ini mengaitkan checkbox ke form di atas walau posisinya di luar form --}}
                        <input type="checkbox" name="barang_ids[]" value="{{ $item->id_barang }}" form="formCetak">
                    </td>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>Rp {{ number_format($item->harga) }}</td>
                    <td>
                        <a href="{{ route('barang.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>

                        {{-- FORM DELETE AMAN (KARENA TIDAK DI DALAM FORM CETAK LAGI) --}}
                        <form action="{{ route('barang.destroy', $item) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection