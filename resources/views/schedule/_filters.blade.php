<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input name="q" class="form-control" placeholder="Cari scheduleId / personId / checkpoint" value="{{ $q }}">
  </div>
  <div class="col-md-3">
    <input type="date" name="d" class="form-control" value="{{ $date }}" placeholder="scheduleDate">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-primary w-100">Filter</button>
  </div>
  @if ($q || $date)
    <div class="col-md-2">
      <a class="btn btn-outline-secondary w-100" href="{{ route('schedule.index') }}">Reset</a>
    </div>
  @endif
</form>
