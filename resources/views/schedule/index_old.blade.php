{{-- resources/views/schedule/index.blade.php --}}
@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Carbon;
        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();
        $startWeek = $monthStart->copy()->startOfWeek(); // Senin
        $endWeek = $monthEnd->copy()->endOfWeek();
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Schedule</h5>
        <a class="btn btn-primary" href="{{ route('schedule.create') }}">Tambah</a>
    </div>

    <form class="row g-2 mb-3">
        <div class="col-md-3">
            <input name="q" class="form-control" placeholder="Cari scheduleId / personId / checkpoint"
                value="{{ $q }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="d" class="form-control" value="{{ $date }}"
                placeholder="scheduleDate">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100">Filter</button>
        </div>
        @if ($q || $date)
            <div class="col-md-2">
                <a class="btn btn-outline-secondary w-100" href="{{ route('schedule.index') }}">Reset</a>
            </div>
        @endif
    </form>

    <div class="row">
        {{-- KIRI: Kalender + Template Schedule --}}
        <div class="col-lg-8">

            {{-- Kalender bulanan --}}
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="fw-semibold">
                        {{ $month->isoFormat('MMMM, YYYY') }}
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-sm btn-outline-secondary"
                            href="{{ route('schedule.index', array_filter(['q' => $q, 'd' => $date, 'm' => $month->copy()->subMonth()->format('Y-m')])) }}">
                            ‹
                        </a>
                        <a class="btn btn-sm btn-outline-secondary"
                            href="{{ route('schedule.index', array_filter(['q' => $q, 'd' => $date, 'm' => $month->copy()->addMonth()->format('Y-m')])) }}">
                            ›
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                    <th>Sun</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($day = $startWeek->copy(); $day <= $endWeek; $day->addDay())
                                    @if ($day->isMonday())
                                        <tr>
                                    @endif
                                    @php
                                        $isOtherMonth = !$day->isSameMonth($monthStart);
                                        $isSelected = $day->toDateString() === $date;
                                        $linkParams = array_filter([
                                            'q' => $q,
                                            'd' => $day->toDateString(),
                                            'm' => $month->format('Y-m'),
                                        ]);
                                    @endphp
                                    <td class="{{ $isOtherMonth ? 'text-muted bg-light' : '' }} {{ $isSelected ? 'table-primary fw-semibold' : '' }}"
                                        style="height:68px; vertical-align:top;">
                                        <div class="d-flex justify-content-between">
                                            <small>{{ $day->day }}</small>
                                            @php
                                                $hasSched =
                                                    $schedules->where('scheduleDate', $day->toDateString())->count() >
                                                    0;
                                            @endphp
                                            @if ($hasSched && !$isSelected)
                                                <span class="badge bg-secondary">•</span>
                                            @endif
                                        </div>
                                        <div class="mt-2 position-relative">
                                            <button type="button"
                                                class="stretched-link btn p-0 border-0 bg-transparent assign-group-btn"
                                                data-date="{{ $day->toDateString() }}"></button>
                                        </div>
                                    </td>
                                    @if ($day->isSunday())
                                        </tr>
                                    @endif
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Template Schedule --}}
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

        </div>


        {{-- KANAN: Group Waktu dinamis --}}
        <div class="col-lg-4">
            @if (!$date)
                <div class="text-muted small py-3">
                    Pilih tanggal pada kalender untuk melihat / mengatur Group Waktu.
                </div>
            @elseif(!$hasGroup)
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <span>Belum ada group untuk tanggal
                        {{ \Illuminate\Support\Carbon::parse($date)->format('d/m/Y') }}.</span>
                    <button type="button" class="btn btn-sm btn-primary"
                        onclick="openAssignModal('{{ $date }}')">Set Group</button>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div><strong>Group Waktu</strong> —
                        {{ \Illuminate\Support\Carbon::parse($date)->isoFormat('D MMMM YYYY') }}</div>
                    <button type="button" class="btn btn-sm btn-outline-primary"
                        onclick="openAssignModal('{{ $date }}')">Edit</button>
                </div>

                <div class="accordion" id="groupAccordion">
                    @foreach ($groups as $hour => $list)
                        @php
                            $label = sprintf('%02d:00 - %02d:59', $hour, $hour);
                            $cid = "grp{$hour}";
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

                            <div id="collapse-{{ $cid }}" class="accordion-collapse collapse"
                                data-bs-parent="#groupAccordion">
                                <div class="accordion-body p-2">
                                    {{-- SETIAP GROUP YANG AKTIF PADA JAM INI PUNYA PHASE-BOX SENDIRI --}}
                                    @foreach ($list as $i)
                                        <div class="phase-box border rounded p-2 mb-2"
                                            id="group-content-{{ $i->sg_uid }}" data-group="{{ $i->sg_uid }}"
                                            data-date="{{ $date }}" data-loaded="0">
                                            <div class="text-muted small">Memuat phase…</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>


    </div>

    {{-- MODAL: Tambah Template Schedule --}}
    <div class="modal fade" id="modalAddTemplate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="post" action="{{ route('schedule-template.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Template Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Info error validasi (jika ada) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <div class="fw-semibold mb-1">Periksa kembali input Anda:</div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Template Name <span class="text-danger">*</span></label>
                                <input type="text" name="templateName" class="form-control" maxlength="150"
                                    value="{{ old('templateName') }}" placeholder="Mis. AGAM 01.00 BLKG" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Template ID (opsional)</label>
                                <input type="text" name="templateId" class="form-control"
                                    value="{{ old('templateId') }}" placeholder="(auto jika dikosongkan)">
                            </div>

                            <div class="col-12">
                                <label class="form-label d-flex align-items-center gap-2">
                                    Mapping IDs
                                    <small class="text-muted">(pisahkan dengan koma / baris baru)</small>
                                </label>
                                <textarea name="templateMapping" class="form-control" rows="2" placeholder="mappingId1, mappingId2, ...">{{ old('templateMapping') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Person (opsional)</label>
                                <textarea name="templatePerson" class="form-control" rows="2" placeholder="personId1, personId2, ...">{{ old('templatePerson') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Checkpoint (opsional)</label>
                                <textarea name="templateCheckpoint" class="form-control" rows="2"
                                    placeholder="checkpoint A, checkpoint B, ...">{{ old('templateCheckpoint') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Start Time(s)</label>
                                <textarea name="templateStart" class="form-control" rows="2" placeholder="01:00, 02:00, ...">{{ old('templateStart') }}</textarea>
                                <div class="form-text">Bisa banyak jam, pisahkan koma atau baris baru.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Time(s)</label>
                                <textarea name="templateEnd" class="form-control" rows="2" placeholder="01:59, 02:59, ...">{{ old('templateEnd') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Task (opsional)</label>
                                <textarea name="templateTask" class="form-control" rows="2" placeholder="Instruksi tugas/phase yang terkait">{{ old('templateTask') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Assign Group ke Tanggal --}}
    <div class="modal fade" id="modalAssignGroup" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Pilih Group Waktu <small class="text-muted" id="assign-date-label"></small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <form id="assign-group-form">
                    @csrf
                    <input type="hidden" name="date" id="assign-date">
                    <div class="modal-body">
                        <div class="mb-2">
                            <div class="form-text">Centang group yang ingin diaktifkan pada tanggal tersebut (urutan sesuai
                                centang).</div>
                        </div>

                        <div class="border rounded p-2" style="max-height: 50vh; overflow:auto;">
                            @forelse($allGroups as $g)
                                <div class="form-check py-1">
                                    <input class="form-check-input group-opt" type="checkbox"
                                        value="{{ $g->uid }}" id="g{{ $g->uid }}" name="group_uids[]">
                                    <label class="form-check-label d-flex justify-content-between"
                                        for="g{{ $g->uid }}">
                                        <span class="me-2">{{ $g->groupName }}</span>
                                        <small class="text-muted">{{ $g->timeStart }} - {{ $g->timeEnd }}</small>
                                    </label>
                                </div>
                            @empty
                                <div class="text-muted">Belum ada group. Buat dulu di menu Group.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Pilih / Edit Phase --}}
    <div class="modal fade" id="modalPickPhase" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formPickPhase" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="mp-title">Pilih Phase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="mp-group">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Phase</label>
                        <input type="date" id="mp-date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phase</label>
                        <select id="mp-phase" class="form-select" required>
                            <option value="">-- pilih --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                <input type="hidden" id="mp-mode" value="create">
                <input type="hidden" id="mp-link">
                <input type="hidden" id="mp-group">
            </form>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const routeList = (uid, date) => `{{ route('ajax.schedule-group.phases', ['uid' => 'UIDX']) }}`
                    .replace('UIDX', uid) + `?d=${encodeURIComponent(date)}`;
                const routeStore = (uid) => `{{ route('ajax.schedule-group.phases.store', ['uid' => 'UIDX']) }}`
                    .replace('UIDX', uid);
                const routeUpdate = (link) => `{{ route('ajax.schedule-group-phase.update', ['link' => 'LNK']) }}`
                    .replace('LNK', link);
                const routeDelete = (link) => `{{ route('ajax.schedule-group-phase.destroy', ['link' => 'LNK']) }}`
                    .replace('LNK', link);
                const routeOpts = `{{ route('ajax.phases.options') }}`;

                // util fetch json
                async function jget(url) {
                    const r = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!r.ok) throw new Error(await r.text());
                    return r.json();
                }
                async function jsend(url, method, body) {
                    const r = await fetch(url, {
                        method,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(body)
                    });
                    if (!r.ok) throw new Error(await r.text());
                    return r.json().catch(() => ({
                        ok: true
                    }));
                }

                // render 1 box (phase list utk 1 schedule_group)
                async function loadPhaseBox(box) {
                    const uid = box.dataset.group;
                    const date = box.dataset.date;
                    box.innerHTML = `<div class="text-muted small">Memuat phase…</div>`;
                    try {
                        const data = await jget(routeList(uid, date));
                        if (!data.phases || data.phases.length === 0) {
                            box.innerHTML =
                                `
          <div class="alert alert-warning py-2 mb-2">Phase belum ada.</div>
          <button class="btn btn-sm btn-primary add-phase" data-group="${uid}" data-date="${date}">Tambah Phase</button>`;
                            return;
                        }
                        let html = `<ul class="list-group">`;
                        data.phases.forEach(p => {
                            html += `
          <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold">${p.phaseName ?? '(phase)'} <small class="text-muted">(${p.phaseDate})</small></div>
                ${p.phaseId ? `<div class="small text-muted">${p.phaseId}</div>` : ''}
              </div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary edit-phase"
                        data-link="${p.link_uid}" data-group="${uid}" data-date="${p.phaseDate}" data-phase="${p.phase_uid}">
                  Edit
                </button>
                <button class="btn btn-outline-danger delete-phase" data-link="${p.link_uid}">Delete</button>
              </div>
            </div>
            <div id="phase-content-${p.link_uid}" class="mt-2 d-none"></div>
          </li>`;
                        });
                        html +=
                            `</ul>
        <button class="btn btn-sm btn-primary mt-2 add-phase" data-group="${uid}" data-date="${date}">Tambah Phase</button>`;
                        box.innerHTML = html;
                    } catch (e) {
                        console.error(e);
                        box.innerHTML = `<div class="text-danger small">Gagal memuat phase.</div>`;
                    }
                }

                // saat panel jam dibuka, muat semua .phase-box di dalamnya
                document.querySelectorAll('.accordion-collapse').forEach(col => {
                    col.addEventListener('shown.bs.collapse', () => {
                        col.querySelectorAll('.phase-box').forEach(loadPhaseBox);
                    });
                });

                // buka modal pilih phase (create/edit)
                async function openPickModal({
                    mode,
                    groupUid,
                    date,
                    linkUid = null,
                    selectedPhaseUid = null
                }) {
                    document.getElementById('mp-mode').value = mode;
                    document.getElementById('mp-group').value = groupUid;
                    document.getElementById('mp-link').value = linkUid || '';
                    document.getElementById('mp-date').value = date;
                    document.getElementById('mp-title').textContent = mode === 'edit' ? 'Edit Phase' :
                        'Pilih Phase';

                    // load options
                    const sel = document.getElementById('mp-phase');
                    sel.innerHTML = `<option value="">-- pilih --</option>`;
                    try {
                        const data = await jget(routeOpts);
                        (data.options || []).forEach(o => {
                            const opt = document.createElement('option');
                            opt.value = o.uid;
                            opt.textContent = (o.phaseOrder ? o.phaseOrder + '. ' : '') + (o.phaseName || o
                                .phaseId || ('#' + o.uid));
                            if (selectedPhaseUid && +selectedPhaseUid === +o.uid) opt.selected = true;
                            sel.appendChild(opt);
                        });
                    } catch (e) {
                        console.error(e);
                        alert('Gagal memuat opsi phase.');
                        return;
                    }

                    new bootstrap.Modal(document.getElementById('modalPickPhase')).show();
                }

                // delegated clicks
                // document.addEventListener('click', async (e) => {
                //     // Tambah → open modal
                //     if (e.target.matches('.add-phase')) {
                //         e.preventDefault();
                //         await openPickModal({
                //             mode: 'create',
                //             groupUid: e.target.dataset.group,
                //             date: e.target.dataset.date
                //         });
                //     }
                //     // Edit → open modal
                //     if (e.target.matches('.edit-phase')) {
                //         e.preventDefault();
                //         await openPickModal({
                //             mode: 'edit',
                //             groupUid: e.target.dataset.group,
                //             date: e.target.dataset.date,
                //             linkUid: e.target.dataset.link,
                //             selectedPhaseUid: e.target.dataset.phase
                //         });
                //     }
                //     // Delete link
                //     if (e.target.matches('.delete-phase')) {
                //         e.preventDefault();
                //         if (!confirm('Hapus phase ini dari group?')) return;
                //         const link = e.target.dataset.link;
                //         try {
                //             await jsend(routeDelete(link), 'DELETE', {});
                //             // reload box
                //             const box = e.target.closest('.accordion-body').querySelector('.phase-box');
                //             await loadPhaseBox(box);
                //         } catch (err) {
                //             console.error(err);
                //             alert('Gagal menghapus.');
                //         }
                //     }
                // });

                // submit modal (create/edit)
                document.getElementById('formPickPhase').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const mode = document.getElementById('mp-mode').value;
                    const groupUid = document.getElementById('mp-group').value;
                    const linkUid = document.getElementById('mp-link').value;
                    const phaseUid = document.getElementById('mp-phase').value;
                    const date = document.getElementById('mp-date').value;
                    if (!phaseUid || !date) {
                        alert('Lengkapi pilihan phase & tanggal.');
                        return;
                    }

                    try {
                        if (mode === 'create') {
                            await jsend(routeStore(groupUid), 'POST', {
                                phase_uid: +phaseUid,
                                phaseDate: date
                            });
                        } else {
                            await jsend(routeUpdate(linkUid), 'PUT', {
                                phase_uid: +phaseUid,
                                phaseDate: date
                            });
                        }
                        bootstrap.Modal.getInstance(document.getElementById('modalPickPhase')).hide();

                        // reload box
                        const box = document.getElementById('group-content-' + groupUid);
                        await loadPhaseBox(box);
                    } catch (err) {
                        console.error(err);
                        alert('Gagal menyimpan phase.');
                    }
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const phaseIndexRoute = (uid, date) =>
                    `{{ route('ajax.schedule-group.phases', ['uid' => 'UIDX']) }}`.replace('UIDX', uid) +
                    `?d=${encodeURIComponent(date)}`;
                const phaseStoreRoute = (uid) =>
                    `{{ route('ajax.schedule-group.phases.store', ['uid' => 'UIDX']) }}`.replace('UIDX', uid);
                const phaseDestroyRoute = (phaseUid) =>
                    `{{ route('ajax.phase.destroy', ['phase' => 'PIDX']) }}`.replace('PIDX', phaseUid);

                // Render daftar phase untuk satu group
                function renderPhases(container, phases, groupUid, date) {
                    if (!phases || phases.length === 0) {
                        container.innerHTML = `
        <div class="alert alert-warning py-2 mb-2">Phase belum ada.</div>
        <button class="btn btn-sm btn-primary add-phase" data-group="${groupUid}" data-date="${date}">Tambah Phase</button>
      `;
                        return;
                    }
                    let html = `<ul class="list-group">`;
                    phases.forEach(p => {
                        html += `
        <li class="list-group-item">
          <div class="d-flex justify-content-between align-items-center">
            <a href="#" class="phase-toggle" data-phase="${p.uid}">
              ${p.phaseName ?? ('Phase ' + (p.phaseOrder || ''))}
              <span class="text-muted">(${p.phaseDate})</span>
            </a>
            <button class="btn btn-sm btn-outline-danger delete-phase" data-phase="${p.uid}">Hapus</button>
          </div>
          <div id="phase-content-${p.uid}" class="mt-2 d-none"></div>
        </li>`;
                    });
                    html +=
                        `</ul>
      <button class="btn btn-sm btn-primary mt-2 add-phase" data-group="${groupUid}" data-date="${date}">Tambah Phase</button>`;
                    container.innerHTML = html;
                }

                // Saat panel jam dibuka → load semua phase-box di dalamnya (sekali saja)
                document.querySelectorAll('.accordion-collapse').forEach(col => {
                    col.addEventListener('shown.bs.collapse', async () => {
                        const boxes = col.querySelectorAll('.phase-box[data-loaded="0"]');
                        for (const box of boxes) {
                            const uid = box.dataset.group;
                            const date = box.dataset.date;
                            try {
                                const res = await fetch(phaseIndexRoute(uid, date), {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });
                                const json = await res.json();
                                renderPhases(box, json.phases, uid, date);
                                box.dataset.loaded = '1';
                            } catch (e) {
                                box.innerHTML =
                                    `<div class="text-danger small">Gagal memuat phase.</div>`;
                            }
                        }
                    }, {
                        once: false
                    });
                });

                // Delegasi klik: Tambah / Hapus / Toggle Phase
                document.addEventListener('click', async (e) => {
                    // Tambah phase
                    if (e.target.matches('.add-phase')) {
                        e.preventDefault();
                        const groupUid = e.target.dataset.group;
                        const date = e.target.dataset.date;
                        const parent = document.getElementById('group-content-' + groupUid);
                        // const body = new URLSearchParams({
                        //     phaseDate: date,
                        //     phaseName: ''
                        // });

                        const res = await fetch(phaseStoreRoute(groupUid), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': token
                            },
                            body
                        });
                        if (res.ok) {
                            const list = await fetch(phaseIndexRoute(groupUid, date), {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            renderPhases(parent, (await list.json()).phases, groupUid, date);
                        } else alert('Gagal menambah phase');
                    }

                    // Hapus phase
                    if (e.target.matches('.delete-phase')) {
                        e.preventDefault();
                        if (!confirm('Hapus phase ini?')) return;
                        const pid = e.target.dataset.phase;
                        const res = await fetch(phaseDestroyRoute(pid), {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': token
                            }
                        });
                        if (res.ok) {
                            // reload box
                            const li = e.target.closest('li.list-group-item');
                            const box = e.target.closest('.accordion-body').querySelector(
                                '.phase-box[id^="group-content-"]');
                            const groupUid = box.dataset.group;
                            const date = box.dataset.date;
                            const list = await fetch(phaseIndexRoute(groupUid, date), {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            renderPhases(box, (await list.json()).phases, groupUid, date);
                        } else alert('Gagal menghapus phase');
                    }

                    // Toggle isi phase (nanti Step 2 isi checklist & tb_activities)
                    if (e.target.matches('.phase-toggle')) {
                        e.preventDefault();
                        const pid = e.target.dataset.phase;
                        const box = document.getElementById('phase-content-' + pid);
                        box.classList.toggle('d-none');
                        // TODO Step 2: muat checklist point & tulis tb_activities
                    }
                });
            });
        </script>

        <script>
            (function() {
                // --- kecil: fetch JSON dengan error handling
                async function fetchJSON(url, options = {}) {
                    const res = await fetch(url, Object.assign({
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    }, options));
                    const text = await res.text();
                    if (!res.ok) {
                        console.error('HTTP', res.status, text);
                        throw new Error('HTTP ' + res.status);
                    }
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Non-JSON:', text);
                        throw e;
                    }
                }

                // --- buka modal dan centang pilihan existing
                async function openAssignModal(dateStr) {
                    // reset centang
                    document.querySelectorAll('#modalAssignGroup .group-opt').forEach(el => el.checked = false);
                    // label & hidden
                    document.getElementById('assign-date-label').textContent = '(' + dateStr + ')';
                    document.getElementById('assign-date').value = dateStr;

                    try {
                        const data = await fetchJSON(
                            `{{ route('schedule.assign-group') }}?d=${encodeURIComponent(dateStr)}`);
                        (data.selected || []).forEach(uid => {
                            const el = document.getElementById('g' + uid);
                            if (el) el.checked = true;
                        });
                        new bootstrap.Modal(document.getElementById('modalAssignGroup')).show();
                    } catch (err) {
                        console.error(err);
                        alert('Tidak bisa memuat group untuk tanggal ini.');
                    }
                }

                // --- klik tanggal: cek dulu → kosong => modal, ada => tampilkan panel kanan
                async function checkAndOpen(dateStr) {
                    try {
                        const data = await fetchJSON(
                            `{{ route('schedule.assign-group') }}?d=${encodeURIComponent(dateStr)}`);
                        const selected = data.selected || [];
                        if (selected.length === 0) {
                            openAssignModal(dateStr);
                        } else {
                            const m = dateStr.slice(0, 7); // YYYY-MM
                            window.location.href =
                                `{{ route('schedule.index') }}?d=${encodeURIComponent(dateStr)}&m=${encodeURIComponent(m)}`;
                        }
                    } catch (err) {
                        console.error(err);
                        alert('Tidak bisa memuat group untuk tanggal ini.');
                    }
                }

                // --- EVENT DELEGATION supaya selalu nempel walau DOM berubah
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.assign-group-btn');
                    if (!btn) return;
                    e.preventDefault();
                    const date = btn.dataset.date;
                    if (date) checkAndOpen(date);
                });

                // --- submit modal: simpan lalu redirect ke bulan dari tanggal yang disimpan
                const form = document.getElementById('assign-group-form');
                if (form) form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const fd = new FormData(form);
                    const selDate = fd.get('date');

                    try {
                        const res = await fetch(`{{ route('schedule.assign-group.save') }}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: fd
                        });
                        const text = await res.text();
                        if (!res.ok) {
                            console.error('Save error', res.status, text);
                            try {
                                alert(JSON.parse(text).message || 'Terjadi kesalahan saat menyimpan.');
                            } catch {
                                alert('Terjadi kesalahan saat menyimpan.');
                            }
                            return;
                        }
                        bootstrap.Modal.getInstance(document.getElementById('modalAssignGroup')).hide();
                        const m = selDate.slice(0, 7);
                        window.location.href =
                            `{{ route('schedule.index') }}?d=${encodeURIComponent(selDate)}&m=${encodeURIComponent(m)}`;
                    } catch (err) {
                        console.error(err);
                        alert('Terjadi kesalahan saat menyimpan.');
                    }
                });

                // ekspor agar tombol "Set Group"/"Edit" (onclick) tetap jalan
                window.openAssignModal = openAssignModal;
            })();
        </script>
    @endpush



@endsection
