@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">templateId</label>
    <input type="text" name="templateId" maxlength="11"
      class="form-control @error('templateId') is-invalid @enderror"
      value="{{ old('templateId', $schedule_template->templateId ?? '') }}" required>
    @error('templateId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-5">
    <label class="form-label">templateName</label>
    <input type="text" name="templateName" maxlength="60"
      class="form-control @error('templateName') is-invalid @enderror"
      value="{{ old('templateName', $schedule_template->templateName ?? '') }}" required>
    @error('templateName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">userName</label>
    <input type="text" name="userName" maxlength="60"
      class="form-control @error('userName') is-invalid @enderror"
      value="{{ old('userName', $schedule_template->userName ?? '') }}">
    @error('userName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">templatePhase</label>
    <input type="number" min="0" max="255" name="templatePhase"
      class="form-control @error('templatePhase') is-invalid @enderror"
      value="{{ old('templatePhase', $schedule_template->templatePhase ?? 0) }}">
    @error('templatePhase') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">templateMapping</label>
    <input type="number" min="0" max="255" name="templateMapping"
      class="form-control @error('templateMapping') is-invalid @enderror"
      value="{{ old('templateMapping', $schedule_template->templateMapping ?? 0) }}">
    @error('templateMapping') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">templatePerson</label>
    <input type="number" min="0" max="255" name="templatePerson"
      class="form-control @error('templatePerson') is-invalid @enderror"
      value="{{ old('templatePerson', $schedule_template->templatePerson ?? 0) }}">
    @error('templatePerson') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">templateStart</label>
    <input type="datetime-local" name="templateStart"
      class="form-control @error('templateStart') is-invalid @enderror"
      value="{{ old('templateStart', isset($schedule_template->templateStart)? \Carbon\Carbon::parse($schedule_template->templateStart)->format('Y-m-d\TH:i') : '') }}">
    @error('templateStart') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">templateEnd</label>
    <input type="datetime-local" name="templateEnd"
      class="form-control @error('templateEnd') is-invalid @enderror"
      value="{{ old('templateEnd', isset($schedule_template->templateEnd)? \Carbon\Carbon::parse($schedule_template->templateEnd)->format('Y-m-d\TH:i') : '') }}">
    @error('templateEnd') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">templateTask</label>
    <textarea name="templateTask" rows="4"
      class="form-control @error('templateTask') is-invalid @enderror"
      placeholder="Bisa JSON / daftar tugas">{{ old('templateTask', $schedule_template->templateTask ?? '') }}</textarea>
    @error('templateTask') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('schedule-template.index') }}" class="btn btn-secondary">Batal</a>
</div>
