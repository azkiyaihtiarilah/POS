<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori'],
        ];

        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem',
        ];

        $activeMenu = 'kategori';

        $kategori = KategoriModel::all();

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page, 'kategori' => $kategori]);
    }

    public function list(Request $request)
     {
         $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
 
         if ($request->kategori_id) {
             $kategori->where('kategori_id', $request->kategori_id);
         }
 
         return DataTables::of($kategori)
             ->addIndexColumn()
             ->addColumn('aksi', function ($kategori) {
                 $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                 $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 // $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                 // $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
 
                 $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
 
                 $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Kategori',
             'list' => ['Home', 'Kategori', 'Tambah'],
         ];
 
         $page = (object) [
             'title' => 'Tambah kategori baru',
         ];
 
         $activeMenu = 'kategori';
 
         return view('kategori.create', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page]);
     }
 
     public function create_ajax()
     {
         return view('kategori.create_ajax');
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'kategori_kode' => 'required|string|max:5',
             'kategori_nama' => 'required|string|max:100',
         ]);
 
         KategoriModel::create([
             'kategori_kode' => $request->kategori_kode,
             'kategori_nama' => $request->kategori_nama,
         ]);
 
         return redirect('/kategori')->with('success', 'Data kategori berhasil ditambahkan');
     }
 
     public function store_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_kode' => 'required|string|max:5',
                 'kategori_nama' => 'required|string|max:100',
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(),
                 ]);
             }
 
             KategoriModel::create($request->all());
 
             return response()->json([
                 'status' => true,
                 'message' => 'Data kategori berhasil disimpan',
             ]);
         }
         return redirect('/');
     }
 
     public function show(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         $breadcrumb = (object) [
             'title' => 'Detail Kategori',
             'list' => ['Home', 'Kategori', 'Detail'],
         ];
 
         $page = (object) [
             'title' => 'Detail kategori',
         ];
 
         $activeMenu = 'kategori';
 
         return view('kategori.show', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page, 'kategori' => $kategori]);
     }

     public function show_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
         return view('kategori.show_ajax', ['kategori' => $kategori]);
     }
 
     public function edit(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         $breadcrumb = (object) [
             'title' => 'Edit Kategori',
             'list' => ['Home', 'Kategori', 'Edit'],
         ];
 
         $page = (object) [
             'title' => 'Edit kategori',
         ];
 
         $activeMenu = 'kategori';
 
         return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page, 'kategori' => $kategori]);
     }
 
     public function edit_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         return view('kategori.edit_ajax', ['kategori' => $kategori]);
     }
 
     public function update(Request $request, string $id)
     {
         $request->validate([
             'kategori_kode' => 'required|string|max:5',
             'kategori_nama' => 'required|string|max:100',
         ]);
 
         KategoriModel::find($id)->update([
             'kategori_kode' => $request->kategori_kode,
             'kategori_nama' => $request->kategori_nama,
         ]);
 
         return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
     }
 
     public function update_ajax(Request $request, string $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_kode' => 'required|string|max:5',
                 'kategori_nama' => 'required|string|max:100',
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(),
                 ]);
             }
             $check = KategoriModel::find($id);
             if ($check) {
                 $check->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data kategori berhasil diubah',
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data kategori tidak ditemukan',
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function confirm_ajax(string $id)
     {
         $kategori = KategoriModel::find($id);
 
         return view('kategori.confirm_ajax', ['kategori' => $kategori]);
     }
 
     public function delete_ajax(Request $request, string $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $kategori = KategoriModel::find($id);
             if ($kategori) {
                 $kategori->delete();
                 return response()->json([
                     'status' => true,
                     'message' => 'Data kategori berhasil dihapus',
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data kategori tidak ditemukan',
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function destroy(string $id)
     {
         $check = KategoriModel::find($id);
         if (!$check) {
             return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
         }
 
         try {
             KategoriModel::destroy($id);
             return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
         } catch (\Illuminate\Database\QueryException $e) {
             return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }

    public function import()
    {
        return view('kategori.import');
    }

    public function import_ajax(Request $request)
    {
        $rules = [
            'file_kategori' => ['required', 'mimes:xlsx,xls', 'max:1024'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $file = $request->file('file_kategori');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $insert = [];
        $header = array_shift($rows); // Ambil header

        foreach ($rows as $row) {
            if (!empty($row[0])) {
                $insert[] = [
                    'kategori_kode' => $row[0],
                    'kategori_nama' => $row[1],
                    'created_at' => now()
                ];
            }
        }

        if (!empty($insert)) {
            KategoriModel::insert($insert);
            return redirect('/kategori')->with('success', 'Data kategori berhasil diimport: ' . count($insert) . ' record');
        } else {
            return redirect('/kategori')->with('error', 'Data kategori kosong');
        }
    }
}

