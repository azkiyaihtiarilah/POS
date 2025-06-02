<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        // Validasi input data
        $request->validate([
            'barang_id'     => 'required|exists:m_barang,barang_id',   // Validasi barang_id
            'stok_jumlah'   => 'required|integer|min:1',               // Validasi stok_jumlah
            'stok_tanggal'  => 'required|date',                         // Validasi stok_tanggal
            'user_id'       => 'required|exists:m_user,user_id',        // Validasi user_id
        ]);
    
        try {
            // Simpan data stok
            StokModel::create([
                'barang_id'    => $request->barang_id,
                'stok_jumlah'  => $request->stok_jumlah,  // Pastikan nama field sesuai
                'stok_tanggal' => $request->stok_tanggal, // Pastikan nama field sesuai
                'user_id'      => $request->user_id,
            ]);
    
            // Redirect dengan pesan sukses
            return redirect('stok')->with('success', 'Data stok berhasil disimpan');
            
        } catch (\Exception $e) {
            // Jika terjadi error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    


    public function store_ajax(Request $request)
    {
        // Pastikan permintaan adalah AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            
            // Aturan validasi untuk data yang masuk
            $rules = [
                'barang_id'    => 'required|integer|exists:m_barang,barang_id',
                'user_id'      => 'required|integer|exists:m_user,user_id',
                'stok_tanggal' => 'required|date',
                'stok_jumlah'  => 'required|integer|min:1',
            ];

            // Lakukan validasi
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validation failed!',
                    'msgField'  => $validator->errors(),
                ]);
            }

            try {
                // Simpan data stok ke dalam database
                StokModel::create([
                    'barang_id'    => $request->barang_id,
                    'user_id'      => $request->user_id,
                    'stok_tanggal' => $request->stok_tanggal,
                    'stok_jumlah'  => $request->stok_jumlah,
                ]);

                // Mengembalikan response sukses
                return response()->json([
                    'status'  => true,
                    'message' => 'New stock data saved successfully!',
                ]);
            } catch (\Exception $e) {
                // Jika terjadi kesalahan
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to save data: ' . $e->getMessage(),
                ]);
            }
        }

        // Jika bukan permintaan AJAX atau JSON
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

    public function import()
    {
        $breadcrumb = (object)[
            'title' => 'Import Stok',
            'list' => ['Home', 'Stok', 'Import']
        ];

        $page = (object)[
            'title' => 'Form Import Stok'
        ];

        $activeMenu = 'Stok';

        return view('stok.import', compact('breadcrumb', 'page', 'activeMenu'));
    }
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file yang diunggah
            $request->validate([
                'file' => 'required|file|mimes:xlsx,csv,xls',
            ]);

            try {
                // Proses import file
                // Implementasikan logika import sesuai kebutuhan Anda

                return response()->json([
                    'status' => true,
                    'message' => 'File imported successfully!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to import file: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/stok');
    }
    public function export_excel()
{
    // Ambil data stok yang sudah ada
    $stok = StokModel::with('barang')->get();

    // Buat objek spreadsheet baru
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menambahkan header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Tanggal Stok');
    $sheet->setCellValue('C1', 'Nama Barang');
    $sheet->setCellValue('D1', 'Jumlah Stok');

    // Menambahkan format font tebal pada header
    $sheet->getStyle('A1:D1')->getFont()->setBold(true);

    // Menyusun data stok
    $no = 1;
    $baris = 2;
    foreach ($stok as $item) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, \Carbon\Carbon::parse($item->stok_tanggal)->format('d-m-Y H:i'));
        $sheet->setCellValue('C' . $baris, $item->barang->barang_nama ?? '-');
        $sheet->setCellValue('D' . $baris, $item->stok_jumlah);
        $baris++;
        $no++;
    }

    // Set lebar kolom secara otomatis
    foreach (range('A', 'D') as $columnID) {
        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Memberikan judul pada sheet
    $sheet->setTitle('Data Stok');
    
    // Menyimpan file dengan format Excel
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';

    // Menyiapkan header untuk download file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: no-cache');
    
    // Menyimpan file ke output untuk di-download
    $writer->save('php://output');
    exit;
}
    
}
