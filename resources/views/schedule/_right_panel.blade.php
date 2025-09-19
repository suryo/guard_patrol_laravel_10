{{-- resources/views/schedule/_right_panel.blade.php --}}
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
  {{-- Header kecil info tanggal + tombol edit assignment --}}
  <div class="d-flex justify-content-between align-items-center mb-2">
    <div>
      <strong>Group Waktu</strong>
      — {{ \Illuminate\Support\Carbon::parse($date)->isoFormat('D MMMM YYYY') }}
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="openAssignModal('{{ $date }}')">Edit</button>
  </div>

  {{-- Wrapper kartu-kartu group (diselaraskan dengan #groupsWrap di index.blade) --}}
  @foreach ($groups as $hour => $list)
    @php
      $label     = sprintf('%02d:00 - %02d:59', $hour, $hour);
      $gid       = "grp{$hour}";
      // Hitung total item (baris schedule_group) di jam ini
      $totalItem = $list->count();
    @endphp

    <div class="group-card">
      {{-- HEAD: judul group + meta jam + badge --}}
      <div class="group-head">
        <div>
          <div class="h6 mb-0">Group [{{ $label }}]</div>
          <div class="group-meta">Jam {{ $hour }} • {{ $totalItem }} item</div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge badge-soft">Group</span>
          {{-- Toggle open/close body --}}
          <button class="btn btn-sm btn-outline-secondary"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapse-{{ $gid }}"
                  aria-expanded="false"
                  aria-controls="collapse-{{ $gid }}">
            Toggle
          </button>
        </div>
      </div>

      {{-- BODY: berisi collapse agar autoload phases.js tetap bekerja (bindAccordionAutoload) --}}
      <div class="group-body">
        <div id="collapse-{{ $gid }}" class="accordion-collapse collapse">
          <div class="accordion-body p-0">
            @foreach ($list as $i)
              {{--
                Catatan:
                - sg_uid = UID pivot tb_schedule_group
                - data-date dipakai endpoint loader
                - data-loaded=0 agar JS load saat collapse dibuka
              --}}
              <div class="phase-box border rounded p-2 mb-2"
                   id="group-content-{{ $i->sg_uid }}"
                   data-group="{{ $i->sg_uid }}"
                   data-date="{{ $date }}"
                   data-loaded="0">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <div class="small text-muted">
                    {{ $i->groupName }} • {{ $i->timeStart }} - {{ $i->timeEnd }}
                  </div>
                </div>
                <div class="text-muted small">Memuat phase…</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endif
