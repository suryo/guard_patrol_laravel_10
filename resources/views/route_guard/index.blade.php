@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Route Guard (Person & Mapping)</h5>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-primary" href="{{ route('person.index') }}">Kelola Person</a>
    <a class="btn btn-outline-secondary" href="{{ route('person-mapping.index') }}">Kelola Mapping</a>
  </div>
</div>

<div class="row g-3">
  {{-- KOLOM PERSON --}}
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <strong>Person</strong>
        <a href="{{ route('person.create') }}" class="btn btn-sm btn-primary">Tambah Person</a>
      </div>
      <div class="card-body">
        <form class="row g-2 mb-2">
          <div class="col-8">
            <input name="qPerson" class="form-control" placeholder="Cari ID / Nama / User"
                   value="{{ $qPerson }}">
          </div>
          <div class="col-4 d-grid">
            <button class="btn btn-outline-primary">Cari</button>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th width="50">#</th>
                <th>Person</th>
                <th>User</th>
                <th width="140">Aksi</th>
              </tr>
            </thead>
            <tbody>
            @forelse($people as $i => $p)
              <tr>
                <td>{{ $people->firstItem() + $i }}</td>
                <td>
                  <div class="fw-semibold">{{ $p->personName }}</div>
                  <div class="text-muted small">{{ $p->personId }}</div>
                </td>
                <td>{{ $p->userName }}</td>
                <td class="d-flex gap-1">
                  <a href="{{ route('person.show', $p->uid) }}" class="btn btn-sm btn-info">Detail</a>
                  <a href="{{ route('person.edit', $p->uid) }}" class="btn btn-sm btn-warning">Edit</a>
                  <a href="{{ route('route-guard.show', $p->personId) }}" class="btn btn-sm btn-outline-secondary">Lihat Mapping</a>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>

        {{ $people->links() }}
      </div>
    </div>
  </div>

  {{-- KOLOM MAPPING --}}
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <strong>Person Mapping</strong>
        <a href="{{ route('person-mapping.create') }}" class="btn btn-sm btn-primary">Tambah Mapping</a>
      </div>
      <div class="card-body">
        <form class="row g-2 mb-2">
          <div class="col-8">
            <input name="qMapping" class="form-control" placeholder="Cari ID / Tag / Nama / User"
                   value="{{ $qMapping }}">
          </div>
          <div class="col-4 d-grid">
            <button class="btn btn-outline-primary">Cari</button>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th width="50">#</th>
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
      </div>
    </div>
  </div>
</div>
@endsection
