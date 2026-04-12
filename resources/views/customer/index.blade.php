@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Data Customer</h4>

        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('customer.create1') }}" class="btn btn-primary me-2">
                    <i class="mdi mdi-camera"></i> Tambah Customer 1 (BLOB)
                </a>
                <a href="{{ route('customer.create2') }}" class="btn btn-info">
                    <i class="mdi mdi-camera"></i> Tambah Customer 2 (File)
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Provinsi</th>
                        <th>Kota</th>
                        <th>Kecamatan</th>
                        <th>Kodepos</th>
                        <th>Penyimpanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $key => $customer)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            @if($customer->foto_blob || $customer->foto_path)
                                <img src="{{ route('customer.foto', $customer->id) }}" alt="Foto" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Foto</span>
                            @endif
                        </td>
                        <td>{{ $customer->nama }}</td>
                        <td>{{ $customer->alamat }}</td>
                        <td>{{ $customer->provinsi }}</td>
                        <td>{{ $customer->kota }}</td>
                        <td>{{ $customer->kecamatan }}</td>
                        <td>{{ $customer->kodepos }}</td>
                        <td>
                            @if($customer->foto_blob)
                                <span class="badge bg-primary">BLOB</span>
                            @elseif($customer->foto_path)
                                <span class="badge bg-success">File</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="mdi mdi-account-off" style="font-size: 2rem;"></i><br>
                            Belum ada data customer
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
