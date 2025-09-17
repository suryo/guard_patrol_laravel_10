// resources/js/schedule/index.js

// Titik masuk halaman schedule (modul existing kamu)
import { bindCalendarClicks } from "./calendar";
import { bindAssignGroupModal } from "./assign-group";
import { initPhases } from "./phases";

// Util kecil untuk ambil bootstrap Modal di berbagai bundler
function getBootstrap() {
    // window.bootstrap disediakan oleh bundler/HTML jika Bootstrap JS di-load global
    // Kalau kamu import ESM, silakan ganti sesuai cara impor-mu.
    return window.bootstrap || window.Bootstrap || null;
}

document.addEventListener("DOMContentLoaded", () => {
    // ====== INIT EXISTING MODULES ======
    try {
        bindCalendarClicks?.();
    } catch (e) {
        console.warn("bindCalendarClicks error", e);
    }
    try {
        bindAssignGroupModal?.();
    } catch (e) {
        console.warn("bindAssignGroupModal error", e);
    }
    try {
        initPhases?.();
    } catch (e) {
        console.warn("initPhases error", e);
    }

    // ========================================================================
    // ================ TEMPLATE TABLE: BULK DELETE + PREVIEW ==================
    // ========================================================================
    (function templateTableInit() {
        const checkAll = document.getElementById("tpl-check-all");
        const getChecks = () =>
            Array.from(document.querySelectorAll(".tpl-check"));
        const btnDel = document.getElementById("btnTplDelete");
        const delForm = document.getElementById("tplBulkDeleteForm");

        function refreshDeleteVisibility() {
            if (!btnDel) return;
            const any = getChecks().some((c) => c.checked);
            btnDel.classList.toggle("d-none", !any);
        }

        if (checkAll) {
            checkAll.addEventListener("change", () => {
                getChecks().forEach((c) => (c.checked = checkAll.checked));
                refreshDeleteVisibility();
            });
        }

        document.addEventListener("change", (e) => {
            if (e.target.closest(".tpl-check")) refreshDeleteVisibility();
        });

        document.addEventListener("click", (e) => {
            const delBtn = e.target.closest("#btnTplDelete");
            if (!delBtn) return;

            const ids = getChecks()
                .filter((c) => c.checked)
                .map((c) => c.value);
            if (!ids.length) return;
            if (!confirm(`Hapus ${ids.length} template terpilih?`)) return;

            if (!delForm) return;
            // Bersihkan input sebelumnya
            delForm
                .querySelectorAll('input[name="ids[]"]')
                .forEach((el) => el.remove());
            // Inject ids[]
            ids.forEach((id) => {
                const i = document.createElement("input");
                i.type = "hidden";
                i.name = "ids[]";
                i.value = id;
                delForm.appendChild(i);
            });
            delForm.submit();
        });

        // OPEN TEMPLATE PREVIEW (AJAX → modal)
        document.addEventListener("click", async (e) => {
            const a = e.target.closest(".js-open-template");
            if (!a) return;

            const id = a.dataset.id;
            const modalEl = document.getElementById("templateViewModal");
            const modalBody = document.getElementById(
                "templateViewModalContent"
            );
            const BS = getBootstrap();
            if (!modalEl || !modalBody || !BS) return;

            try {
                const url = `${window.location.origin}/schedule-template/${id}?ajax=1`;
                const res = await fetch(url, {
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                    credentials: "same-origin",
                });
                const html = await res.text();
                modalBody.innerHTML = html;
                new BS.Modal(modalEl).show();
            } catch (err) {
                console.error(err);
                alert("Gagal memuat template.");
            }
        });
    })();

    // ========================================================================
    // ======================= ADD ACTIVITY (PHASE) ============================
    // ========================================================================
    // ========================================================================
// ======================= ADD ACTIVITY (PHASE) ============================
// ========================================================================
(function addActivityInit() {
  const modalEl = document.getElementById("modalAddActivity");
  if (!modalEl) return;
  const BS = getBootstrap();
  if (!BS) return;

  const modal      = new BS.Modal(modalEl);
  const phaseInput = document.getElementById("maa-phase-uid");
  const sgInput    = document.getElementById("maa-schedule-group-uid"); // <-- hidden input
  const selGroups  = document.getElementById("maa-groups");
  const boxDetails = document.getElementById("maa-details");
  const noteInput  = document.getElementById("maa-note");
  const formAdd    = document.getElementById("formAddActivity");

  const DATA = window.SCHEDULE_DATA || {};
  const taskGroups = Array.isArray(DATA.taskGroups) ? DATA.taskGroups : [];
  const taskDetailsFlat = Array.isArray(DATA.taskDetailsFlat) ? DATA.taskDetailsFlat : [];

  function renderGroupOptions() {
    if (!selGroups) return;
    selGroups.innerHTML = "";
    taskGroups.forEach(g => {
      const opt = document.createElement("option");
      opt.value = g.uid;
      opt.textContent = g.name;
      selGroups.appendChild(opt);
    });
  }

  function renderDetailsForSelectedGroups() {
    if (!selGroups || !boxDetails) return;
    const selected = Array.from(selGroups.selectedOptions).map(o => parseInt(o.value, 10));
    boxDetails.innerHTML = "";
    if (!selected.length) {
      boxDetails.innerHTML = '<div class="text-muted small">Pilih minimal 1 group untuk melihat detail.</div>';
      return;
    }
    const filtered = taskDetailsFlat.filter(d => selected.includes(d.group_uid));
    if (!filtered.length) {
      boxDetails.innerHTML = '<div class="text-muted">Detail tidak ditemukan untuk group terpilih.</div>';
      return;
    }
    const groupMap = {};
    filtered.forEach(d => { (groupMap[d.group_uid] ??= []).push(d); });

    Object.entries(groupMap).forEach(([gid, list]) => {
      const section = document.createElement("div");
      section.className = "mb-2";
      const title = document.createElement("div");
      title.className = "fw-semibold small mb-1";
      const groupName = taskGroups.find(g => String(g.uid) === String(gid))?.name || `Group #${gid}`;
      title.textContent = groupName;
      section.appendChild(title);

      list.sort((a,b) => (a.sortOrder ?? 0) - (b.sortOrder ?? 0));
      list.forEach(d => {
        const id = `dt-${d.uid}`;
        const wrap = document.createElement("div");
        wrap.className = "form-check form-check-inline me-3 mb-2";
        const input = document.createElement("input");
        input.className = "form-check-input maa-detail";
        input.type = "checkbox";
        input.id = id;
        input.value = d.uid;
        input.dataset.group = d.group_uid;
        input.dataset.task = d.task_uid || "";
        wrap.appendChild(input);
        const label = document.createElement("label");
        label.className = "form-check-label";
        label.setAttribute("for", id);
        label.textContent = d.task_name || `Detail #${d.uid}`;
        wrap.appendChild(label);
        section.appendChild(wrap);
      });

      boxDetails.appendChild(section);
    });
  }

  // Klik tombol + Add Activity
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".btn-add-activity");
    if (!btn) return;

    const phaseUid = btn.dataset.phase;
    // Ambil schedule_group_uid dari tombol; fallback ke container jika perlu
    let groupUid = btn.dataset.group;
    if (!groupUid) {
      const host = btn.closest('[data-group]') || btn.closest('[id^="group-content-"]');
      if (host) {
        groupUid = host.dataset?.group || (host.id?.startsWith('group-content-') ? host.id.replace('group-content-','') : "");
      }
    }

    if (!phaseUid) { alert("Phase tidak dikenali."); return; }
    if (!groupUid) { alert("Schedule Group tidak dikenali."); return; }

    if (phaseInput) phaseInput.value = String(phaseUid);
    if (sgInput)    sgInput.value    = String(groupUid); // <-- simpan ke hidden

    if (noteInput) noteInput.value = "";
    renderGroupOptions();
    if (selGroups) Array.from(selGroups.options).forEach(o => (o.selected = false));
    renderDetailsForSelectedGroups();
alert("groupUid: " + groupUid);

    modal.show();
  });

  // Filter detail saat group dipilih
  if (selGroups) selGroups.addEventListener("change", renderDetailsForSelectedGroups);

  // Submit → POST /phase/{phase}/activities
  if (formAdd) {
    formAdd.addEventListener("submit", async (ev) => {
      ev.preventDefault();

      const phaseUid = phaseInput?.value;
      const groupUid = sgInput?.value; // <-- BACA dari hidden input
      const note = noteInput?.value?.trim() ?? "";
      const chosen = Array.from(boxDetails?.querySelectorAll(".maa-detail:checked") ?? []);

      if (!phaseUid) { alert("Phase tidak dikenali."); return; }
      if (!groupUid) { alert("Schedule Group tidak dikenali."); return; }
      if (!chosen.length) { alert("Pilih minimal satu Task Group Detail."); return; }

      const activities = chosen.map((chk, idx) => ({
        task_group_uid: parseInt(chk.dataset.group, 10),
        task_group_detail_uid: parseInt(chk.value, 10),
        task_uid: chk.dataset.task ? parseInt(chk.dataset.task, 10) : null,
        sortOrder: idx + 1,
        activityNote: note || null,
      }));
alert("groupUid modal : " + groupUid);
      try {
        const res = await fetch(`/phase/${phaseUid}/activities`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
          },
          body: JSON.stringify({
            schedule_group_uid: parseInt(groupUid, 10), // <-- KIRIM
            activities
          }),
        });

        if (!res.ok) {
          let reason = "Gagal menyimpan activity.";
          try { const j = await res.json(); if (j?.message) reason = j.message; } catch {}
          throw new Error(reason);
        }

        modal.hide();
        window.location.reload(); // ganti dengan partial refresh bila ada
      } catch (err) {
        console.error(err);
        alert(err.message || "Gagal menyimpan activity.");
      }
    });
  }
})();

});
