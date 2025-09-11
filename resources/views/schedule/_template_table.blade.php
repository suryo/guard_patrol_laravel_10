<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="fw-semibold">Template Schedule</span>
    <div>
      <button type="button" class="btn btn-sm btn-outline-secondary" title="Cari"
              data-bs-toggle="collapse" data-bs-target="#templateSearch">🔍</button>
      <button type="button" class="btn btn-sm btn-outline-primary" title="Tambah Template"
              data-bs-toggle="modal" data-bs-target="#modalAddTemplate">＋</button>
    </div>
  </div>

  <div id="templateSearch" class="collapse px-3 pt-3">
    <form method="get" class="row g-2">
      <div class="col-md-6">
        <input type="text" name="tq" class="form-control" placeholder="Cari template name"
               value="{{ request('tq') }}">
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-primary w-100">Filter</button>
      </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-sm align-middle mb-0">
      <thead class="table-light">
      <tr>
        <th style="width:40px"></th>
        <th>Template Name</th>
        <th class="text-end">Last Update</th>
      </tr>
      </thead>
      <tbody>
      @forelse($templates as $t)
        <tr>
          <td><input type="checkbox" class="form-check-input"></td>
          <td class="text-nowrap">
            <a href="{{ route('schedule-template.show', $t) }}" class="text-decoration-none">
              {{ $t->templateName }}
            </a>
          </td>
          <td class="text-end">
            <small>{{ \Illuminate\Support\Carbon::parse($t->lastUpdated)->format('d/m/Y (H:i)') }}</small>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="3" class="text-center text-muted">Belum ada template</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="card-footer py-2">
    {{ $templates->withQueryString()->links() }}
  </div>
</div>
