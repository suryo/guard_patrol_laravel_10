@extends('layouts.app')

@section('content')
<style>
    /* Card & table look */
    .cp-card { border:1px solid #e5e7eb; border-radius:.5rem; background:#fff; }
    .cp-card-header { padding:.9rem 1rem; border-bottom:1px solid #e5e7eb; font-weight:600; }
    .cp-card-body { padding:0; }
    .table-wrap {
        max-height: 68vh;               /* tinggi area scroll */
        overflow-y: auto;
    }
    table.cp-table { width:100%; margin:0; }
    table.cp-table thead th {
        position: sticky; top: 0;
        background:#f8fafc;             /* abu muda */
        z-index: 1;
        font-weight:600;
        font-size:.9rem;
        border-bottom:1px solid #e5e7eb;
    }
    table.cp-table th, table.cp-table td {
        padding:.7rem .85rem;
        border-bottom:1px solid #f1f5f9;
        vertical-align: middle;
        white-space: nowrap;
    }
    .text-dim { color:#64748b; font-size:.9rem; }
    .truncate { max-width: 360px; overflow: hidden; text-overflow: ellipsis; }
    .w-checkbox { width: 44px; }
</style>

<div class="cp-card">
    <div class="cp-card-header">
        Checkpoint List
    </div>

    <div class="cp-card-body">
        {{-- opsional: baris pencarian kecil --}}
        <div class="px-3 py-2 border-bottom" style="display:flex; gap:.5rem; align-items:center;">
            <form class="d-flex gap-2" style="flex:1;">
                <input name="q" class="form-control form-control-sm" placeholder="Search name / NFC / address"
                       value="{{ request('q') }}">
                <button class="btn btn-sm btn-outline-primary">Search</button>
                @if (request()->filled('q'))
                    <a href="{{ route('checkpoint.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                @endif
            </form>

            <a class="btn btn-sm btn-primary" href="{{ route('checkpoint.create') }}">+ New</a>
        </div>

        <div class="table-wrap">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th class="w-checkbox">
                            <input type="checkbox" id="check-all">
                        </th>
                        <th>Checkpoint Name</th>
                        <th>Checkpoint NFC</th>
                        <th>Last Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $i)
                        <tr>
                            <td class="w-checkbox">
                                <input type="checkbox" class="row-check" value="{{ $i->uid }}">
                            </td>
                            <td class="truncate">
                                <a href="{{ route('checkpoint.show', $i->uid) }}" class="text-decoration-none">
                                    {{ $i->checkpointName }}
                                </a>
                            </td>
                            <td class="text-dim">{{ strtoupper($i->checkpointId) }}</td>
                            <td class="text-dim">
                                {{ optional($i->lastUpdated)->format('d/m/Y (H:i)') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        <div class="px-3 py-2">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
</div>

<script>
    // checkbox select all
    document.addEventListener('DOMContentLoaded', () => {
        const checkAll = document.getElementById('check-all');
        const rowChecks = document.querySelectorAll('.row-check');
        if (checkAll) {
            checkAll.addEventListener('change', () => {
                rowChecks.forEach(cb => cb.checked = checkAll.checked);
            });
        }
    });
</script>
@endsection
