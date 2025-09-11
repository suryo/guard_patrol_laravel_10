// resources/js/schedule/calendar.js
import { routes, jget } from './api';

export function bindCalendarClicks() {
  // Global helper untuk tombol "Set Group" / "Edit" di panel kanan
  window.openAssignModal = (dateStr) => {
    document.dispatchEvent(new CustomEvent('assign:open', { detail: { dateStr } }));
  };

  async function showAssignModal(dateStr) {
    // lempar event ke assign-group.js
    document.dispatchEvent(new CustomEvent('assign:open', { detail: { dateStr } }));
  }

  async function checkAndOpen(dateStr) {
    try {
      const data = await jget(routes.assignGet(dateStr));
      const selected = data.selected || [];
      if (selected.length === 0) {
        await showAssignModal(dateStr);
      } else {
        const m = dateStr.slice(0, 7);
        window.location.href =
          `${routes.scheduleIdx()}?d=${encodeURIComponent(dateStr)}&m=${encodeURIComponent(m)}`;
      }
    } catch (err) {
      console.error('assignGet error:', err);
      alert('Tidak bisa memuat group untuk tanggal ini.');
    }
  }

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.assign-group-btn');
    if (!btn) return;
    e.preventDefault();
    const date = btn.dataset.date;
    if (date) checkAndOpen(date);
  });
}
