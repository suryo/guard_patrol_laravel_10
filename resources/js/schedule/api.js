// resources/js/schedule/api.js
// Tidak memakai Ziggy. Semua endpoint pakai path absolut sesuai web.php

export const token =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

export const routes = {
  // Phase <-> schedule_group
  listPhases:  (uid, date) => `/ajax/schedule-group/${uid}/phases?d=${encodeURIComponent(date)}`,
  storePhase:  (uid)       => `/ajax/schedule-group/${uid}/phases`,
  updateLink:  (link)      => `/ajax/schedule-group-phase/${link}`,
  deleteLink:  (link)      => `/ajax/schedule-group-phase/${link}`,
  phaseOpts:   ()          => `/ajax/phases/options`,

  // Assign group per tanggal
  assignGet:   (d)         => `/schedule/assign-group?d=${encodeURIComponent(d)}`,
  assignSave:  ()          => `/schedule/assign-group`,

  // Halaman schedule index
  scheduleIdx: ()          => `/schedule`,
};

// util fetch
export async function jget(url) {
  const r = await fetch(url, {
    headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' },
    credentials: 'same-origin',
  });
  if (!r.ok) {
    const txt = await r.text();
    console.error('GET failed', r.status, url, txt);
    throw new Error('GET ' + r.status);
  }
  return r.json();
}

export async function jsend(url, method, body) {
  const r = await fetch(url, {
    method,
    headers: {
      'Accept':'application/json',
      'X-Requested-With':'XMLHttpRequest',
      'Content-Type':'application/json',
      'X-CSRF-TOKEN': token,
    },
    body: JSON.stringify(body),
    credentials: 'same-origin',
  });
  if (!r.ok) {
    const txt = await r.text();
    console.error(method, 'failed', r.status, url, txt);
    throw new Error(method + ' ' + r.status);
  }
  try { return await r.json(); } catch { return { ok: true }; }
}
