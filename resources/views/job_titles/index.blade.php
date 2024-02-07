@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2>JOB TITLES</h2>
                </div>
                <div class="card-body">
                    <button class="btn btn-sm btn-success mb-2" id="addJobTitle">Add Job Title</button>
                    <hr>
                    <table class="table table-striped table-bordered table-sm table-hover mb-4" id="jobTitleTable">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 20px;" class="text-center">No</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th style="width: 150px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Job Title Modal -->
<div class="modal fade" id="jobTitleModal" tabindex="-1" role="dialog" aria-labelledby="jobTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobTitleModalLabel">Add Job Title</h5>
                <button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="jobTitleForm" data-action="{{ route('job-titles.store') }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
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

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // DataTables
        var table = $('#jobTitleTable').DataTable({
            processing: false
            , serverSide: true
            , ajax: "{{ route('job-titles.get-data') }}", // Ganti sesuai dengan rute Yajra DataTables Anda
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
                    data: 'title'
                    , name: 'title'
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

        // Open Add Job Title Modal
        $('#addJobTitle').click(function() {
            $('#jobTitleModalLabel').text('Add Job Title');
            $('#jobTitleForm').attr('data-action', "{{ route('job-titles.store') }}");
            $('#title').val('');
            $('#description').val('');
            $('#saveButton').text('Save');
            $('#jobTitleModal').modal('show');
        });

        // Edit Job Title
        $(document).on('click', '#edit-data', function() {
            var id = $(this).data('id');
            var url = "/job-titles/edit/" + id;
            var urlUpdate = "/job-titles/update/" + id;
            $.get(url, function(data) {
                $('#jobTitleModalLabel').text('Edit Job Title');
                $('#jobTitleForm').attr('data-action', urlUpdate);
                $('#title').val(data.title);
                $('#description').val(data.description);
                $('#saveButton').text('Update');
                $('#jobTitleModal').modal('show');
            });
        });

        // Add/Edit Job Title Form Submission
        $('#jobTitleForm').on('submit', function(event) {
            event.preventDefault();
            var action = $(this).attr('data-action');
            var method = (action === "{{ route('job-titles.store') }}") ? 'POST' : 'PUT';

            $.ajax({
                method: method
                , url: action
                , data: $(this).serialize()
                , dataType: 'json'
                , success: function(response) {
                    $('#jobTitleModal').modal('hide');
                    $('#jobTitleForm')[0].reset();
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

        // Delete Job Title
        $(document).on('click', '#delete-data', function() {
            if (confirm('Are you sure you want to delete this job title?')) {
                var id = $(this).data('id');
                var urlDelete = "job-titles/delete/" + id;
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

    // Close modal
    function closeModal() {
        $('#jobTitleModal').modal('hide');
    }

</script>
@endsection
