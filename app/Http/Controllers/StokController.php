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

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:m_barang,barang_id',
            'user_id' => 'required|exists:m_user,user_id',
            'stock_tanggal' => 'required|date',
            'stock_jumlah' => 'required|integer',
        ]);

        $stok = StokModel::where('barang_id', $request->barang_id)->first();

    if ($stok) {
        $stok->stock_jumlah += $request->stock_jumlah;
        $stok->save();
    } else {
        StokModel::create([
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'stock_jumlah' => $request->stock_jumlah,
            'stock_tanggal' => $request->stock_tanggal,
        ]);
    }

        return redirect('/stok')->with('success', 'Data stok berhasil ditambahkan.');
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

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:m_barang,barang_id',
            'stock_tanggal' => 'required|date',
            'stock_jumlah' => 'required|integer',
        ]);

        $stok = StokModel::findOrFail($id);

        //edit ganti stock
        $stok->barang_id = $request->barang_id;
        $stok->stock_tanggal = $request->stock_tanggal;
        $stok->stock_jumlah = $request->stock_jumlah;
        $stok->save();

        return redirect('/stok')->with('success', 'Stok berhasil diperbarui.');
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
