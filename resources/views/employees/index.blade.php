@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Employees</h2>
                </div>
                <div class="card-body">
                    <button class="btn btn-sm btn-success mb-2" id="addEmployee">Add Employee</button>
                    <table class="table table-striped table-sm table-hover mb-4" id="employeeTable">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 40px;" class="text-center">No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Department</th>
                                <th style="width: 200px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Add Employee</h5>
                <button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="employeeForm" data-action="{{ route('employees.store') }}">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="form-group">
                                <label for="birth_date">Birth Date</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                            </div>
                            <div class="form-group">
                                <label for="hire_date">Hire Date</label>
                                <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                            </div>
                            <div class="form-group">
                                <label for="department_id">Department</label>
                                <select class="form-control" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Detail --}}
<!-- Detail Employee Modal -->
<div class="modal fade" id="detailEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="detailEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailEmployeeModalLabel">Detail Employee</h5>
                <button type="button" class="close" onclick="closeDetailModal()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tempatkan data detail Employee di sini -->
                <div id="employeeDetailContent">
                    <!-- Ini tempat data akan dimasukkan dengan JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // DataTables
        var table = $('#employeeTable').DataTable({
            processing: false
            , serverSide: true
            , ajax: "{{ route('employees.get-data') }}", // Ganti sesuai dengan rute Yajra DataTables Anda
            columns: [{
                    data: 'DT_RowIndex', // Ini akan menampilkan nomor urut yang sesuai dengan pagination
                    name: 'DT_RowIndex'
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Menghasilkan nomor urut yang sesuai
                    }
                }
                , {
                    data: 'first_name'
                    , name: 'first_name'
                }
                , {
                    data: 'last_name'
                    , name: 'last_name'
                }
                , {
                    data: 'email'
                    , name: 'email'
                }
                , {
                    data: 'phone_number'
                    , name: 'phone_number'
                }
                , {
                    data: 'department.name', // Mengambil nama departemen
                    name: 'department.name'
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row) {
                        return `
                            <button class="btn btn-primary btn-sm mr-2 view-data" id="view-data" data-id="${row.id}">
                                <i class="fa fa-eye"></i> View
                            </button>
                            <button class="btn btn-primary btn-sm mr-2" id="edit-data" data-id="${row.id}">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" id="delete-data" data-id="${row.id}">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        `;
                    }
                }
            ]
        });

        // Open Add Employee Modal
        $('#addEmployee').click(function() {
            $('#employeeModalLabel').text('Add Employee');
            $('#employeeForm').attr('data-action', "{{ route('employees.store') }}");
            $('#first_name').val('');
            $('#last_name').val('');
            $('#email').val('');
            $('#phone_number').val('');
            $('#address').val('');
            $('#birth_date').val('');
            $('#hire_date').val('');
            $('#department_id').val('');
            $('#total_leave_requests').val('');
            $('#saveButton').text('Save');
            $('#employeeModal').modal('show');
        });

        // Edit Employee
        $(document).on('click', '#edit-data', function() {
            var id = $(this).data('id');
            var url = "/employees/edit/" + id;
            var urlUpdate = "/employees/update/" + id;
            $.get(url, function(data) {
                $('#employeeModalLabel').text('Edit Employee');
                $('#employeeForm').attr('data-action', urlUpdate);
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#email').val(data.email);
                $('#phone_number').val(data.phone_number);
                $('#address').val(data.address);
                $('#birth_date').val(data.birth_date);
                $('#hire_date').val(data.hire_date);
                $('#department_id').val(data.department_id);
                $('#total_leave_requests').val(data.total_leave_requests);
                $('#saveButton').text('Update');
                $('#employeeModal').modal('show');
            });
        });

        // Add/Edit Employee Form Submission
        $('#employeeForm').on('submit', function(event) {
            event.preventDefault();
            var action = $(this).attr('data-action');
            var method = (action === "{{ route('employees.store') }}") ? 'POST' : 'PUT';

            $.ajax({
                method: method
                , url: action
                , data: $(this).serialize()
                , dataType: 'json'
                , success: function(response) {
                    $('#employeeModal').modal('hide');
                    $('#employeeForm')[0].reset();
                    table.draw();
                }
                , error: function(response) {
                    // Handle validation errors or other errors
                    if (response.status === 422) {
                        var errors = response.responseJSON.errors;
                        var errorMessages = [];
                        $.each(errors, function(key, value) {
                            errorMessages.push(value[0]);
                        });
                        alert(errorMessages.join('\n'));
                    } else {
                        console.log(response);
                    }
                }
            });
        });

        // Delete Employee
        $(document).on('click', '#delete-data', function() {
            if (confirm('Are you sure you want to delete this employee?')) {
                var id = $(this).data('id');
                var urlDelete = "/employees/delete/" + id;
                $.ajax({
                    type: 'DELETE'
                    , url: urlDelete
                    , success: function(response) {
                        table.draw();
                    }
                    , error: function(response) {
                        console.log(response);
                    }
                });
            }
        });

        // Menampilkan Modal View Employee
        $(document).on('click', '#view-data', function() {
            var id = $(this).data('id');
            var url = "/employees/view/" + id; // Ganti dengan rute yang sesuai untuk mengambil data karyawan

            $.get(url, function(data) {
                $('#employeeDetailContent').html(data); // Memasukkan data detail ke dalam modal
                $('#detailEmployeeModal').modal('show'); // Menampilkan modal
            });
        });

    });

    // Close modal
    function closeModal() {
        $('#employeeModal').modal('hide');
    }

    function closeDetailModal() {
        $('#detailEmployeeModal').modal('hide');
    }

</script>
@endsection
