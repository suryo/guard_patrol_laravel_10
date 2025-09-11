@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Groups</h5>
  <a class="btn btn-primary" href="{{ route('group.create') }}">Tambah</a>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari groupId / groupName / note"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(request()->filled('q'))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('group.index') }}">Reset</a>
    </div>
  @endif
</form>

<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th style="width: 120px">Group ID</th>
        <th>Group Name</th>
        <th style="width: 160px">Time</th>
        <th>Note</th>
        <th style="width: 170px">Last Updated</th>
        <th style="width: 160px">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
        <tr>
          <td class="text-monospace">{{ $it->groupId }}</td>
          <td>{{ $it->groupName }}</td>
          <td>
            @php
              $ts = $it->timeStart ? \Illuminate\Support\Str::of($it->timeStart)->limit(5,'') : null;
              $te = $it->timeEnd ? \Illuminate\Support\Str::of($it->timeEnd)->limit(5,'') : null;
            @endphp
            {{ $ts ? $ts : '-' }} - {{ $te ? $te : '-' }}
          </td>
          <td>{{ \Illuminate\Support\Str::limit($it->note, 60) }}</td>
          <td>{{ $it->lastUpdated }}</td>
          <td>
            <div class="btn-group btn-group-sm" role="group">
              <a class="btn btn-outline-secondary" href="{{ route('group.show', $it->uid) }}">Detail</a>
              <a class="btn btn-outline-primary" href="{{ route('group.edit', $it->uid) }}">Edit</a>
              <form method="POST" action="{{ route('group.destroy', $it->uid) }}"
                    onsubmit="return confirm('Hapus data ini?')" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center text-muted">Tidak ada data</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $items->links() }}
@endsection
