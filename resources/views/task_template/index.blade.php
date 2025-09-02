@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Task Template</h5>
  <a class="btn btn-primary" href="{{ route('task-template.create') }}">Tambah</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input name="q" class="form-control" placeholder="Cari taskId / taskName"
           value="{{ request('q') }}">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if(request()->filled('q'))
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('task-template.index') }}">Reset</a>
    </div>
  @endif
</form>

<table class="table table-sm table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th><th>taskId</th><th>taskName</th><th>taskNote</th><th style="width:150px">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($items as $i)
    <tr>
      <td>{{ $i->uid }}</td>
      <td><a href="{{ route('task-template.show',$i) }}">{{ $i->taskId }}</a></td>
      <td class="text-truncate" style="max-width:220px">{{ $i->taskName }}</td>
      <td class="text-truncate" style="max-width:280px">{{ $i->taskNote }}</td>
      <td class="text-nowrap">
        <a class="btn btn-sm btn-warning" href="{{ route('task-template.edit',$i) }}">Edit</a>
        <form class="d-inline" method="post" action="{{ route('task-template.destroy',$i) }}">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus template ini?')">Hapus</button>
        </form>
      </td>
    </tr>
    @empty
      <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
    @endforelse
  </tbody>
</table>

{{ $items->withQueryString()->links() }}
@endsection
