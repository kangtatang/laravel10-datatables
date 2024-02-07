@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">DEPARTMENTS</h2>
            <hr>
            <button class="btn btn-sm btn-success mb-2" id="addDepartment">Add Department</button>
            <table class="table table-striped table-sm table-hover mb-4" id="departmentTable">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 20px;" class="text-center">No</th>
                        <th>Nama Divisi</th>
                        <th>Keterangan</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Department Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departmentModalLabel">Add Department</h5>
                <button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="departmentForm" data-action="{{ route('departments.store') }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required>
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
{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> --}}
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // DataTables
        var table = $('#departmentTable').DataTable({
            processing: false
            , serverSide: true
            , ajax: "{{ route('departments.get-data') }}", // Ganti sesuai dengan rute Yajra DataTables Anda
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
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'description'
                    , name: 'description'
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row) {
                        return `
            <button class="btn btn-primary btn-sm mr-2" id="edit-data" data-id="${row.id}">
                <i class="fa fa-edit"></i> Edit
            </button>
            <button class="btn btn-danger btn-sm"id="delete-data" data-id="${row.id}">
                <i class="fa fa-trash"></i> Delete
            </button>
        `;
                    }

                }
            ]
        });

        // Open Add Department Modal
        $('#addDepartment').click(function() {
            $('#departmentModalLabel').text('Add Department');
            $('#departmentForm').attr('data-action', "{{ route('departments.store') }}");
            $('#name').val('');
            $('#description').val('');
            $('#saveButton').text('Save');
            $('#departmentModal').modal('show');
        });

        // Edit Department
        $(document).on('click', '#edit-data', function() {
            var id = $(this).data('id');
            var url = "/departments/edit/" + id;
            var urlUpdate = "/departments/update/" + id;
            $.get(url, function(data) {
                $('#departmentModalLabel').text('Edit Department');
                $('#departmentForm').attr('data-action', urlUpdate);
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#saveButton').text('Update');
                $('#departmentModal').modal('show');
            });
        });

        // Add/Edit Department Form Submission
        $('#departmentForm').on('submit', function(event) {
            event.preventDefault();
            var action = $(this).attr('data-action');
            var method = (action === "{{ route('departments.store') }}") ? 'POST' : 'PUT';

            $.ajax({
                method: method
                , url: action
                , data: $(this).serialize()
                , dataType: 'json'
                , success: function(response) {
                    $('#departmentModal').modal('hide');
                    $('#departmentForm')[0].reset();
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

        // Delete Department
        $(document).on('click', '#delete-data', function() {
            if (confirm('Are you sure you want to delete this department?')) {
                var id = $(this).data('id');
                var urlDelete = "departments/delete/" + id;
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


    });

    // close modal
    function closeModal(modalId) {
        $('#departmentModal').modal('hide');
    }

</script>
@endsection

@section('scripts')

@endsection
