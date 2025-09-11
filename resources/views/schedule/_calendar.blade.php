<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div class="fw-semibold">{{ $month->isoFormat('MMMM, YYYY') }}</div>
    <div class="btn-group">
      <a class="btn btn-sm btn-outline-secondary"
         href="{{ route('schedule.index', array_filter(['q' => $q, 'd' => $date, 'm' => $month->copy()->subMonth()->format('Y-m')])) }}">‹</a>
      <a class="btn btn-sm btn-outline-secondary"
         href="{{ route('schedule.index', array_filter(['q' => $q, 'd' => $date, 'm' => $month->copy()->addMonth()->format('Y-m')])) }}">›</a>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0 table-bordered align-middle text-center">
        <thead class="table-light">
        <tr>
          <th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th>
        </tr>
        </thead>
        <tbody>
        @for ($day = $startWeek->copy(); $day <= $endWeek; $day->addDay())
          @if ($day->isMonday()) <tr> @endif
          @php
            $isOtherMonth = !$day->isSameMonth($monthStart);
            $isSelected   = $day->toDateString() === $date;
            $hasSched     = $schedules->where('scheduleDate', $day->toDateString())->count() > 0;
          @endphp
          <td class="{{ $isOtherMonth ? 'text-muted bg-light' : '' }} {{ $isSelected ? 'table-primary fw-semibold' : '' }}"
              style="height:68px; vertical-align:top;">
            <div class="d-flex justify-content-between">
              <small>{{ $day->day }}</small>
              @if ($hasSched && !$isSelected)
                <span class="badge bg-secondary">•</span>
              @endif
            </div>
            <div class="mt-2 position-relative">
              <button type="button" class="stretched-link btn p-0 border-0 bg-transparent assign-group-btn"
                      data-date="{{ $day->toDateString() }}"></button>
            </div>
          </td>
          @if ($day->isSunday()) </tr> @endif
        @endfor
        </tbody>
      </table>
    </div>
  </div>
</div>
