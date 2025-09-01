@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Edit Task</h2>

    <form action="{{ route('tb_task.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Task List</label>
                <select name="task_list_id" class="form-select @error('task_list_id') is-invalid @enderror" required>
                    @foreach($taskLists as $id=>$name)
                        <option value="{{ $id }}" {{ old('task_list_id',$task->task_list_id)==$id?'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('task_list_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Petugas (Person)</label>
                <select name="person_id" class="form-select @error('person_id') is-invalid @enderror" required>
                    @foreach($people as $id=>$name)
                        <option value="{{ $id }}" {{ old('person_id',$task->person_id)==$id?'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('person_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="schedule_date" value="{{ old('schedule_date',$task->schedule_date) }}" class="form-control @error('schedule_date') is-invalid @enderror" required>
                @error('schedule_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Mulai</label>
                <input type="time" name="start_time" value="{{ old('start_time',$task->start_time) }}" class="form-control @error('start_time') is-invalid @enderror">
                @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Selesai</label>
                <input type="time" name="end_time" value="{{ old('end_time',$task->end_time) }}" class="form-control @error('end_time') is-invalid @enderror">
                @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(['pending'=>'Pending','in_progress'=>'In Progress','done'=>'Done','missed'=>'Missed'] as $k=>$v)
                        <option value="{{ $k }}" {{ old('status',$task->status)==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Prioritas</label>
                <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                    @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High'] as $k=>$v)
                        <option value="{{ $k }}" {{ old('priority',$task->priority)==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" value="{{ old('location',$task->location) }}" class="form-control @error('location') is-invalid @enderror">
                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Catatan</label>
                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes',$task->notes) }}</textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-success">Update</button>
            <a href="{{ route('tb_task.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
