<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryRecord;
use App\Models\Employee;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalaryRecordController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('salary_records.index', compact('employees'));
    }

    public function getData()
    {
        $salaryRecords = SalaryRecord::with('employee') // Mengambil relasi employee dengan kolom 'name'
            ->get();

        return DataTables::of($salaryRecords)
            ->addIndexColumn()
            ->addColumn('action', function ($salaryRecord) {
                // Tambahkan kolom aksi sesuai kebutuhan Anda
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        SalaryRecord::create($request->all());

        return response()->json(['success' => 'Salary Record added successfully']);
    }

    public function edit($id)
    {
        $salaryRecord = SalaryRecord::findOrFail($id);

        return response()->json($salaryRecord);
    }

    public function update(Request $request, $id)
    {
        // Validasi input data
        $this->validate($request, [
            'employee_id' => 'required|exists:employees,id',
            'salary_amount' => 'required|numeric',
            'bonus_amount' => 'nullable|numeric',
            'deduction_amount' => 'nullable|numeric',
            'payment_date' => 'required|date',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $salaryRecord = SalaryRecord::findOrFail($id);
            $salaryRecord->update([
                'employee_id' => $request->input('employee_id'),
                'salary_amount' => $request->input('salary_amount'),
                'bonus_amount' => $request->input('bonus_amount'),
                'deduction_amount' => $request->input('deduction_amount'),
                'payment_date' => $request->input('payment_date'),
            ]);

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['success' => 'Salary Record updated successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to update salary record: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        $salaryRecord = SalaryRecord::findOrFail($id);
        $salaryRecord->delete();

        return response()->json(['success' => 'Salary Record deleted successfully']);
    }

    public function store(Request $request)
    {
        // Validasi input data
        $this->validate($request, [
            'employee_id' => 'required|exists:employees,id',
            'salary_amount' => 'required|numeric',
            'bonus_amount' => 'nullable|numeric',
            'deduction_amount' => 'nullable|numeric',
            'payment_date' => 'required|date',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Buat objek SalaryRecord baru
            $salaryRecord = new SalaryRecord;
            $salaryRecord->employee_id = $request->input('employee_id');
            $salaryRecord->salary_amount = $request->input('salary_amount');
            $salaryRecord->bonus_amount = $request->input('bonus_amount');
            $salaryRecord->deduction_amount = $request->input('deduction_amount');
            $salaryRecord->payment_date = $request->input('payment_date');

            // Simpan data ke dalam database
            $salaryRecord->save();

            // Commit transaksi jika berhasil
            DB::commit();

            // Berikan respons JSON sebagai konfirmasi
            return response()->json(['message' => 'Salary record created successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to create salary record: ' . $e->getMessage()], 500);
        }
    }
}
