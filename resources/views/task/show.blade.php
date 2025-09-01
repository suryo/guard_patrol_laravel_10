@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Detail Task</h2>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $task->taskList->nama_task ?? ('Task #'.$task->id) }}</h4>
            <div class="mb-2">
                <span class="me-2">
                    @php
                        $badge = [
                          'pending'=>'bg-warning text-dark',
                          'in_progress'=>'bg-info text-dark',
                          'done'=>'bg-success',
                          'missed'=>'bg-danger'
                        ][$task->status] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $badge }}">{{ ucfirst(str_replace('_',' ',$task->status)) }}</span>
                </span>
                <span class="badge {{ ['low'=>'bg-success','medium'=>'bg-warning text-dark','high'=>'bg-danger'][$task->priority] ?? 'bg-secondary' }}">
                    {{ ucfirst($task->priority) }}
                </span>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-3">Petugas</dt>
                <dd class="col-sm-9">{{ $task->person->nama ?? ('#'.$task->person_id) }}</dd>

                <dt class="col-sm-3">Tanggal</dt>
                <dd class="col-sm-9">{{ $task->schedule_date }}</dd>

                <dt class="col-sm-3">Waktu</dt>
                <dd class="col-sm-9">{{ $task->start_time ?? '-' }} — {{ $task->end_time ?? '-' }}</dd>

                <dt class="col-sm-3">Lokasi</dt>
                <dd class="col-sm-9">{{ $task->location ?? '-' }}</dd>

                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9">{{ $task->notes ?? '-' }}</dd>

                <dt class="col-sm-3">Dibuat</dt>
                <dd class="col-sm-9">{{ optional($task->created_at)->format('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Diperbarui</dt>
                <dd class="col-sm-9">{{ optional($task->updated_at)->format('d M Y H:i') }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('tb_task.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('tb_task.edit', $task->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('tb_task.destroy', $task->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('Yakin hapus task ini?')" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
