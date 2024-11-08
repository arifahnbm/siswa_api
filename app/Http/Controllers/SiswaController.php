<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Siswa::all();
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data siswa.'], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:255'], // Hanya huruf dan spasi
            'kelas' => ['required', 'regex:/^[X|XI|XII]{1,3}[A-Z]{3}[0-9]+$/'], // Format "XIIIPA1"
            'umur' => ['required', 'integer', 'between:6,18'], // Rentang umur
        ]);

        try {
            $siswa = Siswa::create ($validatedData);
            return response()->json($siswa, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan data siswa.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return Siswa::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $validatedData = $request->validate([
                'nama' => ['sometimes', 'required', 'regex:/^[A-Za-z\s]+$/', 'max:255'], // Hanya huruf dan spasi
                'kelas' => ['sometimes', 'required', 'regex:/^[X|XI|XII]{1,3}[A-Z]{3}[0-9]+$/'], // Format "XIIIPA1"
                'umur' => ['sometimes', 'required', 'integer', 'between:6,18'], // Rentang umur
            ]);

            $siswa->update($validatedData);
            return response()->json($siswa);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memperbarui data siswa.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
        }
    }
}
