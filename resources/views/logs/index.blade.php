@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">Logs List</h6>

    {{-- tombol filter --}}
    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel" aria-expanded="{{ request()->filled('q') || request()->filled('user') || request()->filled('date') ? 'true' : 'false' }}">
      <i class="bi bi-funnel"></i>
    </button>
  </div>

  {{-- panel filter --}}
  <div class="collapse {{ request()->filled('q') || request()->filled('user') || request()->filled('date') ? 'show' : '' }}" id="filterPanel">
    <div class="card-body border-bottom">
      <form id="filterForm" class="row g-2">
        <div class="col-md-4">
          <input name="q" class="form-control" placeholder="Cari activity / category / note" value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
          <input name="user" class="form-control" placeholder="userName" value="{{ request('user') }}">
        </div>
        <div class="col-md-3">
          <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-primary">Filter</button>
        </div>
        @if(request()->filled('q') || request()->filled('user') || request()->filled('date'))
          <div class="col-md-2 d-grid">
            <a class="btn btn-outline-secondary" href="{{ route('logs.index') }}">Reset</a>
          </div>
        @endif
      </form>
    </div>
  </div>

  {{-- tabel --}}
  <div class="table-responsive">
    <table class="table table-sm table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:70px">No</th>
          <th>Activity</th>
          <th>Category</th>
          <th>User</th>
          <th>Note</th>
          <th style="width:180px">Date &amp; Time</th>
          <th style="width:150px" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $i)
          <tr>
            <td>{{ $items->firstItem() + $loop->index }}</td>
            <td><a href="{{ route('logs.show', $i) }}" class="text-decoration-none">{{ $i->activity }}</a></td>
            <td>{{ $i->category }}</td>
            <td>{{ $i->userName }}</td>
            <td class="text-truncate" style="max-width:360px">{{ $i->note }}</td>
            <td>{{ \Carbon\Carbon::parse($i->lastUpdated)->format('Y-m-d H:i:s') }}</td>
            <td class="text-center text-nowrap">
              <a class="btn btn-sm btn-warning" href="{{ route('logs.edit',$i) }}">Edit</a>
              <form class="d-inline" method="post" action="{{ route('logs.destroy',$i) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus log ini?')">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No Data!</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($items->hasPages())
    <div class="card-footer">
      {{ $items->withQueryString()->links() }}
    </div>
  @endif
</div>

{{-- overlay loader --}}
<div id="loaderOverlay" class="gp-overlay d-none">
  <div class="gp-box">
    <div class="spinner-border" role="status" aria-hidden="true"></div>
    <div class="mt-2 fw-medium">Getting Data...</div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .gp-overlay{
    position: fixed; inset: 0; background: rgba(0,0,0,.35);
    display:flex; align-items:center; justify-content:center; z-index: 1055;
  }
  .gp-box{
    background:#fff; padding:24px 28px; border-radius:.5rem;
    box-shadow:0 10px 35px rgba(0,0,0,.25); text-align:center;
    min-width: 240px;
  }
</style>
@endpush

@push('scripts')
<script>
  const overlay = document.getElementById('loaderOverlay');
  function showLoader(){ overlay.classList.remove('d-none'); }
  function bindLoader(){
    // form filter
    const f = document.getElementById('filterForm');
    if(f){ f.addEventListener('submit', showLoader); }
    // pagination
    document.querySelectorAll('.pagination a').forEach(a=>{
      a.addEventListener('click', ()=>{ showLoader(); });
    });
  }
  document.addEventListener('DOMContentLoaded', bindLoader);
</script>
@endpush
