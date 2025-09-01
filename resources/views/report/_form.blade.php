@csrf
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">reportId</label>
    <input type="text" name="reportId" maxlength="20"
      class="form-control @error('reportId') is-invalid @enderror"
      value="{{ old('reportId', $report->reportId ?? '') }}" required>
    @error('reportId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">personId</label>
    <input type="text" name="personId" maxlength="2"
      class="form-control @error('personId') is-invalid @enderror"
      value="{{ old('personId', $report->personId ?? '') }}" required>
    @error('personId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">activityId</label>
    <input type="text" name="activityId" maxlength="20"
      class="form-control @error('activityId') is-invalid @enderror"
      value="{{ old('activityId', $report->activityId ?? '') }}">
    @error('activityId') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">checkpointName</label>
    <input type="text" name="checkpointName" maxlength="60"
      class="form-control @error('checkpointName') is-invalid @enderror"
      value="{{ old('checkpointName', $report->checkpointName ?? '') }}">
    @error('checkpointName') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">reportDate</label>
    <input type="date" name="reportDate"
      class="form-control @error('reportDate') is-invalid @enderror"
      value="{{ old('reportDate', isset($report->reportDate)? \Carbon\Carbon::parse($report->reportDate)->format('Y-m-d') : '') }}">
    @error('reportDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">reportTime</label>
    <input type="time" name="reportTime"
      class="form-control @error('reportTime') is-invalid @enderror"
      value="{{ old('reportTime', isset($report->reportTime)? \Carbon\Carbon::parse($report->reportTime)->format('H:i:s') : '') }}">
    @error('reportTime') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Latitude</label>
    <input type="number" step="0.000001" name="reportLatitude"
      class="form-control @error('reportLatitude') is-invalid @enderror"
      value="{{ old('reportLatitude', $report->reportLatitude ?? '') }}">
    @error('reportLatitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Longitude</label>
    <input type="number" step="0.000001" name="reportLongitude"
      class="form-control @error('reportLongitude') is-invalid @enderror"
      value="{{ old('reportLongitude', $report->reportLongitude ?? '') }}">
    @error('reportLongitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">reportNote</label>
    <textarea name="reportNote" rows="3"
      class="form-control @error('reportNote') is-invalid @enderror"
    >{{ old('reportNote', $report->reportNote ?? '') }}</textarea>
    @error('reportNote') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-primary">Simpan</button>
  <a href="{{ route('report.index') }}" class="btn btn-secondary">Batal</a>
</div>
