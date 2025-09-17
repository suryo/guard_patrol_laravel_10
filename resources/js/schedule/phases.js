import { routes, jget, jsend, token } from './api';

function phaseListHTML(phases, groupUid, date) {
  if (!phases || phases.length === 0) {
    return `
      <div class="alert alert-warning py-2 mb-2">Phase belum ada.</div>
      <button class="btn btn-sm btn-primary add-phase" data-group="${groupUid}" data-date="${date}">
        Tambah Phase
      </button>
    `;
  }

  const items = phases.map(p => `
    <li class="list-group-item">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="fw-semibold">
            ${p.phaseName ?? '(phase)'}
            ${p.phaseDate ? `<small class="text-muted">(${p.phaseDate})</small>` : ''}
          </div>
          ${p.phaseId ? `<div class="small text-muted">${p.phaseId}</div>` : ''}
        </div>
        <div class="btn-group btn-group-sm">
          <button
            type="button"
            class="btn btn-sm btn-outline-primary btn-add-activity"
            data-phase="${p.phase_uid}"
            data-group="${groupUid}"
            data-link="${p.link_uid || ''}"
          >
            + Add Activity ${groupUid}
          </button>

          <button
            class="btn btn-outline-secondary edit-phase"
            data-link="${p.link_uid}"
            data-group="${groupUid}"
            data-date="${p.phaseDate}"
            data-phase="${p.phase_uid}"
          >
            Edit
          </button>

          <button
            class="btn btn-outline-danger delete-phase"
            data-link="${p.link_uid}"
          >
            Delete
          </button>
        </div>
      </div>
      <div id="phase-content-${p.link_uid}" class="mt-2 d-none"></div>
    </li>
  `).join('');

  return `
    <ul class="list-group">${items}</ul>
    <button class="btn btn-sm btn-primary mt-2 add-phase" data-group="${groupUid}" data-date="${date}">
      Tambah Phase
    </button>
  `;
}

async function loadPhaseBox(box) {
  const uid  = box.dataset.group;
  const date = box.dataset.date;
  box.innerHTML = `<div class="text-muted small">Memuat phase…</div>`;
  try {
    const data = await jget(routes.listPhases(uid, date));
    box.innerHTML = phaseListHTML(data.phases || [], uid, date);
  } catch (_) {
    box.innerHTML = `
      <div class="alert alert-danger py-2 mb-2">Gagal memuat phase.</div>
      <button class="btn btn-sm btn-primary add-phase" data-group="${uid}" data-date="${date}">
        Tambah Phase
      </button>
    `;
  }
}

function bindAccordionAutoload() {
  document.querySelectorAll('.accordion-collapse').forEach(col => {
    col.addEventListener('shown.bs.collapse', () => {
      col.querySelectorAll('.phase-box').forEach(loadPhaseBox);
    });
  });
}

function bindPickPhaseModal() {
  const modal = document.getElementById('modalPickPhase');
  if (!modal) return;

  const modeEl  = modal.querySelector('#mp-mode');
  const groupEl = modal.querySelector('#mp-group');
  const linkEl  = modal.querySelector('#mp-link');
  const dateEl  = modal.querySelector('#mp-date');
  const phaseEl = modal.querySelector('#mp-phase');
  const titleEl = modal.querySelector('#mp-title');
  const form    = modal.querySelector('#formPickPhase');

  async function openPickModal({ mode, groupUid, date, linkUid = null, selectedPhaseUid = null }) {
    modeEl.value  = mode;
    groupEl.value = groupUid;
    linkEl.value  = linkUid || '';
    dateEl.value  = date;
    titleEl.textContent = (mode === 'edit') ? 'Edit Phase' : 'Pilih Phase';

    // load options
    phaseEl.innerHTML = `<option value="">-- pilih --</option>`;
    try {
      const data = await jget(routes.phaseOpts());
      (data.options || []).forEach(o => {
        const opt = document.createElement('option');
        opt.value = o.uid;
        opt.textContent = (o.phaseOrder ? o.phaseOrder + '. ' : '') + (o.phaseName || o.phaseId || ('#' + o.uid));
        if (selectedPhaseUid && +selectedPhaseUid === +o.uid) opt.selected = true;
        phaseEl.appendChild(opt);
      });
    } catch {
      alert('Gagal memuat opsi phase.');
      return;
    }

    new bootstrap.Modal(modal).show();
  }

  // delegated clicks
  document.addEventListener('click', async (e) => {
    const t = e.target;

    if (t.matches('.add-phase')) {
      e.preventDefault();
      await openPickModal({
        mode: 'create',
        groupUid: t.dataset.group,
        date: t.dataset.date
      });
    }

    if (t.matches('.edit-phase')) {
      e.preventDefault();
      await openPickModal({
        mode: 'edit',
        groupUid: t.dataset.group,
        date: t.dataset.date,
        linkUid: t.dataset.link,
        selectedPhaseUid: t.dataset.phase
      });
    }

    if (t.matches('.delete-phase')) {
      e.preventDefault();
      if (!confirm('Hapus phase ini dari group?')) return;
      const link = t.dataset.link;
      try {
        await fetch(routes.deleteLink(link), {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
          }
        });
        const box = t.closest('.accordion-body').querySelector('.phase-box');
        await loadPhaseBox(box);
      } catch {
        alert('Gagal menghapus.');
      }
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const mode     = modeEl.value;
    const groupUid = groupEl.value;
    const linkUid  = linkEl.value;
    const phaseUid = phaseEl.value;
    const date     = dateEl.value;

    if (!phaseUid || !date) {
      alert('Lengkapi pilihan phase & tanggal.');
      return;
    }

    try {
      if (mode === 'create') {
        await jsend(routes.storePhase(groupUid), 'POST', {
          phase_uid: +phaseUid,
          phaseDate: date,
          schedule_group_uid: +groupUid   // <<-- tambahan
        });
      } else {
        await jsend(routes.updateLink(linkUid), 'PUT', {
          phase_uid: +phaseUid,
          phaseDate: date,
          schedule_group_uid: +groupUid   // <<-- tambahan
        });
      }
      bootstrap.Modal.getInstance(modal).hide();
      const box = document.getElementById('group-content-' + groupUid);
      await loadPhaseBox(box);
    } catch {
      alert('Gagal menyimpan phase.');
    }
  });
}

export function initPhases() {
  bindAccordionAutoload();
  bindPickPhaseModal();
}
