@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Tasks</h2>
        <a href="{{ route('tb_task.create') }}" class="btn btn-primary">+ Tambah Task</a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-12 col-md-3">
            <input type="date" name="date" value="{{ request('date') }}" class="form-control" placeholder="Tanggal">
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                @foreach(['pending'=>'Pending','in_progress'=>'In Progress','done'=>'Done','missed'=>'Missed'] as $k=>$v)
                    <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="priority" class="form-select">
                <option value="">-- Semua Prioritas --</option>
                @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High'] as $k=>$v)
                    <option value="{{ $k }}" {{ request('priority')===$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <button class="btn btn-outline-secondary w-100">Filter</button>
        </div>
        <div class="col-6 col-md-1">
            <a href="{{ route('tb_task.index') }}" class="btn btn-outline-dark w-100">Reset</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Petugas</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Prioritas</th>
                    <th>Lokasi</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $i => $task)
                <tr>
                    <td>{{ ($tasks->currentPage()-1)*$tasks->perPage() + $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold">{{ $task->taskList->nama_task ?? ('#'.$task->task_list_id) }}</div>
                        <div class="text-muted small">{{ \Illuminate\Support\Str::limit($task->notes, 60) }}</div>
                    </td>
                    <td>{{ $task->person->nama ?? ('#'.$task->person_id) }}</td>
                    <td>{{ $task->schedule_date }}</td>
                    <td>{{ $task->start_time ?? '-' }} — {{ $task->end_time ?? '-' }}</td>
                    <td>
                        @php
                            $badge = [
                              'pending'=>'bg-warning text-dark',
                              'in_progress'=>'bg-info text-dark',
                              'done'=>'bg-success',
                              'missed'=>'bg-danger'
                            ][$task->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $badge }}">{{ ucfirst(str_replace('_',' ',$task->status)) }}</span>
                    </td>
                    <td>
                        @php
                            $pb = ['low'=>'bg-success','medium'=>'bg-warning text-dark','high'=>'bg-danger'][$task->priority] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $pb }}">{{ ucfirst($task->priority) }}</span>
                    </td>
                    <td>{{ $task->location ?? '-' }}</td>
                    <td>
                        <a href="{{ route('tb_task.show',$task->id) }}" class="btn btn-sm btn-secondary">Detail</a>
                        <a href="{{ route('tb_task.edit',$task->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('tb_task.destroy',$task->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus task ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center">Data kosong</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tasks->withQueryString()->links() }}
</div>
@endsection
