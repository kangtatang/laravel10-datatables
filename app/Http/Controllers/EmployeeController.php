<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('employees.index', compact('departments'));
    }

    public function getData()
    {
        // $employees = Employee::select(['id', 'first_name', 'last_name', 'email', 'phone_number', 'address', 'birth_date', 'hire_date', 'department_id']);
        $employees = Employee::with('department')->get();


        return DataTables::of($employees)
            ->addIndexColumn()
            ->addColumn('action', function ($employee) {
                // Tambahkan kolom aksi sesuai kebutuhan Anda
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        Employee::create($request->all());

        return response()->json(['success' => 'Employee added successfully']);
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        // Validasi input menggunakan aturan validasi yang sesuai
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($id),
            ],
            'phone_number' => [
                'required',
                'string',
                Rule::unique('employees')->ignore($id),
            ],
            'address' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            // 'total_leave_requests' => 'required|integer',
        ]);
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $employee = Employee::findOrFail($id);
            $employee->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'birth_date' => $request->input('birth_date'),
                'hire_date' => $request->input('hire_date'),
                'department_id' => $request->input('department_id'),
                // 'total_leave_requests' => $request->input('total_leave_requests'),
            ]);

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['success' => 'Employee updated successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to update employee: ' . $e->getMessage()], 500);
        }
    }

    public function view($id)
    {
        // Mengambil data karyawan berdasarkan ID
        $employee = Employee::findOrFail($id);

        // Mengirim data karyawan ke tampilan modal
        return view('employees.view', compact('employee'));
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json(['success' => 'Employee deleted successfully']);
    }

    public function store(Request $request)
    {
        // Validasi input data
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone_number' => 'required|string|unique:employees,phone_number',
            'address' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            // 'total_leave_requests' => 'required|integer',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Buat objek Employee baru
            $employee = new Employee;
            $employee->first_name = $request->input('first_name');
            $employee->last_name = $request->input('last_name');
            $employee->email = $request->input('email');
            $employee->phone_number = $request->input('phone_number');
            $employee->address = $request->input('address');
            $employee->birth_date = $request->input('birth_date');
            $employee->hire_date = $request->input('hire_date');
            $employee->department_id = $request->input('department_id');
            // $employee->total_leave_requests = $request->input('total_leave_requests');
            $employee->save();

            // Commit transaksi jika berhasil
            DB::commit();

            // Berikan respons JSON sebagai konfirmasi
            return response()->json(['message' => 'Employee created successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to create employee: ' . $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('q');

        $employees = Employee::where('name', 'like', '%' . $searchTerm . '%')
            ->select('id', 'name')
            ->get();

        return response()->json($employees);
    }
}
