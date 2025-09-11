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
