<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departments.index');
    }

    public function getData()
    {
        $departments = Department::select(['id', 'name', 'description']);

        return DataTables::of($departments)
            ->addIndexColumn()
            ->addColumn('action', function ($department) {
                // Tambahkan kolom aksi sesuai kebutuhan Anda
            })
            ->rawColumns(['action'])
            ->make(true);
        // ->toJson();
    }

    public function create(Request $request)
    {
        Department::create($request->all());

        return response()->json(['success' => 'Department added successfully']);
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);

        return response()->json($department);
    }

    public function update(Request $request, $id)
    {
        // Validasi input menggunakan aturan validasi yang sesuai
        $this->validate($request, [
            'name' => 'required|string|regex:/^[A-Za-z0-9\s]+$/|max:150',
            'description' => 'nullable|string|regex:/^[A-Za-z0-9\s.,!?]+$/|max:255',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $department = Department::findOrFail($id);
            $department->update([
                'name' => $request->input('name'),
                'description' => strip_tags($request->input('description')),
            ]);

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['success' => 'Department updated successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to update department: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['success' => 'Department deleted successfully']);
    }

    public function store(Request $request)
    {
        // Validasi input data
        $validatedData = $request->validate([
            'name' => 'required|string|regex:/^[A-Za-z0-9\s]+$/|max:150',
            'description' => 'nullable|string|regex:/^[A-Za-z0-9\s.,!?]+$/|max:255',
        ]);

        // Membersihkan input 'description' dari karakter berbahaya
        $description = strip_tags($validatedData['description']);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Buat objek Departemen baru
            $department = new Department;
            $department->name = $validatedData['name'];
            $department->description = $description;

            // Simpan data ke dalam database
            $department->save();

            // Commit transaksi jika berhasil
            DB::commit();

            // Berikan respons JSON sebagai konfirmasi
            return response()->json(['message' => 'Department created successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to create department: ' . $e->getMessage()], 500);
        }
    }
}
