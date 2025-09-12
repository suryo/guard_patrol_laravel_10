// Titik masuk halaman schedule
import { bindCalendarClicks } from './calendar';
import { bindAssignGroupModal } from './assign-group';
import { initPhases } from './phases';

// Jika pakai Ziggy, import route helper (opsional)
// import route from 'ziggy-js';

document.addEventListener('DOMContentLoaded', () => {
  bindCalendarClicks();
  bindAssignGroupModal();
  initPhases();
});

// === Template table interactivity ===
document.addEventListener('DOMContentLoaded', () => {
  const checkAll = document.getElementById('tpl-check-all');
  const checks   = () => Array.from(document.querySelectorAll('.tpl-check'));
  const btnDel   = document.getElementById('btnTplDelete');
  const delForm  = document.getElementById('tplBulkDeleteForm');
  const delIds   = document.getElementById('tplBulkIds');

  function refreshDeleteVisibility() {
    const any = checks().some(c => c.checked);
    if (any) {
      btnDel.classList.remove('d-none');
    } else {
      btnDel.classList.add('d-none');
    }
  }

  if (checkAll) {
    checkAll.addEventListener('change', () => {
      checks().forEach(c => c.checked = checkAll.checked);
      refreshDeleteVisibility();
    });
  }

  checks().forEach(c => c.addEventListener('change', refreshDeleteVisibility));

  if (btnDel) {
    btnDel.addEventListener('click', () => {
      const ids = checks().filter(c => c.checked).map(c => c.value);
      if (!ids.length) return;
      if (!confirm(`Hapus ${ids.length} template terpilih?`)) return;

      // kirim sebagai array: gunakan input multiple via JSON (laravel bisa terima ids[]=..)
      // Di sini kita simple: buat input dinamis
      // Bersihkan isi form dulu
      delForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
      ids.forEach(id => {
        const i = document.createElement('input');
        i.type = 'hidden';
        i.name = 'ids[]';
        i.value = id;
        delForm.appendChild(i);
      });
      delForm.submit();
    });
  }

  // === Open template in modal ===
  document.querySelectorAll('.js-open-template').forEach(a => {
    a.addEventListener('click', async () => {
      const id = a.dataset.id;
      const modalEl = document.getElementById('templateViewModal');
      const modalBody = document.getElementById('templateViewModalContent');

      try {
        const res = await fetch(`${window.location.origin}/schedule-template/${id}?ajax=1`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin'
        });
        const html = await res.text();
        modalBody.innerHTML = html;

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
      } catch (e) {
        alert('Gagal memuat template.');
        console.error(e);
      }
    });
  });
});

