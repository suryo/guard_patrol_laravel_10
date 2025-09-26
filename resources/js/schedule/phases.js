// resources/js/schedule/phases.js
import { routes, jget, jsend, token } from "./api";

/* ============================================================================
 *  UTIL
 * ==========================================================================*/
function esc(s) {
  return String(s ?? "").replace(
    /[&<>"']/g,
    (m) =>
      ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
      }[m])
  );
}

function getBootstrap() {
  return window.bootstrap || window.Bootstrap || null;
}

/* ============================================================================
 *  DUMMY MODE (untuk uji accordion)
 * ==========================================================================*/
const USE_DUMMY_ACTS = false;

function getDummyActivities(phaseUid) {
  const n = (Number(String(phaseUid).match(/\d+$/)?.[0] || 0) % 3) + 3; // 3..5
  const list = [];
  for (let i = 1; i <= n; i++) {
    list.push({
      uid: `${phaseUid}-${i}`,
      sortOrder: i,
      taskGroup: { name: `Group ${(i % 3) + 1}` },
      taskGroupDetail: { name: `Detail Dummy ${i}` },
      task: { taskName: `Task Dummy ${i}` },
      activityNote: i % 2 ? "Catatan dummy singkat." : "",
      pic: ["Andi", "Sinta", "Rudi", "Rina", "Budi"][i % 5],
      where: ["Workshop", "Ruang A", "Gate 1", "Lokasi L1", "Lab"][i % 5],
      time: ["07:00", "07:15", "07:30", "08:00", "09:00"][i % 5],
    });
  }
  return list;
}

/* ============================================================================
 *  RENDER ACTIVITIES LIST (konten dalam panel) -> kotak activity
 * ==========================================================================*/
function renderActivitiesHTML(list) {
  const acts = Array.isArray(list) ? [...list] : [];
  if (!acts.length) {
    return `<div class="text-muted small">Belum ada activity untuk phase ini.</div>`;
  }

  // Urutkan dulu
  acts.sort((a, b) => (a.sortOrder ?? 0) - (b.sortOrder ?? 0));

  // Helper ambil field aman
  const getDet = (a) =>
    a?.taskGroupDetail?.name ??
    a?.taskGroupDetail?.detailName ??
    a?.detail_name ??
    `Detail #${a?.task_group_detail_uid}`;

  const getTask = (a) =>
    a?.task?.taskName ?? a?.task?.name ?? a?.task_name ?? "";

  const getPos = (a) =>
    a?.taskGroup?.name ??
    a?.taskGroup?.groupName ??
    a?.task_group_name ??
    (a?.task_group_uid ? `Group #${a.task_group_uid}` : "");

  // Kelompokkan per "detail" (Point)
  const byPoint = new Map();
  for (const a of acts) {
    const key = getDet(a);
    if (!byPoint.has(key)) byPoint.set(key, []);
    byPoint.get(key).push(a);
  }

  // Render section per point
  const sections = [];
  for (const [det, items] of byPoint.entries()) {
    // info representatif dari item pertama
    const first = items[0] || {};
    const pic = first.pic || "";
    const pos = getPos(first);

    // cari waktu (prioritas a.time; fallback detail timeStart/timeEnd)
    const time =
      first.time ||
      first?.taskGroupDetail?.time ||
      (first?.taskGroupDetail?.timeStart && first?.taskGroupDetail?.timeEnd
        ? `${first.taskGroupDetail.timeStart} - ${first.taskGroupDetail.timeEnd}`
        : "");

    // Judul seperti "Point 37 - Imam (Imam)" jika pic ada
    const titleRight = `
      <div class="d-flex align-items-center gap-2">
        ${
          items.length
            ? `<button class="btn btn-link p-0 text-danger btn-del-activity" title="Hapus"
                 data-act="${esc(items[0].uid ?? items[0].id)}">🗑️</button>`
            : ""
        }
        <button class="btn btn-link p-0 text-primary btn-edit-activity" title="Edit"
          data-act="${esc(items[0]?.uid ?? items[0]?.id ?? "")}">✏️</button>
        <span class="ms-1 d-inline-block rounded-circle" style="width:12px;height:12px;background:#dc3545;"></span>
      </div>`;

    const header = `
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="h6 mb-1">${esc(det)}${pic ? ` – ${esc(pic)} (${esc(pic)})` : ""}</div>
          ${
            time
              ? `<div class="text-muted small"><span class="me-1">🕒</span>${esc(time)}</div>`
              : ""
          }
          ${pos ? `<div class="text-muted small">Pos: ${esc(pos)}</div>` : ""}
        </div>
        ${titleRight}
      </div>`;

    // Chips merah untuk setiap task dalam point ini
    const chips = items
      .map((a) => {
        const label = getTask(a) || getDet(a);
        return `<span class="badge rounded-pill border border-danger text-danger fw-normal px-3 py-2 me-2 mb-2">
                  ${esc(label)}
                </span>`;
      })
      .join("");

    sections.push(`
      <div class="pb-3 mb-3 border-bottom">
        ${header}
        <div class="mt-2 d-flex flex-wrap">
          ${chips}
        </div>
      </div>
    `);
  }

  return `<div>${sections.join("")}</div>`;
}


