@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Schedule</h5>
  <a class="btn btn-primary" href="{{ route('schedule.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-2">
  <div class="col-md-3">
    <input name="q" class="form-control" placeholder="Cari scheduleId / personId / checkpoint"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-3">
    <input type="date" name="d" class="form-control" value="{{ request('d') }}" placeholder="scheduleDate">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(request()->filled('q') || request()->filled('d'))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('schedule.index') }}">Reset</a>
    </div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th><th>scheduleId</th><th>personId</th><th>checkpoint</th>
      <th>scheduleDate</th><th>Start</th><th>End</th><th style="width:150px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td><a href="{{ route('schedule.show',$i) }}">{{ $i->scheduleId }}</a></td>
      <td>{{ $i->personId }}</td>
      <td class="text-truncate" style="max-width:220px">{{ $i->checkpointName }}</td>
      <td>{{ $i->scheduleDate }}</td>
      <td>{{ $i->scheduleStart }}</td>
      <td>{{ $i->scheduleEnd }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('schedule.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('schedule.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
        </form>
      </td>
    </tr>
    @empty
      <tr><td colspan="8" class="text-center text-muted">Belum ada data</td></tr>
    @endforelse
  </tbody>
</table>

{{ $items->withQueryString()->links() }}
@endsection
