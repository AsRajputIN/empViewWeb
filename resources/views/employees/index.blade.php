@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Employee Management
                    </h5>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="fas fa-plus"></i> Add New Employee
                    </button>
                </div>

                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, or position...">
                        </div>
                        <div class="col-md-3">
                            <select id="statusFilter" class="form-control">
                                <option value="all">All Status</option>
                                <option value="active">Active Only</option>
                                <option value="inactive">Inactive Only</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="yearsFilter" class="form-control">
                                <option value="all">All Years</option>
                                <option value="5plus">More than 5 Years</option>
                                <option value="5minus">Less than 5 Years</option>
                            </select>
                        </div>
                    </div>

                    <!-- Employees Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="employeesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Joining Date</th>
                                    <th>Years of Service</th>
                                    <th>Status</th>
                                    <th>Highlight</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                <tr id="employee-row-{{ $employee->id }}" class="{{ $employee->is_active && $employee->years_of_service > 5 ? 'table-success' : '' }}">
                                    <td>{{ $employee->id }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>{{ $employee->joining_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ number_format($employee->years_of_service, 1) }} years
                                        </span>
                                    </td>
                                    <td>
                                        @if($employee->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->is_active && $employee->years_of_service > 5)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star"></i> 5+ Years
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Regular</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewEmployee({{ $employee->id }})" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="editEmployee({{ $employee->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteEmployee({{ $employee->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addEmployeeForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position *</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="joining_date" class="form-label">Joining Date *</label>
                            <input type="date" class="form-control" id="joining_date" name="joining_date" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEmployeeForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_position" class="form-label">Position *</label>
                            <input type="text" class="form-control" id="edit_position" name="position" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_joining_date" class="form-label">Joining Date *</label>
                            <input type="date" class="form-control" id="edit_joining_date" name="joining_date" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_is_active" class="form-label">Status</label>
                            <select class="form-control" id="edit_is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Employee Modal -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewEmployeeDetails">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Add Employee
    $('#addEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("employees.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addEmployeeModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // Edit Employee
    window.editEmployee = function(id) {
        $.get('/employees/' + id, function(employee) {
            $('#edit_id').val(employee.id);
            $('#edit_name').val(employee.name);
            $('#edit_email').val(employee.email);
            $('#edit_position').val(employee.position);
            $('#edit_joining_date').val(employee.joining_date.split('T')[0]);
            $('#edit_is_active').val(employee.is_active ? 1 : 0);
            $('#editEmployeeModal').modal('show');
        });
    }

    // Update Employee
    $('#editEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        $.ajax({
            url: '/employees/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editEmployeeModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // View Employee
    window.viewEmployee = function(id) {
        $.get('/employees/' + id, function(employee) {
            var yearsOfService = (new Date().getFullYear() - new Date(employee.joining_date).getFullYear());
            var html = `
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">${employee.name}</h5>
                        <p class="card-text">
                            <strong>Email:</strong> ${employee.email}<br>
                            <strong>Position:</strong> ${employee.position}<br>
                            <strong>Joining Date:</strong> ${new Date(employee.joining_date).toLocaleDateString()}<br>
                            <strong>Years of Service:</strong> ${employee.years_of_service.toFixed(1)} years<br>
                            <strong>Status:</strong> 
                            <span class="badge ${employee.is_active ? 'bg-success' : 'bg-danger'}">
                                ${employee.is_active ? 'Active' : 'Inactive'}
                            </span><br>
                            <strong>Highlight Status:</strong>
                            ${employee.is_active && employee.years_of_service > 5 ? 
                                '<span class="badge bg-warning text-dark">5+ Years Veteran</span>' : 
                                '<span class="badge bg-secondary">Regular</span>'}
                        </p>
                    </div>
                </div>
            `;
            $('#viewEmployeeDetails').html(html);
            $('#viewEmployeeModal').modal('show');
        });
    }

    // Delete Employee
    window.deleteEmployee = function(id) {
        if (confirm('Are you sure you want to delete this employee?')) {
            $.ajax({
                url: '/employees/' + id,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#employee-row-' + id).fadeOut();
                },
                error: function(xhr) {
                    alert('Error deleting employee');
                }
            });
        }
    }

    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#employeesTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Filter by status
    $('#statusFilter').on('change', function() {
        var status = $(this).val();
        $('#employeesTable tbody tr').each(function() {
            var statusText = $(this).find('td:eq(6)').text().trim();
            if (status === 'all') {
                $(this).show();
            } else if (status === 'active' && statusText === 'Active') {
                $(this).show();
            } else if (status === 'inactive' && statusText === 'Inactive') {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Filter by years
    $('#yearsFilter').on('change', function() {
        var filter = $(this).val();
        $('#employeesTable tbody tr').each(function() {
            var yearsText = $(this).find('td:eq(5)').text();
            var years = parseFloat(yearsText);
            if (filter === 'all') {
                $(this).show();
            } else if (filter === '5plus' && years > 5) {
                $(this).show();
            } else if (filter === '5minus' && years <= 5) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>
@endpush
@endsection