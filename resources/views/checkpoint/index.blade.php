@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Checkpoint</h5>
  <a class="btn btn-primary" href="{{ route('checkpoint.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-2">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari ID / Nama / Alamat"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-2"><button class="btn btn-outline-primary w-100">Cari</button></div>
  @if(request()->filled('q'))
    <div class="col-md-2"><a class="btn btn-outline-secondary w-100" href="{{ route('checkpoint.index') }}">Reset</a></div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>UID</th><th>ID</th><th>Nama</th><th>Lat</th><th>Lng</th><th>Alamat</th><th style="width:150px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td>{{ $i->checkpointId }}</td>
      <td><a href="{{ route('checkpoint.show',$i) }}">{{ $i->checkpointName }}</a></td>
      <td>{{ $i->latitude }}</td>
      <td>{{ $i->longitude }}</td>
      <td class="text-truncate" style="max-width:280px">{{ $i->address }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('checkpoint.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('checkpoint.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="7" class="text-center text-muted">Belum ada data</td></tr>
    @endforelse
  </tbody>
</table>

{{ $items->withQueryString()->links() }}
@endsection
