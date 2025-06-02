@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header border-0 bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Units List</h5>
                    <small class="text-muted">View/Search Units</small>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                    + New Unit
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="p-3 border-bottom">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            <span class="text-sm me-2">Show</span>
                            <select class="form-select form-select-sm" style="width: 60px;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-sm ms-2">entries</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-center">
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary btn-sm px-3">Copy</button>
                                <button class="btn btn-outline-secondary btn-sm px-3">Excel</button>
                                <button class="btn btn-outline-secondary btn-sm px-3">PDF</button>
                                <button class="btn btn-outline-secondary btn-sm px-3">Print</button>
                                <button class="btn btn-outline-secondary btn-sm px-3">CSV</button>
                                <button class="btn btn-outline-secondary btn-sm px-3">Columns</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <input type="search" class="form-control form-control-sm" placeholder="Search..." style="width: 200px;">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="px-3 bg-light">Unit Name</th>
                            <th class="px-3 bg-light">Description</th>
                            <th class="px-3 bg-light">Status</th>
                            <th class="px-3 bg-light text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units ?? [] as $unit)
                        <tr>
                            <td class="px-3">{{ $unit->name }}</td>
                            <td class="px-3">{{ $unit->description }}</td>
                            <td class="px-3">
                                <span class="badge rounded-pill bg-{{ $unit->status ? 'success' : 'danger' }} bg-opacity-75">
                                    {{ $unit->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-3 text-end">
                                <button class="btn btn-outline-secondary btn-sm px-3" onclick="editUnit({{ $unit->id }})">Action</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-secondary">No units found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-secondary small">
                    Showing 1 to {{ count($units ?? []) }} of {{ count($units ?? []) }} entries
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Keep existing modals -->
@endsection

@push('scripts')
<script>
function editUnit(id) {
    $(`#editUnitModal${id}`).modal('show');
}

function deleteUnit(id) {
    if (confirm('Are you sure you want to delete this unit?')) {
        fetch(`/settings/units/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              } else {
                  alert('Error deleting unit');
              }
          });
    }
}
</script>
@endpush