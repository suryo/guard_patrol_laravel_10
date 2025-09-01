@extends('layouts.app')
@section('content')
<h5>Detail Schedule Template</h5>

<div class="card">
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">UID</dt><dd class="col-sm-9">{{ $schedule_template->uid }}</dd>
      <dt class="col-sm-3">templateId</dt><dd class="col-sm-9">{{ $schedule_template->templateId }}</dd>
      <dt class="col-sm-3">templateName</dt><dd class="col-sm-9">{{ $schedule_template->templateName }}</dd>
      <dt class="col-sm-3">templatePhase</dt><dd class="col-sm-9">{{ $schedule_template->templatePhase }}</dd>
      <dt class="col-sm-3">templateMapping</dt><dd class="col-sm-9">{{ $schedule_template->templateMapping }}</dd>
      <dt class="col-sm-3">templatePerson</dt><dd class="col-sm-9">{{ $schedule_template->templatePerson }}</dd>
      <dt class="col-sm-3">templateStart</dt><dd class="col-sm-9">{{ $schedule_template->templateStart }}</dd>
      <dt class="col-sm-3">templateEnd</dt><dd class="col-sm-9">{{ $schedule_template->templateEnd }}</dd>
      <dt class="col-sm-3">templateTask</dt><dd class="col-sm-9"><pre class="mb-0">{{ $schedule_template->templateTask }}</pre></dd>
      <dt class="col-sm-3">userName</dt><dd class="col-sm-9">{{ $schedule_template->userName }}</dd>
    </dl>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <a href="{{ route('schedule-template.edit',$schedule_template) }}" class="btn btn-warning">Edit</a>
  <a href="{{ route('schedule-template.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
