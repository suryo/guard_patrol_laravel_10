@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Tambah Task</h2>

    <form action="{{ route('tb_task.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Task List</label>
                <select name="task_list_id" class="form-select @error('task_list_id') is-invalid @enderror" required>
                    <option value="">-- pilih --</option>
                    @foreach($taskLists as $id=>$name)
                        <option value="{{ $id }}" {{ old('task_list_id')==$id?'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('task_list_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Petugas (Person)</label>
                <select name="person_id" class="form-select @error('person_id') is-invalid @enderror" required>
                    <option value="">-- pilih --</option>
                    @foreach($people as $id=>$name)
                        <option value="{{ $id }}" {{ old('person_id')==$id?'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('person_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="schedule_date" value="{{ old('schedule_date') }}" class="form-control @error('schedule_date') is-invalid @enderror" required>
                @error('schedule_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Mulai</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" class="form-control @error('start_time') is-invalid @enderror">
                @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Selesai</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}" class="form-control @error('end_time') is-invalid @enderror">
                @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(['pending'=>'Pending','in_progress'=>'In Progress','done'=>'Done','missed'=>'Missed'] as $k=>$v)
                        <option value="{{ $k }}" {{ old('status','pending')==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Prioritas</label>
                <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                    @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High'] as $k=>$v)
                        <option value="{{ $k }}" {{ old('priority','medium')==$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="contoh: Pos 1 / Lantai 2">
                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Catatan</label>
                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Detail instruksi / hasil patroli">{{ old('notes') }}</textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('tb_task.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
