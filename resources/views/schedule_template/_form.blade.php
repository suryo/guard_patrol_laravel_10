{{-- resources/views/schedule_template/_form.blade.php --}}
@csrf
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Template ID =</label>
    <input type="text" name="templateId" class="form-control"
           value="{{ old('templateId', $tpl->templateId ?? '') }}">
  </div>
  <div class="col-md-8">
    <label class="form-label">Template Name <span class="text-danger">*</span></label>
    <input type="text" name="templateName" class="form-control" required
           value="{{ old('templateName', $tpl->templateName ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">Person ID</label>
    <input type="text" name="personId" class="form-control"
           value="{{ old('personId', $tpl->personId ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Time Start</label>
    <input type="datetime-local" name="timeStart" class="form-control"
           value="{{ old('timeStart', isset($tpl->timeStart) ? \Illuminate\Support\Carbon::parse($tpl->timeStart)->format('Y-m-d\TH:i') : '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Time End</label>
    <input type="datetime-local" name="timeEnd" class="form-control"
           value="{{ old('timeEnd', isset($tpl->timeEnd) ? \Illuminate\Support\Carbon::parse($tpl->timeEnd)->format('Y-m-d\TH:i') : '') }}">
  </div>

  <div class="col-12">
    <label class="form-label">Task Group Details</label>
    <select name="task_detail_uids[]" class="form-select" multiple size="8">
      @foreach($taskDetails as $td)
        <option value="{{ $td->uid }}"
          @selected(collect(old('task_detail_uids', isset($tpl) ? $tpl->taskDetails->pluck('uid')->all() : []))->contains($td->uid))>
          {{ $td->groupName ?? 'Detail' }} — {{ $td->uid }}
        </option>
      @endforeach
    </select>
    <div class="form-text">Gunakan Ctrl/Shift untuk memilih beberapa. Urutan mengikuti urutan pilihan saat dikirim (atau drag list di UI jika nanti ditambah).</div>
  </div>
</div>
