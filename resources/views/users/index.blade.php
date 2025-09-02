@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Users</h5>
  <a class="btn btn-primary" href="{{ route('users.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari userId / userName / email"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-2">
    <select name="level" class="form-select">
      <option value="">Semua Level</option>
      <option value="A" @selected(request('level')==='A')>Admin (A)</option>
      <option value="S" @selected(request('level')==='S')>Supervisor (S)</option>
      <option value="U" @selected(request('level')==='U')>User (U)</option>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(request()->filled('q') || request()->filled('level'))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('users.index') }}">Reset</a>
    </div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th><th>userId</th><th>userName</th><th>Level</th><th>Email</th><th style="width:150px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td><a href="{{ route('users.show',$i) }}">{{ $i->userId }}</a></td>
      <td>{{ $i->userName }}</td>
      <td>
        @php $map=['A'=>'Admin','S'=>'Supervisor','U'=>'User']; @endphp
        <span class="badge text-bg-{{ $i->userLevel==='A'?'primary':($i->userLevel==='S'?'warning':'secondary') }}">
          {{ $map[$i->userLevel] ?? $i->userLevel }}
        </span>
      </td>
      <td class="text-truncate" style="max-width:240px">{{ $i->userEmail }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('users.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('users.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</button>
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
