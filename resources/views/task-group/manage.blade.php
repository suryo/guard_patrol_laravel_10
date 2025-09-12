{{-- resources/views/task-group/manage.blade.php --}}
@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  .pane { height: calc(100vh - 190px); overflow: auto; }
  .chip {
    display: inline-flex; align-items: center; gap:.35rem;
    border: 1px solid #dee2e6; border-radius: 999px; padding:.25rem .6rem; font-size:.85rem;
    margin: .2rem .3rem .2rem 0;
  }
  .chip .x { cursor: pointer; font-weight: 700; opacity: .6 }
  .chip .x:hover { opacity: 1 }
  .row-hover:hover { background: #f8f9fa; }
  .stick-header { position: sticky; top: 0; background: #fff; z-index: 5; }
</style>

<div class="row g-3">
  {{-- LEFT: TASK LIST --}}
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header stick-header d-flex justify-content-between align-items-center">
        <div class="fs-5 fw-semibold">Task List</div>
        <div class="d-flex align-items-center gap-2">
          <div class="input-group input-group-sm" style="width: 260px;">
            <input id="qTask" class="form-control" placeholder="Cari task...">
            <button class="btn btn-outline-secondary" id="btnSearchTask">Cari</button>
          </div>

          {{-- tombol modal create TASK --}}
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateTask" title="Tambah Task">
            <i class="bi bi-plus-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="pane">
          <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:48px;"></th>
                <th>Task Name</th>
                <th style="width:180px;">Last Update</th>
              </tr>
            </thead>
            <tbody id="taskBody"></tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">
        <span id="taskCount">0</span> item dipilih
      </div>
    </div>
  </div>

  {{-- RIGHT: TASK GROUP --}}
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header stick-header d-flex justify-content-between align-items-center">
        <div class="fs-5 fw-semibold">Task Group</div>
        <div class="d-flex align-items-center gap-2">
          <div class="input-group input-group-sm" style="width: 260px;">
            <input id="qGroup" class="form-control" placeholder="Cari group...">
            <button class="btn btn-outline-secondary" id="btnSearchGroup">Cari</button>
          </div>

          {{-- tombol modal create GROUP --}}
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateGroup" title="Tambah Group">
            <i class="bi bi-plus-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="pane">
          <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:48px;"></th>
                <th>Group Name</th>
                <th>Task</th>
                <th style="width:54px;"></th>
              </tr>
            </thead>
            <tbody id="groupBody"></tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted">
        Tips: centang task di kiri → klik ikon <strong>+</strong> pada baris group untuk menambahkan
      </div>
    </div>
  </div>
</div>

{{-- === include MODALS (file terpisah) === --}}
@include('task._modal_create')
@include('task-group._modal_create')

@push('scripts')
<script>
const routes = {
  tasks:  '{{ url('ajax/tasks') }}',
  groups: '{{ url('ajax/task-groups') }}',
  attach: (uid) => '{{ url('ajax/task-group') }}/' + uid + '/attach',
  detach: (gid, tid) => '{{ url('ajax/task-group') }}/' + gid + '/detach/' + tid,

  // store bawaan resource
  storeTask: '{{ route('task.store') }}',
  storeGroup: '{{ route('task-group.store') }}',
};
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const state = { selectedTaskUids: new Set(), qTask: '', qGroup: '' };

document.addEventListener('DOMContentLoaded', () => {
  loadTasks();
  loadGroups();

  document.getElementById('btnSearchTask').addEventListener('click', () => {
    state.qTask = document.getElementById('qTask').value.trim();
    loadTasks();
  });
  document.getElementById('btnSearchGroup').addEventListener('click', () => {
    state.qGroup = document.getElementById('qGroup').value.trim();
    loadGroups();
  });

  // handle submit modal CREATE TASK (AJAX)
  const formTask = document.getElementById('formCreateTask');
  formTask.addEventListener('submit', async (e) => {
    e.preventDefault();
    await submitCreate(formTask, routes.storeTask, () => {
      bootstrap.Modal.getInstance(document.getElementById('modalCreateTask')).hide();
      formTask.reset();
      loadTasks();
    }, 'task');
  });

  // handle submit modal CREATE GROUP (AJAX)
  const formGroup = document.getElementById('formCreateGroup');
  formGroup.addEventListener('submit', async (e) => {
    e.preventDefault();
    await submitCreate(formGroup, routes.storeGroup, () => {
      bootstrap.Modal.getInstance(document.getElementById('modalCreateGroup')).hide();
      formGroup.reset();
      loadGroups();
    }, 'group');
  });
});

function fmtTime(t){
  if(!t) return '-';
  const dt = new Date(String(t).replace(' ', 'T'));
  if(isNaN(dt)) return t;
  return dt.toISOString().slice(0,16).replace('T',' ');
}

async function loadTasks(){
  const url = new URL(routes.tasks, window.location.origin);
  if(state.qTask) url.searchParams.set('q', state.qTask);
  const res = await fetch(url, {headers:{'Accept':'application/json'}});
  const json = await res.json();
  const tbody = document.getElementById('taskBody');
  tbody.innerHTML = '';
  (json.data || []).forEach(row => {
    const tr = document.createElement('tr');
    tr.className = 'row-hover';
    tr.innerHTML = `
      <td class="text-center">
        <input type="checkbox" class="form-check-input select-task" value="${row.uid}">
      </td>
      <td>
        <div class="fw-semibold">${escapeHtml(row.taskName || '(no name)')}</div>
        <div class="small text-muted">${escapeHtml(row.taskId || '')}</div>
      </td>
      <td><small class="text-muted">${fmtTime(row.lastUpdated)}</small></td>
    `;
    tbody.appendChild(tr);
  });

  document.querySelectorAll('.select-task').forEach(cb=>{
    cb.checked = state.selectedTaskUids.has(parseInt(cb.value));
    cb.addEventListener('change', (e)=>{
      const id = parseInt(e.target.value);
      if(e.target.checked) state.selectedTaskUids.add(id);
      else state.selectedTaskUids.delete(id);
      document.getElementById('taskCount').innerText = state.selectedTaskUids.size;
    });
  });
  document.getElementById('taskCount').innerText = state.selectedTaskUids.size;
}

async function loadGroups(){
  const url = new URL(routes.groups, window.location.origin);
  if(state.qGroup) url.searchParams.set('q', state.qGroup);
  const res = await fetch(url, {headers:{'Accept':'application/json'}});
  const json = await res.json();
  const tbody = document.getElementById('groupBody');
  tbody.innerHTML = '';
  (json.data || []).forEach(g=>{
    const tr = document.createElement('tr');
    tr.className = 'row-hover';
    tr.innerHTML = `
      <td class="text-center">
        <input type="checkbox" class="form-check-input select-group" value="${g.uid}">
      </td>
      <td>
        <div class="fw-semibold">${escapeHtml(g.groupName)}</div>
        <div class="small text-muted">${escapeHtml(g.groupId)} · ${fmtTime(g.lastUpdated)}</div>
      </td>
      <td>
        <div>
          ${(g.tasks||[]).map(t => `
            <span class="chip" data-gid="${g.uid}" data-tid="${t.task_uid}">
              ${escapeHtml(t.taskName || t.taskId || ('#'+t.task_uid))}
              <span class="x" title="Hapus">&times;</span>
            </span>
          `).join('')}
        </div>
      </td>
      <td class="text-end">
        <button class="btn btn-sm btn-outline-primary attach-btn" title="Tambahkan task terpilih ke group">
          <i class="bi bi-plus-lg"></i>
        </button>
      </td>
    `;
    tbody.appendChild(tr);

    tr.querySelectorAll('.chip .x').forEach(x=>{
      x.addEventListener('click', async (e)=>{
        const chip = e.target.closest('.chip');
        const gid = chip.getAttribute('data-gid');
        const tid = chip.getAttribute('data-tid');
        if(!confirm('Hapus task dari group?')) return;
        const ok = await detachTask(gid, tid);
        if(ok) chip.remove();
      });
    });

    tr.querySelector('.attach-btn').addEventListener('click', async ()=>{
      if(state.selectedTaskUids.size === 0){
        alert('Pilih task di sisi kiri terlebih dahulu.');
        return;
      }
      const arr = Array.from(state.selectedTaskUids);
      const ok = await attachTasks(g.uid, arr);
      if(ok){ await loadGroups(); }
    });
  });
}

async function attachTasks(groupUid, taskUids){
  try{
    const res = await fetch(routes.attach(groupUid), {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token, 'Accept':'application/json' },
      body: JSON.stringify({ task_uids: taskUids })
    });
    if(!res.ok){
      const j = await res.json().catch(()=>({message: 'Gagal attach'}));
      alert(j.message || 'Gagal attach');
      return false;
    }
    return true;
  }catch{ alert('Error jaringan'); return false; }
}

async function detachTask(groupUid, taskUid){
  try{
    const res = await fetch(routes.detach(groupUid, taskUid), {
      method:'DELETE', headers:{ 'X-CSRF-TOKEN': token, 'Accept':'application/json' }
    });
    return res.ok;
  }catch{ return false; }
}

async function submitCreate(formEl, url, onOk, type){
  const btn = formEl.querySelector('button[type="submit"]');
  const errBox = formEl.querySelector('.ajax-errors');
  errBox.innerHTML = ''; errBox.classList.add('d-none');
  btn.disabled = true;

  try{
    const fd = new FormData(formEl);
    const res = await fetch(url, { method:'POST', headers:{ 'X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: fd });
    if(res.ok){
      onOk?.();
    }else{
      const j = await res.json().catch(()=>({message:'Gagal menyimpan'}));
      const errors = j.errors || { general:[j.message || 'Gagal menyimpan'] };
      renderErrors(errBox, errors);
    }
  }catch(e){
    renderErrors(errBox, { general:['Terjadi kesalahan jaringan.'] });
  }finally{
    btn.disabled = false;
  }
}

function renderErrors(box, errors){
  const ul = document.createElement('ul');
  ul.className = 'mb-0';
  Object.keys(errors).forEach(k=>{
    (Array.isArray(errors[k]) ? errors[k] : [errors[k]]).forEach(msg=>{
      const li = document.createElement('li'); li.textContent = msg; ul.appendChild(li);
    });
  });
  box.innerHTML = '';
  box.appendChild(ul);
  box.classList.remove('d-none');
}

function escapeHtml(s){
  if(s===null||s===undefined) return '';
  return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}
</script>
@endpush
@endsection
