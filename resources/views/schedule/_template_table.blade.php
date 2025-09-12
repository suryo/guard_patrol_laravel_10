<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="fw-semibold">Template Schedule</span>

    <div class="d-flex gap-2 align-items-center">
      <button type="button" class="btn btn-sm btn-outline-secondary" title="Cari"
              data-bs-toggle="collapse" data-bs-target="#templateSearch">🔍</button>

      {{-- 🔴 Tombol DELETE (disembunyikan saat belum ada yang dicentang) --}}
      <button type="button" id="btnTplDelete"
              class="btn btn-sm btn-outline-danger d-none" title="Hapus terpilih">
        🗑
      </button>

      <button type="button" class="btn btn-sm btn-outline-primary" title="Tambah Template"
              data-bs-toggle="modal" data-bs-target="#modalAddTemplate">＋</button>
    </div>
  </div>

  {{-- Form untuk bulk delete (disubmit via JS) --}}
  <form id="tplBulkDeleteForm" method="post"
        action="{{ route('schedule-template.bulk-destroy') }}"
        class="d-none">
    @csrf
    @method('DELETE')
    {{-- input ids[] akan diinject via JS --}}
  </form>

  {{-- ... sisanya tetap ... --}}
  <div class="table-responsive">
    <table class="table table-sm align-middle mb-0">
      <thead class="table-light">
      <tr>
        <th style="width:40px">
          <input type="checkbox" class="form-check-input" id="tpl-check-all">
        </th>
        <th>Template Name</th>
        <th class="text-end">Last Update</th>
      </tr>
      </thead>
      <tbody>
      @forelse($templates as $t)
        <tr>
          <td>
            <input type="checkbox" class="form-check-input tpl-check" value="{{ $t->uid }}">
          </td>
          <td class="text-nowrap">
            <a href="javascript:void(0)" class="text-decoration-none js-open-template"
               data-id="{{ $t->uid }}">
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
