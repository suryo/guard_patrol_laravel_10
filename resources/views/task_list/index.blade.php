@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Task List</h5>
  <a class="btn btn-primary" href="{{ route('task-list.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input name="q" class="form-control" placeholder="Cari taskId / scheduleId / phaseId"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-2">
    <select name="s" class="form-select">
      <option value="">Semua Status</option>
      <option value="0" @selected(request('s')==='0')>Pending (0)</option>
      <option value="1" @selected(request('s')==='1')>Selesai (1)</option>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(request()->filled('q') || request()->filled('s'))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('task-list.index') }}">Reset</a>
    </div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th><th>taskId</th><th>scheduleId</th><th>phaseId</th>
      <th>Status</th><th>userName</th><th style="width:150px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td><a href="{{ route('task-list.show',$i) }}">{{ $i->taskId }}</a></td>
      <td>{{ $i->scheduleId }}</td>
      <td>{{ $i->phaseId }}</td>
      <td>
        @php
          $badge = $i->taskStatus==='1' ? 'success' : 'secondary';
          $label = $i->taskStatus==='1' ? 'Selesai' : 'Pending';
        @endphp
        <span class="badge text-bg-{{ $badge }}">{{ $label }}</span>
      </td>
      <td>{{ $i->userName }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('task-list.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('task-list.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus item ini?')">Hapus</button>
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
