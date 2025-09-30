var O;const B=(O=document.querySelector('meta[name="csrf-token"]'))==null?void 0:O.getAttribute("content"),L={listPhases:(t,e)=>`/ajax/schedule-group/${t}/phases?d=${encodeURIComponent(e)}`,storePhase:t=>`/ajax/schedule-group/${t}/phases`,updateLink:t=>`/ajax/schedule-group-phase/${t}`,deleteLink:t=>`/ajax/schedule-group-phase/${t}`,phaseOpts:()=>"/ajax/phases/options",assignGet:t=>`/schedule/assign-group?d=${encodeURIComponent(t)}`,assignSave:()=>"/schedule/assign-group",scheduleIdx:()=>"/schedule"};async function R(t){const e=await fetch(t,{headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest"},credentials:"same-origin"});if(!e.ok){const n=await e.text();throw console.error("GET failed",e.status,t,n),new Error("GET "+e.status)}return e.json()}async function P(t,e,n){const s=await fetch(t,{method:e,headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest","Content-Type":"application/json","X-CSRF-TOKEN":B},body:JSON.stringify(n),credentials:"same-origin"});if(!s.ok){const o=await s.text();throw console.error(e,"failed",s.status,t,o),new Error(e+" "+s.status)}try{return await s.json()}catch{return{ok:!0}}}function I(){window.openAssignModal=n=>{document.dispatchEvent(new CustomEvent("assign:open",{detail:{dateStr:n}}))};async function t(n){document.dispatchEvent(new CustomEvent("assign:open",{detail:{dateStr:n}}))}async function e(n){try{if(((await R(L.assignGet(n))).selected||[]).length===0)await t(n);else{const i=n.slice(0,7);window.location.href=`${L.scheduleIdx()}?d=${encodeURIComponent(n)}&m=${encodeURIComponent(i)}`}}catch(s){console.error("assignGet error:",s),alert("Tidak bisa memuat group untuk tanggal ini.")}}document.addEventListener("click",n=>{const s=n.target.closest(".assign-group-btn");if(!s)return;n.preventDefault();const o=s.dataset.date;o&&e(o)})}function C(){const t=document.getElementById("modalAssignGroup"),e=document.getElementById("assign-group-form");if(!t||!e)return;async function n(s){t.querySelectorAll(".group-opt").forEach(o=>o.checked=!1),t.querySelector("#assign-date-label").textContent="("+s+")",t.querySelector("#assign-date").value=s;try{((await(await fetch(L.assignGet(s),{headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest"}})).json()).selected||[]).forEach(c=>{const u=t.querySelector("#g"+c);u&&(u.checked=!0)}),new bootstrap.Modal(t).show()}catch{alert("Tidak bisa memuat group untuk tanggal ini.")}}document.addEventListener("assign:open",s=>n(s.detail.dateStr)),e.addEventListener("submit",async s=>{s.preventDefault();const o=new FormData(e),i=o.get("date");try{const c=await fetch(L.assignSave(),{method:"POST",headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest"},body:o}),u=await c.text();if(!c.ok){try{alert(JSON.parse(u).message||"Terjadi kesalahan saat menyimpan.")}catch{alert("Terjadi kesalahan saat menyimpan.")}return}bootstrap.Modal.getInstance(t).hide();const h=i.slice(0,7);window.location.href=`${L.scheduleIdx()}?d=${encodeURIComponent(i)}&m=${encodeURIComponent(h)}`}catch{alert("Terjadi kesalahan saat menyimpan.")}})}function $(t){return String(t??"").replace(/[&<>"']/g,e=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"})[e])}function M(){return window.bootstrap||window.Bootstrap||null}const X=!1;function U(t){var u,h,p,d,g;const e=Array.isArray(t)?[...t]:[];if(!e.length)return'<div class="text-muted small">Belum ada activity untuk phase ini.</div>';e.sort((a,r)=>(a.sortOrder??0)-(r.sortOrder??0));const n=a=>{var r,l;return((r=a==null?void 0:a.taskGroupDetail)==null?void 0:r.name)??((l=a==null?void 0:a.taskGroupDetail)==null?void 0:l.detailName)??(a==null?void 0:a.detail_name)??`Detail #${a==null?void 0:a.task_group_detail_uid}`},s=a=>{var r,l;return((r=a==null?void 0:a.task)==null?void 0:r.taskName)??((l=a==null?void 0:a.task)==null?void 0:l.name)??(a==null?void 0:a.task_name)??""},o=a=>{var r,l;return((r=a==null?void 0:a.taskGroup)==null?void 0:r.name)??((l=a==null?void 0:a.taskGroup)==null?void 0:l.groupName)??(a==null?void 0:a.task_group_name)??(a!=null&&a.task_group_uid?`Group #${a.task_group_uid}`:"")},i=new Map;for(const a of e){const r=n(a);i.has(r)||i.set(r,[]),i.get(r).push(a)}const c=[];for(const[a,r]of i.entries()){const l=r[0]||{},b=l.pic||"",m=o(l),f=l.time||((u=l==null?void 0:l.taskGroupDetail)==null?void 0:u.time)||((h=l==null?void 0:l.taskGroupDetail)!=null&&h.timeStart&&((p=l==null?void 0:l.taskGroupDetail)!=null&&p.timeEnd)?`${l.taskGroupDetail.timeStart} - ${l.taskGroupDetail.timeEnd}`:""),y=`
      <div class="d-flex align-items-center gap-2">
        ${r.length?`<button class="btn btn-link p-0 text-danger btn-del-activity" title="Hapus"
                 data-act="${$(r[0].uid??r[0].id)}">🗑️</button>`:""}
        <button class="btn btn-link p-0 text-primary btn-edit-activity" title="Edit"
          data-act="${$(((d=r[0])==null?void 0:d.uid)??((g=r[0])==null?void 0:g.id)??"")}">✏️</button>
        <span class="ms-1 d-inline-block rounded-circle" style="width:12px;height:12px;background:#dc3545;"></span>
      </div>`,E=`
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="h6 mb-1">${$(a)}${b?` – ${$(b)} (${$(b)})`:""}</div>
          ${f?`<div class="text-muted small"><span class="me-1">🕒</span>${$(f)}</div>`:""}
          ${m?`<div class="text-muted small">Pos: ${$(m)}</div>`:""}
        </div>
        ${y}
      </div>`,A=r.map(v=>{const D=s(v)||n(v);return`<span class="badge rounded-pill border border-danger text-danger fw-normal px-3 py-2 me-2 mb-2">
                  ${$(D)}
                </span>`}).join("");c.push(`
      <div class="pb-3 mb-3 border-bottom">
        ${E}
        <div class="mt-2 d-flex flex-wrap">
          ${A}
        </div>
      </div>
    `)}return`<div>${c.join("")}</div>`}function F(t,e,n){return!t||t.length===0?`
      <div class="alert alert-warning py-2 mb-2">Phase belum ada.</div>
      <button class="btn btn-sm btn-primary add-phase" data-group="${e}" data-date="${$(n)}">
        Tambah Phase
      </button>
    `:`
    <div class="accordion acc-clean phase-accordion">
      ${t.map(o=>{const i=o.phase_uid,c=o.link_uid||"",u=`ph-h-${e}-${i}-${c}`,h=`ph-c-${e}-${i}-${c}`,p=`act-panel-${e}-${i}-${c}`;return`
        <div class="accordion-item">
          <h2 class="accordion-header" id="${u}">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#${h}"
              aria-expanded="false"
              aria-controls="${h}">
              <span class="badge badge-muted me-2">Phase</span>
              ${$(o.phaseName??"(phase)")}
              ${o.phaseDate?`<small class="text-muted ms-2">(${$(o.phaseDate)})</small>`:""}
              <span class="ms-auto badge text-bg-secondary act-count" data-phase="${i}">...</span>
            </button>
          </h2>

          <div id="${h}" class="accordion-collapse collapse" aria-labelledby="${u}">
            <div class="accordion-body">
              <!-- Toolbar Phase -->
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="small text-muted">${o.phaseId?$(o.phaseId):""}</div>
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-primary btn-add-activity"
                    data-phase="${i}" data-group="${e}" data-link="${c}">
                    + Add Activity
                  </button>
                  <button class="btn btn-outline-secondary edit-phase"
                    data-link="${c}" data-group="${e}" data-date="${$(o.phaseDate)}" data-phase="${i}">
                    Edit
                  </button>
                  <button class="btn btn-outline-danger delete-phase" data-link="${c}" data-group="${e}">
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
                      data-bs-target="#${p}"
                      aria-expanded="false"
                      aria-controls="${p}"
                      data-phase="${i}">
                      Activities
                      <span class="ms-auto badge text-bg-secondary act-count" data-phase="${i}">...</span>
                    </button>
                  </h2>
                  <div id="${p}"
                       class="accordion-collapse collapse act-collapse"
                       data-phase="${i}"
                       data-group="${e}">
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
      `}).join("")}
    </div>
    <button class="btn btn-sm btn-primary mt-2 add-phase" data-group="${e}" data-date="${$(n)}">
      Tambah Phase
    </button>
  `}async function _(t){const e=t.dataset.group,n=t.dataset.date;t.dataset.loading="1",t.innerHTML='<div class="text-muted small">Memuat phase…</div>';try{const s=await R(L.listPhases(e,n));t.innerHTML=F(s.phases||[],e,n),t.dataset.loaded="1"}catch{t.innerHTML=`
      <div class="alert alert-danger py-2 mb-2">Gagal memuat phase.</div>
      <button class="btn btn-sm btn-primary add-phase" data-group="${e}" data-date="${$(n)}">
        Tambah Phase
      </button>
    `}finally{t.dataset.loading="0"}}async function j(t,e){var s;if(!e)return;const n=e.querySelector(".accordion-body");if(n&&e.dataset.loading!=="1"){e.dataset.loading="1",n.innerHTML='<div class="text-muted small">Memuat…</div>';try{let o=[];if(!X){const u=e.dataset.group||((s=e.closest(".phase-box"))==null?void 0:s.dataset.group)||null,h=u?`?schedule_group_uid=${encodeURIComponent(u)}`:"",p=await fetch(`/phase/${t}/activities${h}`,{headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest"}});let d=null;try{d=await p.json()}catch{}o=Array.isArray(d)?d:(d==null?void 0:d.data)||[]}n.innerHTML=U(o),e.dataset.loaded="1";const i=e.closest(".accordion-item");((i==null?void 0:i.querySelectorAll(".act-count[data-phase='"+t+"']"))||[]).forEach(u=>u.textContent=`${o.length} activity`)}catch(o){console.error(o),n.innerHTML='<div class="text-danger small">Gagal memuat activities.</div>'}finally{e.dataset.loading="0"}}}function W(){document.addEventListener("shown.bs.collapse",t=>{const e=t.target;if(e.classList.contains("act-collapse"))return;const n=e.querySelectorAll(".phase-box");n.length>0&&n.forEach(_)})}function K(){document.addEventListener("show.bs.collapse",t=>{const e=t.target;e.classList.contains("act-collapse")&&e.dataset.phase&&e.dataset.loaded!=="1"&&j(e.dataset.phase,e)}),document.addEventListener("click",async t=>{var i;const e=t.target.closest(".btn-del-activity");if(!e||(t.preventDefault(),!confirm("Hapus activity ini?")))return;const n=e.dataset.act,s=e.closest(".act-collapse"),o=(i=s==null?void 0:s.dataset)==null?void 0:i.phase;try{if(!(await fetch(`/phase/activities/${n}`,{method:"DELETE",headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":B}})).ok)throw new Error("Gagal menghapus activity.");s.dataset.loaded="0",await j(o,s)}catch(c){console.error(c),alert("Gagal menghapus activity.")}})}function J(){const t=document.getElementById("btnExpandAll"),e=document.getElementById("btnCollapseAll"),n=M();t&&n&&t.addEventListener("click",()=>{document.querySelectorAll(".accordion-collapse").forEach(s=>{s.classList.contains("show")||new n.Collapse(s,{toggle:!0}).show()})}),e&&n&&e.addEventListener("click",()=>{document.querySelectorAll(".accordion-collapse.show").forEach(s=>{new n.Collapse(s,{toggle:!0}).hide()})})}function V(){const t=document.getElementById("modalPickPhase");if(!t)return;const e=t.querySelector("#mp-mode"),n=t.querySelector("#mp-group"),s=t.querySelector("#mp-link"),o=t.querySelector("#mp-date"),i=t.querySelector("#mp-phase"),c=t.querySelector("#mp-title"),u=t.querySelector("#formPickPhase");async function h({mode:p,groupUid:d,date:g,linkUid:a=null,selectedPhaseUid:r=null}){e.value=p,n.value=d,s.value=a||"",o.value=g,c.textContent=p==="edit"?"Edit Phase":"Pilih Phase",i.innerHTML='<option value="">-- pilih --</option>';try{((await R(L.phaseOpts())).options||[]).forEach(m=>{const f=document.createElement("option");f.value=m.uid,f.textContent=(m.phaseOrder?m.phaseOrder+". ":"")+(m.phaseName||m.phaseId||"#"+m.uid),r&&+r==+m.uid&&(f.selected=!0),i.appendChild(f)})}catch{alert("Gagal memuat opsi phase.");return}const l=M();new l.Modal(t).show()}document.addEventListener("click",async p=>{var g;const d=p.target;if(d.matches(".add-phase")&&(p.preventDefault(),await h({mode:"create",groupUid:d.dataset.group,date:d.dataset.date})),d.matches(".edit-phase")&&(p.preventDefault(),await h({mode:"edit",groupUid:d.dataset.group,date:d.dataset.date,linkUid:d.dataset.link,selectedPhaseUid:d.dataset.phase})),d.matches(".delete-phase")){if(p.preventDefault(),!confirm("Hapus phase ini dari group?"))return;const a=d.dataset.link;try{await fetch(L.deleteLink(a),{method:"DELETE",headers:{Accept:"application/json","X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":B}});const r=((g=d.closest(".accordion-body"))==null?void 0:g.querySelector(".phase-box"))||document.querySelector(`.phase-box[data-group="${d.dataset.group}"]`);r&&await _(r)}catch{alert("Gagal menghapus.")}}}),u.addEventListener("submit",async p=>{p.preventDefault();const d=e.value,g=n.value,a=s.value,r=i.value,l=o.value;if(!r||!l){alert("Lengkapi pilihan phase & tanggal.");return}try{d==="create"?await P(L.storePhase(g),"POST",{phase_uid:+r,phaseDate:l,schedule_group_uid:+g}):await P(L.updateLink(a),"PUT",{phase_uid:+r,phaseDate:l,schedule_group_uid:+g}),M().Modal.getInstance(t).hide();const m=document.getElementById("group-content-"+g)||document.querySelector(`.phase-box[data-group="${g}"]`);m&&await _(m)}catch{alert("Gagal menyimpan phase.")}})}let H=!1;function G(){H||(H=!0,W(),V(),K(),J())}function N(){return window.bootstrap||window.Bootstrap||null}document.addEventListener("DOMContentLoaded",()=>{try{I==null||I()}catch(t){console.warn("bindCalendarClicks error",t)}try{C==null||C()}catch(t){console.warn("bindAssignGroupModal error",t)}try{G==null||G()}catch(t){console.warn("initPhases error",t)}(function(){const e=document.getElementById("tpl-check-all"),n=()=>Array.from(document.querySelectorAll(".tpl-check")),s=document.getElementById("btnTplDelete"),o=document.getElementById("tplBulkDeleteForm");function i(){if(!s)return;const c=n().some(u=>u.checked);s.classList.toggle("d-none",!c)}e&&e.addEventListener("change",()=>{n().forEach(c=>c.checked=e.checked),i()}),document.addEventListener("change",c=>{c.target.closest(".tpl-check")&&i()}),document.addEventListener("click",c=>{if(!c.target.closest("#btnTplDelete"))return;const h=n().filter(p=>p.checked).map(p=>p.value);h.length&&confirm(`Hapus ${h.length} template terpilih?`)&&o&&(o.querySelectorAll('input[name="ids[]"]').forEach(p=>p.remove()),h.forEach(p=>{const d=document.createElement("input");d.type="hidden",d.name="ids[]",d.value=p,o.appendChild(d)}),o.submit())}),document.addEventListener("click",async c=>{const u=c.target.closest(".js-open-template");if(!u)return;const h=u.dataset.id,p=document.getElementById("templateViewModal"),d=document.getElementById("templateViewModalContent"),g=N();if(!(!p||!d||!g))try{const a=`${window.location.origin}/schedule-template/${h}?ajax=1`,l=await(await fetch(a,{headers:{"X-Requested-With":"XMLHttpRequest"},credentials:"same-origin"})).text();d.innerHTML=l,new g.Modal(p).show()}catch(a){console.error(a),alert("Gagal memuat template.")}})})(),function(){const e=document.getElementById("modalAddActivity");if(!e)return;const n=N();if(!n)return;const s=new n.Modal(e),o=document.getElementById("maa-phase-uid"),i=document.getElementById("maa-schedule-group-uid"),c=document.getElementById("maa-groups"),u=document.getElementById("maa-details"),h=document.getElementById("maa-note"),p=document.getElementById("formAddActivity"),d=window.SCHEDULE_DATA||{},g=Array.isArray(d.taskGroups)?d.taskGroups:[],a=Array.isArray(d.taskDetailsFlat)?d.taskDetailsFlat:[];function r(){c&&(c.innerHTML="",g.forEach(b=>{const m=document.createElement("option");m.value=b.uid,m.textContent=b.name,c.appendChild(m)}))}function l(){if(!c||!u)return;const b=Array.from(c.selectedOptions).map(y=>parseInt(y.value,10));if(u.innerHTML="",!b.length){u.innerHTML='<div class="text-muted small">Pilih minimal 1 group untuk melihat detail.</div>';return}const m=a.filter(y=>b.includes(y.group_uid));if(!m.length){u.innerHTML='<div class="text-muted">Detail tidak ditemukan untuk group terpilih.</div>';return}const f={};m.forEach(y=>{var E;(f[E=y.group_uid]??(f[E]=[])).push(y)}),Object.entries(f).forEach(([y,E])=>{var w;const A=document.createElement("div");A.className="mb-2";const v=document.createElement("div");v.className="fw-semibold small mb-1";const D=((w=g.find(k=>String(k.uid)===String(y)))==null?void 0:w.name)||`Group #${y}`;v.textContent=D,A.appendChild(v),E.sort((k,x)=>(k.sortOrder??0)-(x.sortOrder??0)),E.forEach(k=>{const x=`dt-${k.uid}`,T=document.createElement("div");T.className="form-check form-check-inline me-3 mb-2";const S=document.createElement("input");S.className="form-check-input maa-detail",S.type="checkbox",S.id=x,S.value=k.uid,S.dataset.group=k.group_uid,S.dataset.task=k.task_uid||"",T.appendChild(S);const q=document.createElement("label");q.className="form-check-label",q.setAttribute("for",x),q.textContent=k.task_name||`Detail #${k.uid}`,T.appendChild(q),A.appendChild(T)}),u.appendChild(A)})}document.addEventListener("click",b=>{var E,A;const m=b.target.closest(".btn-add-activity");if(!m)return;const f=m.dataset.phase;let y=m.dataset.group;if(!y){const v=m.closest("[data-group]")||m.closest('[id^="group-content-"]');v&&(y=((E=v.dataset)==null?void 0:E.group)||((A=v.id)!=null&&A.startsWith("group-content-")?v.id.replace("group-content-",""):""))}if(!f){alert("Phase tidak dikenali.");return}if(!y){alert("Schedule Group tidak dikenali.");return}o&&(o.value=String(f)),i&&(i.value=String(y)),h&&(h.value=""),r(),c&&Array.from(c.options).forEach(v=>v.selected=!1),l(),s.show()}),c&&c.addEventListener("change",l),p&&p.addEventListener("submit",async b=>{var v,D;b.preventDefault();const m=o==null?void 0:o.value,f=i==null?void 0:i.value,y=((v=h==null?void 0:h.value)==null?void 0:v.trim())??"",E=Array.from((u==null?void 0:u.querySelectorAll(".maa-detail:checked"))??[]);if(!m){alert("Phase tidak dikenali.");return}if(!f){alert("Schedule Group tidak dikenali.");return}if(!E.length){alert("Pilih minimal satu Task Group Detail.");return}const A=E.map((w,k)=>({task_group_uid:parseInt(w.dataset.group,10),task_group_detail_uid:parseInt(w.value,10),task_uid:w.dataset.task?parseInt(w.dataset.task,10):null,sortOrder:k+1,activityNote:y||null}));try{const w=await fetch(`/phase/${m}/activities`,{method:"POST",headers:{"Content-Type":"application/json","X-Requested-With":"XMLHttpRequest","X-CSRF-TOKEN":(D=document.querySelector('meta[name="csrf-token"]'))==null?void 0:D.getAttribute("content")},body:JSON.stringify({schedule_group_uid:parseInt(f,10),activities:A})});if(!w.ok){let k="Gagal menyimpan activity.";try{const x=await w.json();x!=null&&x.message&&(k=x.message)}catch{}throw new Error(k)}s.hide(),window.location.reload()}catch(w){console.error(w),alert(w.message||"Gagal menyimpan activity.")}})}()});
