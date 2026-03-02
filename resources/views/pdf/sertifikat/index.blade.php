@extends('layouts.app')

@section('title', 'List Sertifikat')

@section('content')

<div class="row">
<div class="col-lg-12 grid-margin stretch-card">
<div class="card">
<div class="card-body">

<h4 class="card-title">List Sertifikat</h4>

<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
    <th>Nama</th>
    <th>Email</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

@foreach($sertifikat as $s)
<tr>
    <td>{{ $s->nama }}</td>
    <td>{{ $s->email }}</td>
    <td>

        <a href="{{ route('pdf.sertifikat.preview', $s->id) }}"
           class="btn btn-info btn-sm"
           target="_blank">
           Preview
        </a>

        <a href="{{ route('pdf.sertifikat.download', $s->id) }}"
           class="btn btn-success btn-sm">
           Download
        </a>

    </td>
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