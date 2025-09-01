@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Person</h5>
  <a class="btn btn-primary" href="{{ route('person.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-2">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari personId / personName"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Cari</button>
  </div>
  @if(request()->has('q') && request('q')!=='')
  <div class="col-md-2">
    <a href="{{ route('person.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
  </div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>UID</th><th>personId</th><th>personName</th><th>userName</th><th>isDeleted</th><th style="width:140px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td>{{ $i->personId }}</td>
      <td><a href="{{ route('person.show',$i) }}">{{ $i->personName }}</a></td>
      <td>{{ $i->userName }}</td>
      <td>{{ $i->isDeleted }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('person.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('person.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
        </form>
      </td>
    </tr>
    @empty
      <tr><td colspan="6" class="text-center text-muted">Belum ada data</td></tr>
    @endforelse
  </tbody>
</table>

{{ $items->withQueryString()->links() }}
@endsection
