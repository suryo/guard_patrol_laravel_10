@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Activity</h5>
  <a class="btn btn-primary" href="{{ route('activity.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-2">
  <div class="col-md-3">
    <input name="q" class="form-control" placeholder="Cari activityId / personId / scheduleId"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-3">
    <input type="date" name="d" class="form-control" value="{{ request('d') }}" placeholder="Tanggal (activityStart)">
  </div>
  <div class="col-md-2">
    <select name="s" class="form-select">
      <option value="">Semua Status</option>
      <option value="0" @selected(request('s')==='0')>Planned (0)</option>
      <option value="1" @selected(request('s')==='1')>Done (1)</option>
      <option value="2" @selected(request('s')==='2')>Miss (2)</option>
    </select>
  </div>
  <div class="col-md-2"><button class="btn btn-outline-primary w-100">Filter</button></div>
  @if(request()->filled('q') || request()->filled('d') || request()->filled('s'))
    <div class="col-md-2"><a class="btn btn-outline-secondary w-100" href="{{ route('activity.index') }}">Reset</a></div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th><th>activityId</th><th>personId</th><th>scheduleId</th>
      <th>Start</th><th>End</th><th>Status</th><th style="width:150px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td><a href="{{ route('activity.show',$i) }}">{{ $i->activityId }}</a></td>
      <td>{{ $i->personId }}</td>
      <td>{{ $i->scheduleId }}</td>
      <td>{{ $i->activityStart }}</td>
      <td>{{ $i->activityEnd }}</td>
      <td>
        @php
          $badge = ['0'=>'secondary','1'=>'success','2'=>'danger'][$i->activityStatus] ?? 'secondary';
          $label = ['0'=>'Planned','1'=>'Done','2'=>'Miss'][$i->activityStatus] ?? $i->activityStatus;
        @endphp
        <span class="badge text-bg-{{ $badge }}">{{ $label }}</span>
      </td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('activity.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('activity.destroy',$i) }}">
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
