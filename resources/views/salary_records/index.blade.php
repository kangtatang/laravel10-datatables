@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Salary Records</h2>
            <hr>
            <button class="btn btn-sm btn-success mb-2" id="addSalaryRecord">Add Salary Record</button>
            <table class="table table-striped table-bordered table-sm table-hover mb-4" id="salaryRecordTable">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 40px;" class="text-center">No</th>
                        <th>Employee</th>
                        <th>Salary Amount</th>
                        <th>Bonus Amount</th>
                        <th>Deduction Amount</th>
                        <th>Payment Date</th>
                        <th style="width: 200px;" class="text-center">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Salary Record Modal -->
<div class="modal fade" id="salaryRecordModal" tabindex="-1" role="dialog" aria-labelledby="salaryRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="salaryRecordModalLabel">Add Salary Record</h5>
                <button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="salaryRecordForm" data-action="{{ route('salary-records.store') }}">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="employee_id">Employee</label>
                        <select class="form-control" id="employee_id" name="employee_id" required>
                            <option value="">Pilih Employee</option>
                            @foreach ($employees as $employee )
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="salary-amount">Salary Amount</label>
                        <input type="number" class="form-control" id="salary_amount" name="salary_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="bonus_amount">Bonus Amount</label>
                        <input type="number" class="form-control" id="bonus_amount" name="bonus_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="deduction_amount">Deduction Amount</label>
                        <input type="number" class="form-control" id="deduction_amount" name="deduction_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_date">Payment Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
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

        var table = $('#salaryRecordTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('salary-records.get-data') }}"
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: null, // Gunakan null karena kita akan menggabungkan data
                    name: 'employee.first_name', // Nama kolom (bisa diganti sesuai kebutuhan)
                    searchable: true
                    , render: function(data, type, row) {
                        // Gabungkan first_name dan last_name dengan spasi di antaranya
                        return row.employee.first_name + ' ' + row.employee.last_name;
                    }
                }
                , {
                    data: 'salary_amount'
                    , name: 'salary_amount'
                    , render: function(data, type, row) {
                        // Ubah angka menjadi format dengan pemisah ribuan tanpa desimal
                        return parseFloat(data).toLocaleString('id-ID', {
                            minimumFractionDigits: 0
                        });
                    }
                }
                , {
                    data: 'bonus_amount'
                    , name: 'bonus_amount'
                    , render: function(data, type, row) {
                        // Ubah angka menjadi format dengan pemisah ribuan tanpa desimal
                        return parseFloat(data).toLocaleString('id-ID', {
                            minimumFractionDigits: 0
                        });
                    }
                }
                , {
                    data: 'deduction_amount'
                    , name: 'deduction_amount'
                    , render: function(data, type, row) {
                        // Ubah angka menjadi format dengan pemisah ribuan tanpa desimal
                        return parseFloat(data).toLocaleString('id-ID', {
                            minimumFractionDigits: 0
                        });
                    }
                }
                , {
                    data: 'payment_date'
                    , name: 'payment_date'
                    , render: function(data, type, row) {
                        // Buat objek tanggal dari data
                        var paymentDate = new Date(data);

                        // Daftar nama bulan dalam bahasa Indonesia
                        var monthNames = [
                            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'
                            , 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];

                        // Format tanggal sesuai dengan preferensi Anda
                        var formattedDate = paymentDate.getDate() + ' ' +
                            monthNames[paymentDate.getMonth()] + ' ' +
                            paymentDate.getFullYear();

                        return formattedDate;
                    }
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                    , className: 'text-center'
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
                , {
                    data: 'employee.last_name', // Kolom data tersembunyi
                    name: 'employee.last_name', // Nama sebenarnya
                    visible: false, // Kolom ini tidak akan ditampilkan
                    searchable: true, // Kolom ini dapat dicari
                }
            , ]
        });

        // Open Add Salary Record Modal
        $('#addSalaryRecord').click(function() {
            $('#salaryRecordModalLabel').text('Add Salary Record');
            $('#salaryRecordForm').attr('data-action', "{{ route('salary-records.store') }}");
            $('#employee_id').val('').trigger('change');
            $('#salary-amount').val('');
            $('#bonus_amount').val('');
            $('#deduction_amount').val('');
            $('#payment_date').val('');
            $('#saveButton').text('Save');
            $('#salaryRecordModal').modal('show');
        });

        // Edit Salary Record
        $(document).on('click', '#edit-data', function() {
            var id = $(this).data('id');
            var url = "/salary-records/edit/" + id;
            var urlUpdate = "/salary-records/update/" + id;
            $.get(url, function(data) {
                $('#salaryRecordModalLabel').text('Edit Salary Record');
                $('#salaryRecordForm').attr('data-action', urlUpdate);
                $('#employee_id').val(data.employee_id).trigger('change');
                $('#salary_amount').val(data.salary_amount);
                $('#bonus_amount').val(data.bonus_amount);
                $('#deduction_amount').val(data.deduction_amount);
                $('#payment_date').val(data.payment_date);
                $('#saveButton').text('Update');
                $('#salaryRecordModal').modal('show');
            });
        });

        // Add/Edit Salary Record Form Submission
        $('#salaryRecordForm').on('submit', function(event) {
            event.preventDefault();
            var action = $(this).attr('data-action');
            var method = (action === "{{ route('salary-records.store') }}") ? 'POST' : 'PUT';

            $.ajax({
                method: method
                , url: action
                , data: $(this).serialize()
                , dataType: 'json'
                , success: function(response) {
                    $('#salaryRecordModal').modal('hide');
                    $('#salaryRecordForm')[0].reset();
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

        // Delete Salary Record
        $(document).on('click', '#delete-data', function() {
            if (confirm('Are you sure you want to delete this salary record?')) {
                var id = $(this).data('id');
                var urlDelete = "/salary-records/delete/" + id;
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

    // Close modal function
    function closeModal() {
        $('#salaryRecordModal').modal('hide');
    }

</script>


@endsection

@section('scripts')
<!-- Tambahkan script tambahan jika diperlukan -->
<script>
    // Tambahkan script JavaScript untuk mengatur fungsi-fungsi yang diperlukan

</script>
@endsection
