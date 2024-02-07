<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobTitle;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JobTitleController extends Controller
{
    public function index()
    {
        return view('job_titles.index');
    }

    public function getData()
    {
        $jobTitles = JobTitle::select(['id', 'title', 'description']);

        return DataTables::of($jobTitles)
            ->addIndexColumn()
            ->addColumn('action', function ($jobTitle) {
                // Tambahkan kolom aksi sesuai kebutuhan Anda
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        JobTitle::create($request->all());

        return response()->json(['success' => 'Job Title added successfully']);
    }

    public function edit($id)
    {
        $jobTitle = JobTitle::findOrFail($id);

        return response()->json($jobTitle);
    }

    public function update(Request $request, $id)
    {
        // Validasi input menggunakan aturan validasi yang sesuai
        $this->validate($request, [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $jobTitle = JobTitle::findOrFail($id);
            $jobTitle->update([
                'title' => $request->input('title'),
                'description' => strip_tags($request->input('description')),
            ]);

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['success' => 'Job Title updated successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to update job title: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $jobTitle = JobTitle::findOrFail($id);
        $jobTitle->delete();

        return response()->json(['success' => 'Job Title deleted successfully']);
    }

    public function store(Request $request)
    {
        // Validasi input data
        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        // Membersihkan input 'description' dari karakter berbahaya
        $description = strip_tags($validatedData['description']);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Buat objek JobTitle baru
            $jobTitle = new JobTitle;
            $jobTitle->title = $validatedData['title'];
            $jobTitle->description = $description;

            // Simpan data ke dalam database
            $jobTitle->save();

            // Commit transaksi jika berhasil
            DB::commit();

            // Berikan respons JSON sebagai konfirmasi
            return response()->json(['message' => 'Job Title created successfully']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Tangani kesalahan dan kembalikan pesan kesalahan
            return response()->json(['error' => 'Failed to create job title: ' . $e->getMessage()], 500);
        }
    }
}
