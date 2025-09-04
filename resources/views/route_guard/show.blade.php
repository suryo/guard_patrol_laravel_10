@extends('layouts.app')

@section('content')
<h5 class="mb-3">Route Guard • Person</h5>

<div class="card mb-3">
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <div><strong>Nama</strong><br>{{ $person->personName }}</div>
        <div class="mt-2"><strong>Person ID</strong><br>{{ $person->personId }}</div>
      </div>
      <div class="col-md-6">
        <div><strong>User</strong><br>{{ $person->userName }}</div>
        <div class="mt-2"><strong>Last Updated</strong><br>{{ \Illuminate\Support\Carbon::parse($person->lastUpdated)->format('Y-m-d H:i:s') }}</div>
      </div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
  <h6 class="mb-0">Daftar Mapping (referensi)</h6>
  <a class="btn btn-secondary btn-sm" href="{{ route('route-guard.index') }}">Kembali</a>
</div>

<form class="row g-2 mb-2">
  <div class="col-md-4">
    <input name="qMapping" class="form-control" placeholder="Cari Tag / Nama / ID Mapping"
           value="{{ $qMapping }}">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Cari</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-sm table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th width="60">#</th>
        <th>Mapping</th>
        <th>User</th>
        <th width="130">Aksi</th>
      </tr>
    </thead>
    <tbody>
    @forelse($mappings as $i => $m)
      <tr>
        <td>{{ $mappings->firstItem() + $i }}</td>
        <td>
          <div class="fw-semibold">{{ $m->mappingTag }} — {{ $m->mappingName }}</div>
          <div class="text-muted small">{{ $m->mappingId }}</div>
        </td>
        <td>{{ $m->userName }}</td>
        <td class="d-flex gap-1">
          <a href="{{ route('person-mapping.show', $m->uid) }}" class="btn btn-sm btn-info">Detail</a>
          <a href="{{ route('person-mapping.edit', $m->uid) }}" class="btn btn-sm btn-warning">Edit</a>
        </td>
      </tr>
    @empty
      <tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>

{{ $mappings->links() }}
@endsection
