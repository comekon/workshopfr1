@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="row">

    
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <h4>Total Kategori</h4>
                <h2>{{ $totalKategori }}</h2>
            </div>
        </div>
    </div>

    
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <h4>Total Buku</h4>
                <h2>{{ $totalBuku }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Buku Terbaru</h4>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bukuTerbaru as $b)
                            <tr>
                                <td>{{ $b->kategori->nama_kategori }}</td>
                                <td>{{ $b->kode }}</td>
                                <td>{{ $b->judul }}</td>
                                <td>{{ $b->pengarang }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
