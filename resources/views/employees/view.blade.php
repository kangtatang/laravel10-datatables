<div class="modal-body">
    <h5>[ Nama: {{ $employee->first_name }} {{ $employee->last_name }} ]</h5>
    <hr>
    <table class="table table-sm table-striped">
        <tr>
            <td style="width: 200px;">Tanggal Lahir</td>
            <td>: {{ date('d F Y', strtotime($employee->birth_date)) }}</td>
        </tr>
        <tr>
            <td style="width: 200px;">Alamat</td>
            <td>: {{ $employee->address }}</td>
        </tr>
        <tr>
            <td>Tanggal Bergabung</td>
            <td>: {{ date('d F Y', strtotime($employee->hire_date)) }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>: {{ $employee->email }}</td>
        </tr>
        <tr>
            <td>Nomor Telepon</td>
            <td>: {{ formatPhoneNumber($employee->phone_number) }}</td>
        </tr>
        <tr>
            <td>Departemen</td>
            <td>: {{ $employee->department->name }}</td>
        </tr>
        <tr>
            <td>Total Cuti</td>
            <td>: {{ $employee->total_leave_requests }} Hari</td>
        </tr>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" onclick="closeDetailModal()" data-dismiss="modal">Close</button>
</div>
