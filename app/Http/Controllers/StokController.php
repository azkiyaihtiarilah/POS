<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object)[
            'title' => 'Data Stok Barang yang Tersedia'
        ];

        $activeMenu = 'Stok';

        $kategori = KategoriModel::all();

        $stok = StokModel::with(['barang.kategori']);

        // Filter jika ada kategori_id
        if ($request->filled('kategori_id')) {
            $stok->whereHas('barang', function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori_id);
            });
        }

        $stok = $stok->get();

        

        return view('stok.index', compact('breadcrumb', 'page', 'activeMenu', 'stok', 'kategori'));
    }

    public function list(Request $request)
    {
         $products = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'kategori_id', 'harga_beli', 'harga_jual')->with('kategori');
 
         // Filter data barang berdasarkan kategori_id
         if ($request->kategori_id) {
             $products->where('kategori_id', $request->kategori_id);
         }
 
         return DataTables::of($products)
             ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
             ->addColumn('aksi', function ($barang) {
                 // menambahkan kolom aksi
                 // $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                 // $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                 //     . csrf_field() . method_field('DELETE') .
                 //     '<button type="submit" class="btn btn-danger btn-sm"
                 //     onclick="return confirm(\'Apakah Anda yakit menghapus data
                 //     ini?\');">Hapus</button></form>';
                 $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
 
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
 
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
             ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Form Tambah Stok'
        ];

        $activeMenu = 'Stok';
        $barang = BarangModel::all();
        $user = UserModel::all();

        return view('stok.create', compact('breadcrumb', 'page', 'activeMenu', 'barang', 'user'));
    }

    public function create_ajax()
    {
        $barang = BarangModel::all();
        $user = UserModel::all();
        return view('stok.create_ajax', ['barang' => $barang, 'user' => $user]);
    }

    public function store(Request $request)
{
    $request->validate([
        'barang_id'      => 'required|exists:m_barang,barang_id',
        'stock_jumlah'   => 'required|integer|min:1',
        'stock_tanggal'  => 'required|date',
        'user_id'        => 'required|exists:m_user,user_id',
    ]);

    // Simpan data
    StokModel::create([
        'barang_id'     => $request->barang_id,
        'stok_jumlah'  => $request->stock_jumlah,
        'stok_tanggal' => $request->stock_tanggal,
        'user_id'       => $request->user_id,
    ]);

    return redirect('stok')->with('success', 'Data stok berhasil disimpan');
}


    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id' => 'required|integer',
                'user_id' => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed!',
                    'msgField' => $validator->errors()
                ]);
            }

            StokModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'New stock data saved successfully!'
            ]);
        }
        return redirect('/');
    }

    
    public function show($id)
    {
        $stok = StokModel::with(['barang', 'user'])->findOrFail($id);

        $breadcrumb = (object)[
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail Data Stok'
        ];

        $activeMenu = 'Stok';

        return view('stok.show', compact('breadcrumb', 'page', 'activeMenu', 'stok'));
    }

    public function show_ajax(string $id)
    {
        $stok = StokModel::with(['barang', 'user'])->find($id);
        return view('stok.show_ajax', compact('stok'));
    }

    public function edit($id)
    {
        $stok = StokModel::findOrFail($id);

        $breadcrumb = (object)[
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object)[
            'title' => 'Form Edit Stok'
        ];

        $activeMenu = 'Stok';
        $barang = BarangModel::all();
        $user = UserModel::all();

        return view('stok.edit', compact('breadcrumb', 'page', 'activeMenu', 'stok', 'barang', 'user'));
    }

    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::all();
        $user = UserModel::all();
        return view('stok.edit_ajax', ['stok' => $stok, 'barang' => $barang, 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:m_barang,barang_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer',
        ]);

        $stok = StokModel::findOrFail($id);

        //edit ganti stock
        $stok->barang_id = $request->barang_id;
        $stok->stok_tanggal = $request->stok_tanggal;
        $stok->stok_jumlah = $request->stok_jumlah;
        $stok->save();

        return redirect('/stok')->with('success', 'Stok berhasil diperbarui.');
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id' => 'required|integer',
                'user_id' => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed!',
                    'msgField' => $validator->errors()
                ]);
            }

            $stok = StokModel::find($id);
            if ($stok) {
                $stok->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Stock data updated successfully!'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Stock data not found!'
            ]);
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                try {
                    StokModel::destroy($id);
                    return response()->json([
                        'status' => true,
                        'message' => 'Stock data deleted successfully!'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Stock data fails to be deleted because it is still related to other data'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Stock data not found!'
                ]);
            }
        }
        return redirect('/');
    }

    public function destroy($id)
    {
        $stok = StokModel::find($id);

        if (!$stok) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan.');
        }

        try {
            $stok->delete();
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus.');
        }
    }

    
}
