import { routes } from './api';

export function bindAssignGroupModal() {
  const modalEl = document.getElementById('modalAssignGroup');
  const form    = document.getElementById('assign-group-form');
  if (!modalEl || !form) return;

  async function open(dateStr) {
    // reset
    modalEl.querySelectorAll('.group-opt').forEach(el => el.checked = false);
    modalEl.querySelector('#assign-date-label').textContent = '(' + dateStr + ')';
    modalEl.querySelector('#assign-date').value = dateStr;

    try {
      const res  = await fetch(routes.assignGet(dateStr), { headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' }});
      const data = await res.json();
      (data.selected || []).forEach(uid => {
        const el = modalEl.querySelector('#g' + uid);
        if (el) el.checked = true;
      });
      new bootstrap.Modal(modalEl).show();
    } catch {
      alert('Tidak bisa memuat group untuk tanggal ini.');
    }
  }

  document.addEventListener('assign:open', (e) => open(e.detail.dateStr));

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const selDate = fd.get('date');

    try {
      const res  = await fetch(routes.assignSave(), {
        method: 'POST',
        headers: {'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
        body: fd
      });
      const text = await res.text();
      if (!res.ok) {
        try { alert(JSON.parse(text).message || 'Terjadi kesalahan saat menyimpan.'); }
        catch { alert('Terjadi kesalahan saat menyimpan.'); }
        return;
      }
      bootstrap.Modal.getInstance(modalEl).hide();
      const m = selDate.slice(0,7);
      window.location.href = `${routes.scheduleIdx()}?d=${encodeURIComponent(selDate)}&m=${encodeURIComponent(m)}`;
    } catch {
      alert('Terjadi kesalahan saat menyimpan.');
    }
  });
}
