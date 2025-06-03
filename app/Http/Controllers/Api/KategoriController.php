<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        return KategoriModel::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'required',
            'kategori_nama' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama
        ]);

        if ($kategori) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'kategori' => $kategori
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }

    public function show(KategoriModel $kategori)
    {
        return $kategori;
    }

    public function update(Request $request, KategoriModel $kategori)
    {
        $kategori->update($request->all());
        return $kategori;
    }

    public function destroy(KategoriModel $kategori)
    {
        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}