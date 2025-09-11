@if (!$date)
  <div class="text-muted small py-3">
    Pilih tanggal pada kalender untuk melihat / mengatur Group Waktu.
  </div>
@elseif(!$hasGroup)
  <div class="alert alert-info d-flex justify-content-between align-items-center">
    <span>Belum ada group untuk tanggal {{ \Illuminate\Support\Carbon::parse($date)->format('d/m/Y') }}.</span>
    <button type="button" class="btn btn-sm btn-primary" onclick="openAssignModal('{{ $date }}')">Set Group</button>
  </div>
@else
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div><strong>Group Waktu</strong> — {{ \Illuminate\Support\Carbon::parse($date)->isoFormat('D MMMM YYYY') }}</div>
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="openAssignModal('{{ $date }}')">Edit</button>
  </div>

  <div class="accordion" id="groupAccordion">
    @foreach ($groups as $hour => $list)
      @php
        $label = sprintf('%02d:00 - %02d:59', $hour, $hour);
        $cid   = "grp{$hour}";
      @endphp
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading-{{ $cid }}">
          <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapse-{{ $cid }}">
            <div class="d-flex justify-content-between w-100">
              <span>Group [{{ $label }}]</span>
              <span class="badge bg-secondary">{{ $list->count() }}</span>
            </div>
          </button>
        </h2>

        <div id="collapse-{{ $cid }}" class="accordion-collapse collapse" data-bs-parent="#groupAccordion">
          <div class="accordion-body p-2">
            @foreach ($list as $i)
              <div class="phase-box border rounded p-2 mb-2"
                   id="group-content-{{ $i->sg_uid }}"
                   data-group="{{ $i->sg_uid }}"
                   data-date="{{ $date }}"
                   data-loaded="0">
                <div class="text-muted small">Memuat phase…</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif
