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
const USE_DUMMY_ACTS = true;

function getDummyActivities(phaseUid) {
    // variasi ringan berdasarkan digit terakhir phaseUid
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
            // dummy fields ala template
            pic: ["Andi", "Sinta", "Rudi", "Rina", "Budi"][i % 5],
            where: ["Workshop", "Ruang A", "Gate 1", "Lokasi L1", "Lab"][i % 5],
            time: ["07:00", "07:15", "07:30", "08:00", "09:00"][i % 5],
        });
    }
    return list;
}

/* ============================================================================
 *  RENDER ACTIVITIES LIST (konten dalam panel)
 *    -> gaya kotak activity (activity-box) ala template
 * ==========================================================================*/
function renderActivitiesHTML(list) {
    const acts = Array.isArray(list) ? [...list] : [];
    if (!acts.length) {
        return `<div class="text-muted small">Belum ada activity untuk phase ini.</div>`;
    }
    acts.sort((a, b) => (a.sortOrder ?? 0) - (b.sortOrder ?? 0));

    const rows = acts
        .map((a) => {
            const det =
                a.taskGroupDetail?.name ??
                a.taskGroupDetail?.detailName ??
                a.detail_name ??
                `Detail #${a.task_group_detail_uid}`;
            const pos =
                a.taskGroup?.name ??
                a.taskGroup?.groupName ??
                a.task_group_name ??
                `Group #${a.task_group_uid}`;
            const task = a.task?.taskName ?? a.task?.name ?? a.task_name ?? "";
            const note = a.activityNote ?? a.note ?? "";
            const pic = a.pic ?? "-";
            const where = a.where ?? "-";
            const time = a.time ?? "-";

            return `
        <div class="activity-box mb-2">
          <div class="fw-semibold">${esc(det)}</div>
          <div class="small text-muted mb-2">
            Pos: ${esc(pos)} ${task ? `&middot; Task: ${esc(task)}` : ""}
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="kv"><div class="k">PIC</div><div class="v">: ${esc(
                  pic
              )}</div></div>
              <div class="kv"><div class="k">Lokasi</div><div class="v">: ${esc(
                  where
              )}</div></div>
              <div class="kv"><div class="k">Waktu</div><div class="v">: ${esc(
                  time
              )}</div></div>
            </div>
            <div class="col-md-6">
              <div class="kv"><div class="k">Catatan</div><div class="v">: ${
                  note ? esc(note) : "-"
              }</div></div>
            </div>
          </div>
          <div class="mt-2 d-flex justify-content-end">
            <button class="btn btn-sm btn-outline-danger btn-del-activity" data-act="${esc(
                a.uid ?? a.id
            )}">
              Hapus
            </button>
          </div>
        </div>
      `;
        })
        .join("");

    return `<div>${rows}</div>`;
}

// === GAYA ACCORDION PER ACTIVITY (sesuai template) ===
function renderActivitiesAccordionHTML(list, seed = "") {
    const acts = Array.isArray(list) ? [...list] : [];
    if (!acts.length) {
        return `<div class="text-muted small">Belum ada activity untuk phase ini.</div>`;
    }
    acts.sort((a, b) => (a.sortOrder ?? 0) - (b.sortOrder ?? 0));

    const items = acts
        .map((a, idx) => {
            const idSafe = String(a.uid ?? a.id ?? idx);
            const aHeaderId = `act-h-${seed}-${idSafe}`;
            const aCollapseId = `act-c-${seed}-${idSafe}`;

            const det =
                a.taskGroupDetail?.name ??
                a.taskGroupDetail?.detailName ??
                a.detail_name ??
                `Detail #${a.task_group_detail_uid}`;
            const pos =
                a.taskGroup?.name ??
                a.taskGroup?.groupName ??
                a.task_group_name ??
                `Group #${a.task_group_uid}`;
            const task = a.task?.taskName ?? a.task?.name ?? a.task_name ?? "";
            const note = a.activityNote ?? a.note ?? "";
            const pic = a.pic ?? "-";
            const where = a.where ?? "-";
            const time = a.time ?? "-";

            return `
      <div class="accordion-item">
        <h2 class="accordion-header" id="${aHeaderId}">
          <button class="accordion-button collapsed" type="button"
            data-bs-toggle="collapse" data-bs-target="#${aCollapseId}"
            aria-expanded="false" aria-controls="${aCollapseId}">
            <span class="badge badge-muted me-2">Activity</span>
            ${esc(det)}
            <span class="ms-auto text-muted small">ID: ${esc(idSafe)}</span>
          </button>
        </h2>
        <div id="${aCollapseId}" class="accordion-collapse collapse" aria-labelledby="${aHeaderId}">
          <div class="accordion-body">
            <div class="activity-box">
              <div class="fw-semibold mb-1">${esc(det)}</div>
              <div class="small text-muted mb-2">
                Pos: ${esc(pos)} ${task ? `&middot; Task: ${esc(task)}` : ""}
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="kv"><div class="k">PIC</div><div class="v">: ${esc(
                      pic
                  )}</div></div>
                  <div class="kv"><div class="k">Lokasi</div><div class="v">: ${esc(
                      where
                  )}</div></div>
                  <div class="kv"><div class="k">Waktu</div><div class="v">: ${esc(
                      time
                  )}</div></div>
                </div>
                <div class="col-md-6">
                  <div class="kv"><div class="k">Catatan</div><div class="v">: ${
                      note ? esc(note) : "-"
                  }</div></div>
                </div>
              </div>
              <div class="mt-2 d-flex justify-content-end">
                <button class="btn btn-sm btn-outline-danger btn-del-activity" data-act="${esc(
                    a.uid ?? a.id
                )}">
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
        })
        .join("");

    return `
    <div class="accordion acc-clean activity-accordion">
      ${items}
    </div>
  `;
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

  const items = phases.map((p) => {
    const pid = p.phase_uid;
    const linkUid = p.link_uid || "";
    const pHeaderId   = `ph-h-${groupUid}-${pid}-${linkUid}`;
    const pCollapseId = `ph-c-${groupUid}-${pid}-${linkUid}`;
    const actPanelId  = `act-panel-${groupUid}-${pid}-${linkUid}`;

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
                     data-phase="${pid}">
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
  }).join("");

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
      <button class="btn btn-sm btn-primary add-phase" data-group="${uid}" data-date="${esc(
            date
        )}">
        Tambah Phase
      </button>
    `;
    } finally {
        box.dataset.loading = "0";
    }
}

/* ============================================================================
 *  LOAD ACTIVITIES KE PANEL (lazy)
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
            const res = await fetch(`/phase/${phaseUid}/activities`, {
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

        // render isi panel
        // body.innerHTML = renderActivitiesHTML(acts);
        const seed = String(phaseUid); // pembeda unik ID accordion
        body.innerHTML = renderActivitiesAccordionHTML(acts, seed);
        panelEl.dataset.loaded = "1";

        // update badge jumlah di header phase
        const accItem = panelEl.closest(".accordion-item"); // parent item phase
        const countBadge = accItem?.querySelector(
            ".act-count[data-phase='" + phaseUid + "']"
        );
        if (countBadge) countBadge.textContent = `${acts.length} activity`;
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
    document.querySelectorAll(".accordion-collapse").forEach((col) => {
        col.addEventListener("shown.bs.collapse", () => {
            // Abaikan inner activities
            if (col.classList.contains("act-collapse")) return;
            col.querySelectorAll(".phase-box").forEach(loadPhaseBox);
        });
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
            document
                .querySelectorAll(".accordion-collapse.show")
                .forEach((el) => {
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

    async function openPickModal({
        mode,
        groupUid,
        date,
        linkUid = null,
        selectedPhaseUid = null,
    }) {
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
                if (selectedPhaseUid && +selectedPhaseUid === +o.uid)
                    opt.selected = true;
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
                    document.querySelector(
                        `.phase-box[data-group="${t.dataset.group}"]`
                    );
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
