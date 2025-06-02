@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Payment Types List</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPaymentTypeModal">
                    + Create New
                </button>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    Show 
                    <select class="form-control mx-2" style="width: 70px;" id="entriesPerPage">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    entries
                </div>
                <div class="btn-group">
                    <button class="btn btn-info btn-sm" id="copyBtn">Copy</button>
                    <button class="btn btn-success btn-sm" id="excelBtn">Excel</button>
                    <button class="btn btn-danger btn-sm" id="pdfBtn">PDF</button>
                    <button class="btn btn-secondary btn-sm" id="printBtn">Print</button>
                    <button class="btn btn-warning btn-sm" id="csvBtn">CSV</button>
                    <button class="btn btn-primary btn-sm" id="columnsBtn">Columns</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="paymentTypesTable">
                    <thead>
                        <tr>
                            <th>Payment Type Name</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentTypes as $type)
                        <tr>
                            <td>{{ $type->name }}</td>
                            <td>
                                <span class="badge badge-{{ $type->status ? 'success' : 'danger' }}">
                                    {{ $type->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item edit-type" href="#" data-id="{{ $type->id }}">Edit</a>
                                    <a class="dropdown-item delete-type text-danger" href="#" data-id="{{ $type->id }}">Delete</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No payment types found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $paymentTypes->firstItem() ?? 0 }} to {{ $paymentTypes->lastItem() ?? 0 }} of {{ $paymentTypes->total() ?? 0 }} entries</div>
                {{ $paymentTypes->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Type Modal -->
<div class="modal fade" id="addPaymentTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Type</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="addPaymentTypeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Payment Type Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#paymentTypesTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print', 'csv'
        ],
        pageLength: 10,
        searching: true,
        ordering: true
    });

    // Connect custom buttons to DataTable functions
    $('#copyBtn').click(function() { table.button('copy').trigger(); });
    $('#excelBtn').click(function() { table.button('excel').trigger(); });
    $('#pdfBtn').click(function() { table.button('pdf').trigger(); });
    $('#printBtn').click(function() { table.button('print').trigger(); });
    $('#csvBtn').click(function() { table.button('csv').trigger(); });

    // Entries per page change handler
    $('#entriesPerPage').change(function() {
        table.page.len($(this).val()).draw();
    });

    // Add Payment Type
    $('#addPaymentTypeForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("settings.payment-types.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addPaymentTypeModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error occurred while saving payment type');
            }
        });
    });

    // Delete Payment Type
    $('.delete-type').click(function() {
        if (confirm('Are you sure you want to delete this payment type?')) {
            let id = $(this).data('id');
            $.ajax({
                url: `/settings/payment-types/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endpush