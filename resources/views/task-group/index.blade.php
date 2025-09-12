@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Task Group</h5>
  <a class="btn btn-primary" href="{{ route('task-group.create') }}">Tambah</a>
</div>

@include('components.alert')

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari groupId / groupName / userName"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(request()->filled('q'))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('task-group.index') }}">Reset</a>
    </div>
  @endif
</form>

<div class="table-responsive">
  <table class="table table-sm align-middle">
    <thead>
      <tr>
        <th style="width:90px">UID</th>
        <th>Group ID</th>
        <th>Group Name</th>
        <th>Tasks</th>
        <th>User</th>
        <th>Updated</th>
        <th style="width:130px"></th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
        <tr>
          <td class="text-muted">{{ $it->uid }}</td>
          <td class="fw-semibold">{{ $it->groupId }}</td>
          <td>{{ $it->groupName }}</td>
          <td>
            <span class="badge text-bg-secondary">
              {{ $it->details()->count() }} tasks
            </span>
          </td>
          <td>{{ $it->userName }}</td>
          <td><small class="text-muted">{{ \Illuminate\Support\Carbon::parse($it->lastUpdated)->format('Y-m-d H:i:s') }}</small></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('task-group.show', $it->uid) }}">Lihat</a>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('task-group.edit', $it->uid) }}">Edit</a>
            <form action="{{ route('task-group.destroy', $it->uid) }}" method="post" class="d-inline"
                  onsubmit="return confirm('Hapus task group ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div>
  {{ $items->links() }}
</div>
@endsection
