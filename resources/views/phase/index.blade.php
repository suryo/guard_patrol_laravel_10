@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Phase</h5>
  <a class="btn btn-primary" href="{{ route('phase.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari phaseId"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-3">
    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(request()->filled('q') || request()->filled('date'))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('phase.index') }}">Reset</a>
    </div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th><th>phaseId</th><th>phaseDate</th><th style="width:150px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td><a href="{{ route('phase.show',$i) }}">{{ $i->phaseId }}</a></td>
      <td>{{ $i->phaseDate }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('phase.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('phase.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
        </form>
      </td>
    </tr>
    @empty
      <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
    @endforelse
  </tbody>
</table>

{{ $items->withQueryString()->links() }}
@endsection