/* ============================================================================
 *  RENDER PHASE LIST (kanan, per group) → accordion rapi
 * ==========================================================================*/
function phaseListHTML(phases, groupUid, date) {
  if (!phases || phases.length === 0) {
    return `
      <div class="alert alert-warning py-2 mb-2">Phase belum ada.</div>
      <button class="btn btn-sm btn-primary add-phase" data-group="${groupUid}" data-date="${esc(date)}">
        Tambah Phase
      </button>
    `;
  }

  const items = phases
    .map((p) => {
      const pid = p.phase_uid;
      const linkUid = p.link_uid || "";
      const pHeaderId = `ph-h-${groupUid}-${pid}-${linkUid}`;
      const pCollapseId = `ph-c-${groupUid}-${pid}-${linkUid}`;
      const actPanelId = `act-panel-${groupUid}-${pid}-${linkUid}`;

      return `
        <div class="accordion-item">
          <h2 class="accordion-header" id="${pHeaderId}">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#${pCollapseId}"
              aria-expanded="false"
              aria-controls="${pCollapseId}">
              <span class="badge badge-muted me-2">Phase</span>
              ${esc(p.phaseName ?? "(phase)")}
              ${p.phaseDate ? `<small class="text-muted ms-2">(${esc(p.phaseDate)})</small>` : ""}
              <span class="ms-auto badge text-bg-secondary act-count" data-phase="${pid}">...</span>
            </button>
          </h2>

          <div id="${pCollapseId}" class="accordion-collapse collapse" aria-labelledby="${pHeaderId}">
            <div class="accordion-body">
              <!-- Toolbar Phase -->
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="small text-muted">${p.phaseId ? esc(p.phaseId) : ""}</div>
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-primary btn-add-activity"
                    data-phase="${pid}" data-group="${groupUid}" data-link="${linkUid}">
                    + Add Activity
                  </button>
                  <button class="btn btn-outline-secondary edit-phase"
                    data-link="${linkUid}" data-group="${groupUid}" data-date="${esc(p.phaseDate)}" data-phase="${pid}">
                    Edit
                  </button>
                  <button class="btn btn-outline-danger delete-phase" data-link="${linkUid}" data-group="${groupUid}">
                    Delete
                  </button>
                </div>
              </div>

              <!-- INNER: Activities (single toggle) -->
              <div class="accordion acc-clean activity-accordion">
                <div class="accordion-item border">
                  <h2 class="accordion-header">
                    <button
                      type="button"
                      class="accordion-button collapsed act-toggle"
                      data-bs-toggle="collapse"
                      data-bs-target="#${actPanelId}"
                      aria-expanded="false"
                      aria-controls="${actPanelId}"
                      data-phase="${pid}">
                      Activities
                      <span class="ms-auto badge text-bg-secondary act-count" data-phase="${pid}">...</span>
                    </button>
                  </h2>
                  <div id="${actPanelId}"
                       class="accordion-collapse collapse act-collapse"
                       data-phase="${pid}"
                       data-group="${groupUid}">
                    <div class="accordion-body p-2">
                      <div class="text-muted small">Memuat…</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /INNER -->
            </div>
          </div>
        </div>
      `;
    })
    .join("");

  return `
    <div class="accordion acc-clean phase-accordion">
      ${items}
    </div>
    <button class="btn btn-sm btn-primary mt-2 add-phase" data-group="${groupUid}" data-date="${esc(date)}">
      Tambah Phase
    </button>
  `;
}

/* ============================================================================
 *  LOAD PHASE BOX (ketika parent group collapse dibuka)
 * ==========================================================================*/
async function loadPhaseBox(box) {
  const uid = box.dataset.group;
  const date = box.dataset.date;

  box.dataset.loading = "1";
  box.innerHTML = `<div class="text-muted small">Memuat phase…</div>`;

  try {
    const data = await jget(routes.listPhases(uid, date));
    box.innerHTML = phaseListHTML(data.phases || [], uid, date);
    box.dataset.loaded = "1";
  } catch (_) {
    box.innerHTML = `
      <div class="alert alert-danger py-2 mb-2">Gagal memuat phase.</div>
      <button class="btn btn-sm btn-primary add-phase" data-group="${uid}" data-date="${esc(date)}">
        Tambah Phase
      </button>
    `;
  } finally {
    box.dataset.loading = "0";
  }
}

/* ============================================================================
 *  LOAD ACTIVITIES KE PANEL (lazy, scoped by schedule_group_uid)
 * ==========================================================================*/
async function loadPhaseActivities(phaseUid, panelEl) {
  if (!panelEl) return;
  const body = panelEl.querySelector(".accordion-body");
  if (!body) return;

  if (panelEl.dataset.loading === "1") return; // anti dobel
  panelEl.dataset.loading = "1";
  body.innerHTML = `<div class="text-muted small">Memuat…</div>`;

  try {
    let acts = [];
    if (USE_DUMMY_ACTS) {
      await new Promise((r) => setTimeout(r, 250));
      acts = getDummyActivities(phaseUid);
    } else {
      // Ambil konteks group dari data-group pada panel, atau fallback ke .phase-box terdekat
      const groupUid =
        panelEl.dataset.group ||
        panelEl.closest(".phase-box")?.dataset.group ||
        null;

      const qs = groupUid ? `?schedule_group_uid=${encodeURIComponent(groupUid)}` : "";
      const res = await fetch(`/phase/${phaseUid}/activities${qs}`, {
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      });
      let json = null;
      try {
        json = await res.json();
      } catch {}
      acts = Array.isArray(json) ? json : json?.data || [];
    }

    // render isi panel (single toggle)
    body.innerHTML = renderActivitiesHTML(acts);
    panelEl.dataset.loaded = "1";

    // update badge jumlah di header phase & di tombol Activities
    const container = panelEl.closest(".accordion-item"); // item phase
    const countBadges = container?.querySelectorAll(".act-count[data-phase='" + phaseUid + "']") || [];
    countBadges.forEach((el) => (el.textContent = `${acts.length} activity`));
  } catch (e) {
    console.error(e);
    body.innerHTML = `<div class="text-danger small">Gagal memuat activities.</div>`;
  } finally {
    panelEl.dataset.loading = "0";
  }
}

/* ============================================================================
 *  BIND: Autoload Phase Box saat parent collapse (group) dibuka
 * ==========================================================================*/
function bindAccordionAutoload() {
  // Delegasi: dengarkan collapse yang dibuka dari document
  document.addEventListener("shown.bs.collapse", (event) => {
    const collapseElement = event.target;
    // Abaikan collapse "activities"
    if (collapseElement.classList.contains("act-collapse")) return;

    // Muat semua .phase-box di dalam collapse group yang baru dibuka
    const phaseBoxes = collapseElement.querySelectorAll(".phase-box");
    if (phaseBoxes.length > 0) {
      phaseBoxes.forEach(loadPhaseBox);
    }
  });
}

/* ============================================================================
 *  BIND: Activities Accordion (load saat dibuka)
 * ==========================================================================*/
function bindActivitiesAccordion() {
  // Load saat panel AKAN dibuka (khusus inner .act-collapse)
  document.addEventListener("show.bs.collapse", (ev) => {
    const panel = ev.target;
    if (!panel.classList.contains("act-collapse")) return;
    if (!panel.dataset.phase) return;
    if (panel.dataset.loaded === "1") return;
    loadPhaseActivities(panel.dataset.phase, panel);
  });

  // Hapus activity
  document.addEventListener("click", async (e) => {
    const btn = e.target.closest(".btn-del-activity");
    if (!btn) return;
    e.preventDefault();

    if (USE_DUMMY_ACTS) {
      alert("Mode dummy: hapus tidak diaktifkan.");
      return;
    }

    if (!confirm("Hapus activity ini?")) return;

    const actId = btn.dataset.act;
    const panel = btn.closest(".act-collapse");
    const phaseUid = panel?.dataset?.phase;

    try {
      const res = await fetch(`/phase/activities/${actId}`, {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": token,
        },
      });
      if (!res.ok) throw new Error("Gagal menghapus activity.");
      panel.dataset.loaded = "0";
      await loadPhaseActivities(phaseUid, panel);
    } catch (err) {
      console.error(err);
      alert("Gagal menghapus activity.");
    }
  });
}

/* ============================================================================
 *  BIND: Expand / Collapse All (semua level collapse)
 * ==========================================================================*/
function bindExpandCollapseAll() {
  const btnExpand = document.getElementById("btnExpandAll");
  const btnCollapse = document.getElementById("btnCollapseAll");
  const BS = getBootstrap();

  if (btnExpand && BS) {
    btnExpand.addEventListener("click", () => {
      document.querySelectorAll(".accordion-collapse").forEach((el) => {
        if (!el.classList.contains("show")) {
          new BS.Collapse(el, { toggle: true }).show();
        }
      });
    });
  }
  if (btnCollapse && BS) {
    btnCollapse.addEventListener("click", () => {
      document.querySelectorAll(".accordion-collapse.show").forEach((el) => {
        new BS.Collapse(el, { toggle: true }).hide();
      });
    });
  }
}

/* ============================================================================
 *  BIND: Modal pilih / edit phase (tetap real ke server)
 * ==========================================================================*/
function bindPickPhaseModal() {
  const modal = document.getElementById("modalPickPhase");
  if (!modal) return;

  const modeEl = modal.querySelector("#mp-mode");
  const groupEl = modal.querySelector("#mp-group");
  const linkEl = modal.querySelector("#mp-link");
  const dateEl = modal.querySelector("#mp-date");
  const phaseEl = modal.querySelector("#mp-phase");
  const titleEl = modal.querySelector("#mp-title");
  const form = modal.querySelector("#formPickPhase");

  async function openPickModal({ mode, groupUid, date, linkUid = null, selectedPhaseUid = null }) {
    modeEl.value = mode;
    groupEl.value = groupUid;
    linkEl.value = linkUid || "";
    dateEl.value = date;
    titleEl.textContent = mode === "edit" ? "Edit Phase" : "Pilih Phase";

    // load options
    phaseEl.innerHTML = `<option value="">-- pilih --</option>`;
    try {
      const data = await jget(routes.phaseOpts());
      (data.options || []).forEach((o) => {
        const opt = document.createElement("option");
        opt.value = o.uid;
        opt.textContent =
          (o.phaseOrder ? o.phaseOrder + ". " : "") +
          (o.phaseName || o.phaseId || "#" + o.uid);
        if (selectedPhaseUid && +selectedPhaseUid === +o.uid) opt.selected = true;
        phaseEl.appendChild(opt);
      });
    } catch {
      alert("Gagal memuat opsi phase.");
      return;
    }

    const BS = getBootstrap();
    new BS.Modal(modal).show();
  }

  // delegated clicks
  document.addEventListener("click", async (e) => {
    const t = e.target;

    if (t.matches(".add-phase")) {
      e.preventDefault();
      await openPickModal({
        mode: "create",
        groupUid: t.dataset.group,
        date: t.dataset.date,
      });
    }

    if (t.matches(".edit-phase")) {
      e.preventDefault();
      await openPickModal({
        mode: "edit",
        groupUid: t.dataset.group,
        date: t.dataset.date,
        linkUid: t.dataset.link,
        selectedPhaseUid: t.dataset.phase,
      });
    }

    if (t.matches(".delete-phase")) {
      e.preventDefault();
      if (!confirm("Hapus phase ini dari group?")) return;
      const link = t.dataset.link;
      try {
        await fetch(routes.deleteLink(link), {
          method: "DELETE",
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token,
          },
        });
        const box =
          t.closest(".accordion-body")?.querySelector(".phase-box") ||
          document.querySelector(`.phase-box[data-group="${t.dataset.group}"]`);
        if (box) await loadPhaseBox(box);
      } catch {
        alert("Gagal menghapus.");
      }
    }
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const mode = modeEl.value;
    const groupUid = groupEl.value;
    const linkUid = linkEl.value;
    const phaseUid = phaseEl.value;
    const date = dateEl.value;

    if (!phaseUid || !date) {
      alert("Lengkapi pilihan phase & tanggal.");
      return;
    }

    try {
      if (mode === "create") {
        await jsend(routes.storePhase(groupUid), "POST", {
          phase_uid: +phaseUid,
          phaseDate: date,
          schedule_group_uid: +groupUid,
        });
      } else {
        await jsend(routes.updateLink(linkUid), "PUT", {
          phase_uid: +phaseUid,
          phaseDate: date,
          schedule_group_uid: +groupUid,
        });
      }
      const BS = getBootstrap();
      BS.Modal.getInstance(modal).hide();
      const box =
        document.getElementById("group-content-" + groupUid) ||
        document.querySelector(`.phase-box[data-group="${groupUid}"]`);
      if (box) await loadPhaseBox(box);
    } catch {
      alert("Gagal menyimpan phase.");
    }
  });
}

/* ============================================================================
 *  INIT
 * ==========================================================================*/
let __phasesBound = false;
export function initPhases() {
  if (__phasesBound) return;
  __phasesBound = true;

  bindAccordionAutoload();
  bindPickPhaseModal();
  bindActivitiesAccordion();
  bindExpandCollapseAll();
}
